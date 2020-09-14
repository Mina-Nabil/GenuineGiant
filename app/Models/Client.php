<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Client extends Model
{
    protected $table="clients";
    public $timestamps = true;

 
    public function area(){
        return $this->belongsTo("App\Models\Area", "CLNT_AREA_ID", 'id');
    }
    
    public function orders(){
        return $this->hasMany("App\Models\Order", "ORDR_CLNT_ID", "id");
    }

    public function moneyPaid(){
        return DB::table('orders')->where('ORDR_CLNT_ID', $this->id)->where('ORDR_STTS_ID', 4)
                ->selectRaw('SUM(ORDR_PAID) as paid, SUM(ORDR_DISC) as discount')
                ->get()->first();
    }

    public function itemsBought(){
        return DB::table('orders')->where('ORDR_CLNT_ID', $this->id)->where('ORDR_STTS_ID', 4)
        ->join('order_items', "ORIT_ORDR_ID", '=', 'orders.id')
        ->join('inventory', "ORIT_INVT_ID", '=', 'inventory.id')
        ->join('products', "INVT_PROD_ID", '=', 'products.id')
        ->selectRaw('SUM(ORIT_KGS) as ORIT_KGS, PROD_NAME, ORIT_PRCE')
        ->groupBy('order_items.id')
        ->get();
    }

    ///////transactions

    public function payments()
    {
        return $this->hasMany('App\Models\ClientPayments', 'CLPY_CLNT_ID');
    }

    public function pay($amount, $comment=null, $orderID=null)
    {
        DB::transaction(function () use ($amount, $comment, $orderID) {
            $this->CLNT_BLNC -= $amount;
            $payment =  new ClientPayments();
            $payment->CLPY_PAID = $amount;
            $payment->CLPY_CMNT = $comment;
            $payment->CLPY_ORDR_ID = $orderID;
            $payment->CLPY_BLNC = $this->CLNT_BLNC;
            $payment->CLPY_DASH_ID = Auth::user()->id;

            $this->payments()->save($payment);
            $cashTitle = "Recieved from " . $this->CLNT_NAME;
            if(isset($orderID) && is_numeric($orderID))
                $cashTitle .= " for order(" . $orderID . ")";
            Cash::entry($cashTitle, $amount, 0, $comment);
            $this->save();
        });
    }
}


