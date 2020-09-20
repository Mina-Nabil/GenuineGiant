<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\Size;
use Illuminate\Http\Request;

class InventoryController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth");
    }

    public function entry()
    {
        //models data
        $data['products'] = Product::all();

        //form data
        $data['formURL'] = 'inventory/insert/entry';
        $data['formTitle'] = "New Stock Entry";

        return view("inventory.entry", $data);
    }

    public function insert(Request $request)
    {
        $entryArr = $this->getEntryArray($request);

        

       $entryStatus = Inventory::insertEntry($entryArr, null, false, "New Stock Entry");
        if($entryStatus !== false){
            $data['raws'] = RawMaterial::all();
            $data['ingredients'] =  $this->getIngredients($entryArr);
            $data['formTitle'] = "Raw Material Consumption";
            $data['formURL'] = url('raw/used/insert');
            $data['skipURL'] = url('inventory/current/stock');
            return view("inventory.usedRaw", $data);
        } else {
            abort(500, "Entry Failed! Please try again");
        }
        
    }

    public function consumeRaw(Request $request){
        $i=0;
        foreach($request->raw as $rawID){
            $raw = RawMaterial::findOrFail($rawID);
            $raw->addEntry(0, $request->count[$i++], null, 0, "Production Consumption", null, false);
        }
        return redirect('inventory/current/stock');
    }

    private function getIngredients($entryArr){
        $ret = array();
        foreach($entryArr as $entry){
            $product = Product::findOrFail($entry['modelID']);
            $ingredients = $product->ingredients;
            foreach($ingredients as $raw){
                $rawAmount = (($raw->IGDT_GRAM/1000)*$entry['count']) ;
                if(array_key_exists($raw->IGDT_RWMT_ID, $ret)){
                    $ret[$raw->IGDT_RWMT_ID] += $rawAmount;
                } else {
                    $ret[$raw->IGDT_RWMT_ID] = $rawAmount;
                }
            }
        }
        return $ret;
    }

    public function stock()
    {

        $data['items'] = Inventory::with(["product"])->get();

        $data['stockTitle'] = "Stock List";
        $data['stockSubtitle'] = "View Current Stock";
        $data['stockCols'] = ['Model', 'KGs Available', 'Retail Price', 'Whole Price', 'Inside Price', 'Cost'];
        $data['stockAtts'] = [
            ['foreignUrl' => ['products/details', 'INVT_PROD_ID', 'product', 'PROD_NAME']],
            ['number' => ['att' => 'INVT_KGS']],
            ['foreign' => ['product', 'PROD_RETL_PRCE']],
            ['foreign' => ['product', 'PROD_WHLE_PRCE']],
            ['foreign' => ['product', 'PROD_INSD_PRCE']],
            ['foreign' => ['product', 'PROD_COST']],
        ];

        $data['totalKG']        = $data['items']->sum('INVT_KGS');
        $data['totalPrice']   = Inventory::getTotalPrice();
        $data['totalCost']      = Inventory::getTotalCost();

        //transactions
        $data['trans'] = Inventory::getGroupedTransactions();
        $data['transTitle'] = "Latest Inventory Entries";
        $data['transSubtitle'] = "View the latest 500 inventory entries - Each Entry can be shown by the entry code";
        $data['transCols'] = ['Code', 'Date', 'Done by', 'Total In', 'Total Out', "Order#"];
        $data['transAtts'] = [
            ['attUrl' => ['url' => 'inventory/transaction', 'shownAtt' => 'INTR_CODE', 'urlAtt' => 'INTR_CODE']],
            "INTR_DATE",
            'DASH_USNM',
            'totalIn',
            'totalOut',
            ['dynamicUrl' => ['val' => 'INTR_ORDR_ID', 'att' => 'INTR_ORDR_ID', '0' => 'orders/details/']]
        ];

        return view("inventory.stock", $data);
    }

    public function transactionDetails($code)
    {
        $data['items'] = Inventory::getTransactionByCode($code);
        abort_if(!isset($data['items'][0]), 404);
        $data['title'] = "Entry " . ((isset($data['items'][0]->INTR_CODE)) ? $data['items'][0]->INTR_CODE : "") .  " details";
        $data['subTitle'] = "Inventory Entry Details" . ((isset($data['items'][0]->DASH_USNM)) ? " done by '" . $data['items'][0]->DASH_USNM . "'" : "") .
            ((isset($data['items'][0]->INTR_DATE)) ? " on " . $data['items'][0]->INTR_DATE : "");
        $data['cols'] = ['Code', 'Product', 'In', 'Out', 'Order#', "Comment"];
        $data['atts'] = [
            "INTR_CODE",
            ['attUrl' => ["url" => "products/profile", 'urlAtt' => 'INVT_PROD_ID', 'shownAtt' => 'PROD_NAME']],
            'INTR_IN',
            'INTR_OUT',
            ['dynamicUrl' => ['val' => 'INTR_ORDR_ID', 'att' => 'INTR_ORDR_ID', '0' => 'orders/details/']],
            ['comment' => ['att' => 'INTR_CMNT']]
        ];

        return view("inventory.table", $data);
    }

    public function transactions()
    {
        $data['items'] = Inventory::getGroupedTransactions();
        $data['title'] = "Latest Inventory Entries";
        $data['subTitle'] = "View the latest 500 inventory entries - Each Entry can be shown by the entry code";
        $data['cols'] = ['Code', 'Date', 'Done by', 'Total In', 'Total Out', 'Order#'];
        $data['atts'] = [
            ['attUrl' => ['url' => 'inventory/transaction', 'shownAtt' => 'INTR_CODE', 'urlAtt' => 'INTR_CODE']],
            "INTR_DATE",
            'DASH_USNM',
            'totalIn',
            'totalOut',
            ['dynamicUrl' => ['val' => 'INTR_ORDR_ID', 'att' => 'INTR_ORDR_ID', '0' => 'orders/details/']]
        ];

        return view("inventory.table", $data);
    }


    private function getEntryArray($request)
    {
        $ret = array();

        for ($i = 0; isset($request->count[$i]); $i++) {
            $ret[$i] = [
                "modelID" => $request->model[$i],
                "count" => $request->count[$i],
            ];
        }
        return $ret;
    }
}
