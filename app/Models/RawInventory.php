<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawInventory extends Model
{
    protected $table ="raw_inventory";
    public $timestamps = true;

    public function raw_material(){
        return $this->belongsTo("App\Models\RawMaterial", "RINV_RWMT_ID");
    }
    public function dash_user(){
        return $this->belongsTo("App\Models\DashUser", "RINV_DASH_ID");
    }
    public function supplier(){
        return $this->belongsTo("App\Models\Supplier", "RINV_SUPP_ID");
    }
}
