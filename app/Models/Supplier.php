<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Supplier extends Model
{
    public $timestamps = false;

    public function payments()
    {
        return $this->hasMany('App\Models\SupplierPayment', 'SPPY_SUPP_ID');
    }

    public function supplies(){
        return $this->belongsToMany("App\Models\RawMaterial", "supplies", "SPLS_SUPP_ID", "SPLS_RWMT_ID");
    }

    public function rawMaterials(){
        return $this->hasManyThrough('App\Models\Supplies', 'App\Models\Supplies', "SPLS_SUPP_ID", "SPLS_RWMT_ID");
    }

    public function addInvoice($amount, $comment=null)
    {
        DB::transaction(function () use ($amount, $comment) {
            $this->SUPP_BLNC += $amount;
            $payment =  new SupplierPayment();
            $payment->SPPY_PAID = 0;
            $payment->SPPY_RCVD = $amount;
            $payment->SPPY_CMNT = $comment;
            $payment->SPPY_BLNC = $this->SUPP_BLNC;
            $payment->SPPY_DASH_ID = Auth::user()->id;

            $this->payments()->save($payment);
            $this->save();
        });
    }

    public function pay($amount, $comment=null)
    {
        DB::transaction(function () use ($amount, $comment) {
            $this->SUPP_BLNC -= $amount;
            $payment =  new SupplierPayment();
            $payment->SPPY_PAID = $amount;
            $payment->SPPY_CMNT = $comment;
            $payment->SPPY_BLNC = $this->SUPP_BLNC;
            $payment->SPPY_DASH_ID = Auth::user()->id;

            $this->payments()->save($payment);
            Cash::entry("Paid to " . $this->SUPP_NAME, 0, $amount, $comment);
            $this->save();
        });
    }

    public function getSuppliedRawMaterials()
    {
        return DB::table('raw_inventory')->join('raw_materials', 'RINV_RWMT_ID', '=', 'raw_materials.id')
            ->join('suppliers', 'RINV_SUPP_ID', '=', 'suppliers.id')
            ->selectRaw("RWMT_NAME, SUM(RINV_IN) as kgSupplied, AVG(RINV_PRCE) as avgPrice")
            ->where('suppliers.id', '=', $this->id)
            ->groupBy('raw_materials.id')->get();
    }

    public function getRegisteredRawMaterials()
    {
        return DB::table('raw_materials')->join('supplies', 'SPLS_RWMT_ID', '=', 'raw_materials.id')
            ->join('suppliers', 'SPLS_SUPP_ID', '=', 'suppliers.id')
            ->selectRaw("RWMT_NAME, SPLS_PRCE, raw_materials.id")
            ->where('suppliers.id', '=', $this->id)->get();
    }
}
