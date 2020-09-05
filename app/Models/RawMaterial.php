<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RawMaterial extends Model
{
    protected $table = "raw_materials";
    public $timestamps = false;

    public function ingredients()
    {
        return $this->belongsToMany("App\Models\Product", "ingredients", 'IGDT_RWMT_ID', 'IGDT_PROD_ID');
    }
    public function entries()
    {
        return $this->hasMany("App\Models\RawInventory", "RINV_RWMT_ID");
    }

    public function supplier(){
        return $this->belongsTo("App\Models\Supplier", "RINV_SUPP_ID");
    }

    public function addEntry($desc, $in, $out, $supplierID=null, $price = null, $comment = null)
    {
        $entryCost = ($in ?? 0) * $price ;

        $entry = new RawInventory();
        $entry->RINV_DESC = $desc;
        $entry->RINV_CMNT = $comment;
        $entry->RINV_SUPP_ID = $supplierID;
        $entry->RINV_IN = $in ?? 0;
        $entry->RINV_OUT = $out ?? 0;
        $entry->RINV_PRCE = $price;
        $entry->RINV_BLNC = $this->RWMT_BLNC + $entry->RINV_IN - $entry->RINV_OUT;
        $entry->RINV_DASH_ID = Auth::id();
        $supplier = null ;
        $payment = null ;
        if($supplierID != null && $in > 0 && is_numeric($in)) {
            $supplier = Supplier::findOrFail($supplierID);
            $payment =  new SupplierPayment();
            $payment->SPPY_PAID = -1*$entryCost;
            $payment->SPPY_CMNT = "Raw Material Entry";
            $payment->SPPY_BLNC = $supplier->SUPP_BLNC + $entryCost;
            $payment->SPPY_DASH_ID = Auth::user()->id;    
        }
        DB::transaction(function () use ($entry, $supplier, $payment) {     
            if ($entry->RINV_IN > 0) {
                $this->RWMT_COST = (($this->RWMT_COST*$this->RWMT_BLNC) + ($entry->RINV_PRCE * $entry->RINV_IN)) / ($entry->RINV_BLNC)  ;
                $supplier->SUPP_BLNC += ($entry->RINV_PRCE * $entry->RINV_IN);
                $supplier->save();
                $supplier->payments()->save($payment);
            }
            $this->RWMT_BLNC = $entry->RINV_BLNC;
            $this->entries()->save($entry);
            $this->save();
         
        });
    }

    private function adjustHistoricalCost()
    {
        //adjust stock from the beginning of time
        $entries = $this->entries()->where('RINV_IN', '>', 0)->get("RINV_IN", "RINV_PRCE");
        $cost = $this->RWMT_COST;
        $stock = $this->RWMT_BLNC;
        $price = $cost * $stock;
        foreach ($entries as $entry) {
            $stock += $entry->RINV_IN;
            $price += $entry->RINV_IN * $entry->RINV_PRCE;
        }
        $this->RWMT_COST = $price / $stock;
        $this->RWMT_BLNC = $stock;
        $this->save();
    }
}
