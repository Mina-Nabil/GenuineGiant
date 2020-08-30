<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    public $timestamps = false;
    public $fillable = ["IGDT_RWMT_ID", "IGDT_PROD_ID", "IGDT_GRAM"];

    public function raw_material(){
        return $this->belongsTo('App\Models\RawMaterial', "IGDT_RWMT_ID");
    }
}
