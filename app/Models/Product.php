<?php

namespace App\Models;

use DateInterval;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $table = "products";
    public $timestamps = true;

    protected $attributes = [
        "PROD_COST" => 0
    ];

    public function subcategory(){
        return $this->belongsTo("App\Models\SubCategory", "PROD_SBCT_ID", 'id');
    }

    public function stock(){
        return $this->hasMany("App\Models\Inventory", "INVT_PROD_ID", "id");
    }

    public function ingredients(){
        return $this->hasMany('App\Models\Ingredient', "IGDT_PROD_ID");
    }

    public function rawMaterials(){
        return $this->belongsToMany("App\Models\RawMaterial", "ingredients", 'IGDT_PROD_ID','IGDT_RWMT_ID');
    }

    public static function newArrivals($dateInterval){
        return DB::table("products")
        ->join("inventory", "INVT_PROD_ID", "=", "products.id")
        ->select("products.*")->selectRaw("SUM(INVT_CUNT) as stock")
        ->groupBy("products.id")
        ->where("products.created_at" , ">", (new DateTime())->sub(new DateInterval($dateInterval)))
        ->get();
    }

    public function calculateCost(){
        $cost = 0;
        foreach($this->ingredients as $ingredient){
            $rawPrice = (RawMaterial::findOrFail($ingredient->IGDT_RWMT_ID))->RWMT_COST;
            $cost += $rawPrice * $ingredient->IGDT_GRAM / 1000 ;
        }
    }


}
