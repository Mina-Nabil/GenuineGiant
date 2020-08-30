<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

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
        ->selectRaw('SUM(ORIT_KMS) as ORIT_KMS, PROD_NAME')
        ->groupBy('order_items.id')
        ->get();
    }
}


