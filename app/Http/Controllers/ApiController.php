<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Product;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    
    public function getProductPrices(Request $request){
        
        $request->validate([
            'id' => 'required|exists:products,id'
        ]);

        $product = Product::findOrFail($request->id);
        $arr = ['retail' => $product->PROD_RETL_PRCE, 'whole' => $product->PROD_WHLE_PRCE, 'inside' => $product->PROD_INSD_PRCE ] ;
        return json_encode($arr);
    }
    
    public function getAreaPrice(Request $request){
        
        $request->validate([
            'id' => 'required|exists:areas,id'
        ]);

        $area = Area::findOrFail($request->id);
        $arr = ['price' => $area->AREA_RATE ] ;
        return json_encode($arr);
    }
}
