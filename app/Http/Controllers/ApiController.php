<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Product;
use App\Models\Supplier;
use App\MOdels\Supplies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{

    public function getProductPrices(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'id' => 'required|exists:products,id'
        ]);

        if ($validation->fails()) {
            $errors = $validation->errors();
            return $this->returnAsJson("errors", $errors, false);
        }

        $product = Product::findOrFail($request->id);
        $arr = ['retail' => $product->PROD_RETL_PRCE, 'whole' => $product->PROD_WHLE_PRCE, 'inside' => $product->PROD_INSD_PRCE];
        return json_encode($arr);
    }

    public function getAreaPrice(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'id' => 'required|exists:areas,id'
        ]);

        if ($validation->fails()) {
            $errors = $validation->errors();
            return $this->returnAsJson("errors", $errors, false);
        }

        $area = Area::findOrFail($request->id);
        $arr = ['price' => $area->AREA_RATE];
        return json_encode($arr);
    }

    public function getSupplyPrice(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'rawID' => 'required|exists:raw_materials,id',
            'suppID' => 'required|exists:suppliers,id',
        ]);

        if ($validation->fails()) {
            $errors = $validation->errors();
            return $this->returnAsJson("errors", $errors, false);
        }

        $arr = ['price' => Supplies::getSupplyPrice($request->suppID, $request->rawID)];
        return json_encode($arr);
    }

    private function returnAsJson($key, $arr, $state = true)
    {
        echo json_encode([
            "status" => $state,
            $key => $arr
        ], JSON_UNESCAPED_UNICODE);
    }
}
