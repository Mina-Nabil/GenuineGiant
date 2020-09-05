<?php

namespace App\Models;

use DateInterval;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $table = "orders";
    public $timestamps = true;

    public function order_items()
    {
        return $this->hasMany("App\Models\OrderItem", "ORIT_ORDR_ID", "id");
    }

    public function timeline()
    {
        return $this->hasMany("timeline", "TMLN_ORDR_ID", "id");
    }

    public function client()
    {
        return $this->belongsTo("App\Models\Client", "ORDR_CLNT_ID", "id");
    }

    public function area()
    {
        return $this->belongsTo("App\Models\Area", "ORDR_AREA_ID", "id");
    }

    public function status()
    {
        return $this->belongsTo("order_status", "ORDR_STTS_ID", "id");
    }

    public function driver()
    {
        return $this->belongsTo("App\Models\Driver", "ORDR_DRVR_ID", "id");
    }

    public function paymentOption()
    {
        return $this->belongsTo("payment_options", "ORDR_AREA_ID", "id");
    }

    public function recalculateTotal()
    {
        $total = 0;
        foreach ($this->order_items as $item) {
            $total += $item->ORIT_KGS * $item->ORIT_PRCE;
        }
        $this->ORDR_TOTL = $total;
        $this->save();
    }

    public static function getOrdersByDate(bool $currentMonth = true, int $month = -1, int $year = -1, int $state = -1)
    {

        $startDate = '';
        $endDate = '';

        if ($currentMonth) {
            $startDate = (new DateTime("first day of this month"))->format('Y-m-d 0:0:0');
            $endDate = (date_add((new DateTime('now')), new DateInterval("P01M")))->format('Y-m-1 0:0:0');
        } elseif ($month != -1 && $year == -1) {
            assert((0 < $month) && ($month < 13), 'Invalid Month');
            $year = date('Y');
            $startDate = $year . '-' . $month . '-01';
            $endDate   = date_add((new DateTime($startDate)), new DateInterval("P01M"))->format('Y-m-1 0:0:0');
        } elseif ($month == -1 && $year != -1) {
            $startDate = $year . '-01-01 00:00:00';
            $endDate = $year . '-12-31 12:59:59';
        } else {
            assert((0 < $month) && ($month < 13), 'Invalid Month');
            $startDate = $year . '-' . $month . '-01';
            $endDate   = date_add((new DateTime($startDate)), new DateInterval("P01M"))->format('Y-m-1 0:0:0');
        }


        $query =  self::tableQuery();

        if ($state > 0 && $state < 7) {
            $query = $query->where("ORDR_STTS_ID", "=", $state);
        } else {
            $query = $query->where("ORDR_STTS_ID", ">", 3);
        }
        $query->whereBetween("ORDR_DLVR_DATE", [$startDate, $endDate]);

        return $query->get();
    }

    public static function getOrdersByClient($userID)
    {
        $query = self::tableQuery();
        $query = $query->where('ORDR_CLNT_ID', $userID);
        return $query->get();
    }

    public static function getActiveOrders($state = -1)
    {
        $query = self::tableQuery();
        if ($state > 0 && $state < 6) {
            $query = $query->where("ORDR_STTS_ID", "=", $state);
        } else {
            $query = $query->where("ORDR_STTS_ID", "=", 1)->orWhere("ORDR_STTS_ID", "=", 1)->orWhere("ORDR_STTS_ID", "=", 2)->orWhere("ORDR_STTS_ID", "=", 3);
        }
        return $query->get();
    }

    public static function getOrderDetails($id)
    {
        $ret['order'] = DB::table("orders")
            ->join("order_status", "ORDR_STTS_ID", "=", "order_status.id")
            ->join("areas", "ORDR_AREA_ID", "=", "areas.id")
            ->Leftjoin("clients", "ORDR_CLNT_ID", "=", "clients.id")
            ->Leftjoin("dash_users", "ORDR_DASH_ID", "=", "dash_users.id")
            ->Leftjoin("drivers", "ORDR_DRVR_ID", "=", "drivers.id")
            ->Leftjoin("order_items", "ORIT_ORDR_ID", "=", "orders.id")
            ->join("payment_options", "ORDR_PYOP_ID", "=", "payment_options.id")
            ->select("orders.*", 'drivers.DRVR_NAME', "orders.ORDR_GEST_NAME", "order_status.STTS_NAME", "areas.AREA_NAME", "AREA_RATE", "clients.CLNT_NAME", "clients.CLNT_MOBN", "dash_users.DASH_USNM", "payment_options.PYOP_NAME")->selectRaw("SUM(ORIT_KGS) as itemsCount")
            ->groupBy("orders.id", "order_status.STTS_NAME", "areas.AREA_NAME", "clients.CLNT_NAME", "clients.CLNT_MOBN", "payment_options.PYOP_NAME")
            ->where('orders.id', $id)->get()->first();

        $ret['items'] = DB::table('order_items')->join("inventory", "ORIT_INVT_ID", "=", "inventory.id")
            ->join("products", "INVT_PROD_ID", "=", "products.id")
            ->select("order_items.id", "PROD_NAME", "ORIT_KGS", "ORIT_PRCE", "ORIT_VRFD", "ORIT_PRCE")
            ->where("ORIT_ORDR_ID", "=", $id)
            ->get();

        $ret['timeline'] = DB::table('timeline')
            ->join('dash_users', 'TMLN_DASH_ID', '=', 'dash_users.id')
            ->select('DASH_USNM', 'timeline.*')
            ->orderByDesc('timeline.id')
            ->where('TMLN_ORDR_ID', $id)->get();

        return $ret;
    }

    public static function getOrdersCountByState($state, $startDate = null, $endDate = null)
    {
        $query = DB::table("orders")->where("ORDR_STTS_ID", $state);

        if (!is_null($startDate) && !is_null($endDate)) {
            $query->whereBetween('ORDR_DLVR_DATE', [$startDate, $endDate]);
        }

        return $query->get()->count();
    }

    public static function getSalesIncome($month, $year, $catg = -1, $subCatg = -1)
    {
    }

    public static function getModelsIncome($month, $year, $product)
    {
    }

    private static function tableQuery()
    {
        return DB::table("orders")
            ->join("order_status", "ORDR_STTS_ID", "=", "order_status.id")
            ->join("areas", "ORDR_AREA_ID", "=", "areas.id")
            ->Leftjoin("clients", "ORDR_CLNT_ID", "=", "clients.id")
            ->Leftjoin("dash_users", "ORDR_DASH_ID", "=", "dash_users.id")
            ->Leftjoin("order_items", "ORIT_ORDR_ID", "=", "orders.id")
            ->join("payment_options", "ORDR_PYOP_ID", "=", "payment_options.id")
            ->select("orders.*", "order_status.STTS_NAME", "dash_users.DASH_USNM", "areas.AREA_NAME", "clients.CLNT_NAME", "clients.CLNT_MOBN", "payment_options.PYOP_NAME")->selectRaw("SUM(ORIT_KGS) as itemsCount")
            ->groupBy("orders.id", "orders.ORDR_STTS_ID", "orders.ORDR_CLNT_ID", "orders.ORDR_OPEN_DATE", "order_status.STTS_NAME", "areas.AREA_NAME", "clients.CLNT_NAME", "clients.CLNT_MOBN", "payment_options.PYOP_NAME");
    }

    public function addTimeline($text, $isdash = true)
    {
        $timeline = new Timeline();
        $timeline->TMLN_DASH_ID = ($isdash) ? Auth::user()->id : "NULL";
        $timeline->TMLN_ORDR_ID = $this->id;
        $timeline->TMLN_TEXT    = $text;
        $timeline->save();
    }
}
