<?php

namespace App\MOdels;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\DB;

class Supplies extends Pivot
{
    protected $table = "supplies";

    public static function getSupplyPrice($supplierID, $rawID){
        return self::where([
            ['SPLS_RWMT_ID', $rawID],
            ['SPLS_SUPP_ID', $supplierID]
            ])->get('SPLS_PRCE')->first()->SPLS_PRCE ?? -1;
    }
}
