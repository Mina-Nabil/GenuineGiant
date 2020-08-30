<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = "order_items";
    public $timestamps = false;

    public $fillable = [
        "ORIT_INVT_ID", "ORIT_CUNT" 
    ];
    public $attributes = [
        "ORIT_KMS" => 0
    ];

    public function order()
    {
        return $this->belongsTo("App\Models\Order", "ORIT_ORDR_ID", "id");
    }

    public function inventory()
    {
        return $this->belongsTo("App\Models\Inventory", "ORIT_INVT_ID", "id");
    }
}
