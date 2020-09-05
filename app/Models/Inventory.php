<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Inventory extends Model
{
    protected $table = "inventory";
    protected $fillable = ['INVT_PROD_ID', 'INVT_KGS'];

    public function product()
    {
        return $this->belongsTo("App\Models\Product", "INVT_PROD_ID", "id");
    }

    static function returnOrderItems(Order $order)
    {
        $entryArr = array();
        foreach($order->order_items as $item){
            if($item->ORIT_VRFD == 1){
                $product = Product::findOrFail($item->inventory->INVT_PROD_ID);
                array_push($entryArr, [ "modelID" => $product->id , "count" => $item->ORIT_KGS ]);
            }
        }
        return self::insertEntry($entryArr, $order->id, true, "Order Return");
    }

    static public function insertEntry($entryArr, $orderID = null, $orderIn = false, $comment=null)
    {
        $transactionCode = date_format(now(), "ymdHis");
        $date = date_format(now(), "Y-m-d H:i:s");
        return DB::transaction(function () use ($entryArr, $transactionCode, $orderID, $date, $orderIn, $comment) {
            foreach ($entryArr as $row) {
                self::insert($row['modelID'], (($orderID == null) || $orderIn) ? $row['count'] : -1 * $row['count'], $transactionCode, $orderID, $date, $orderIn, $comment);
            }
        });
    }

    static public function insert($modelID, $count, $transactionCode, $orderID = null, $date = null, $orderIn = false, $comment=null)
    {

        return DB::transaction(function () use ($modelID, $count, $transactionCode, $orderID, $date, $orderIn, $comment) {
            $inventoryRow = self::firstOrNew(
                ["INVT_PROD_ID" => $modelID]
            );
            $inventoryRow->INVT_KGS += $count;

            $inventoryRow->save();

            if ($orderIn)
                self::addNewTransaction($inventoryRow->id, $inventoryRow->INVT_KGS, $transactionCode, $orderID, $count, 0, $date, $comment);
            else if ($count > 0)
                self::addNewTransaction($inventoryRow->id, $inventoryRow->INVT_KGS, $transactionCode, $orderID, $count, 0, $date, $comment);
            else
                self::addNewTransaction($inventoryRow->id, $inventoryRow->INVT_KGS, $transactionCode, $orderID, 0, -1 * $count, $date, $comment);
        });
    }


    ///////////////Inventory aggregates
    static public function getTotalPrice()
    {
        return DB::table("inventory")->join("products", "INVT_PROD_ID", '=', 'products.id')
            ->selectRaw("SUM(PROD_RETL_PRCE * INVT_KGS) as totalPrice")
            ->get()->first()->totalPrice;
    }
    static public function getTotalCost()
    {
        return DB::table("inventory")->join("products", "INVT_PROD_ID", '=', 'products.id')
            ->selectRaw("SUM(PROD_COST * INVT_KGS) as totalCost")
            ->get()->first()->totalCost;
    }


    /////////////Insert New Transaction function
    static public function getGroupedTransactions()
    {
        return DB::table("inventory_transactions")->join("dash_users", "INTR_DASH_ID", "=", "dash_users.id")
            ->selectRaw("INTR_CODE, INTR_DATE, INTR_DASH_ID, SUM(INTR_IN) as totalIn, SUM(INTR_OUT) as totalOut, DASH_USNM, INTR_ORDR_ID")
            ->groupByRaw("INTR_CODE, INTR_DATE, INTR_DASH_ID, DASH_USNM, INTR_ORDR_ID")
            ->orderByDesc("INTR_DATE")
            ->limit(500)
            ->get();
    }

    static public function getTransactionByCode($code)
    {
        return DB::table("inventory_transactions")->join("dash_users", "INTR_DASH_ID", "=", "dash_users.id")
            ->join("inventory", "INTR_INVT_ID", "=", "inventory.id")
            ->join("products", "INVT_PROD_ID", "=", "products.id")
            ->select("inventory_transactions.*", "dash_users.DASH_USNM", "products.PROD_NAME", "inventory.INVT_PROD_ID", "INTR_CMNT")
            ->where("INTR_CODE", $code)
            ->get();
    }




    static private function addNewTransaction($inventoryID, $balance, $transactionCode, $orderID = null, $in = 0, $out = 0, $date = null, $comment=null)
    {
        DB::table("inventory_transactions")->insert([
            "INTR_DATE"     => ($date) ?? date_format(now(), "Y-m-d H:i:s"),
            "INTR_CODE"     =>  $transactionCode,
            "INTR_INVT_ID"  =>  $inventoryID,
            "INTR_DASH_ID"  =>  Auth::id(),
            'INTR_IN'       =>  $in,
            'INTR_OUT'      =>  $out,
            'INTR_BLNC'     =>  $balance,
            'INTR_CMNT'     =>  $comment,
            'INTR_ORDR_ID' =>  $orderID
        ]);
    }
}
