<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    protected $table = "raw_materials";
    public $timestamps = false;

    public function ingredients(){
        return $this->belongsToMany("App\Models\Product", "ingredients", 'IGDT_RWMT_ID','IGDT_PROD_ID');
    }
}
