<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawInvoice extends Model
{
    protected $table = "raw_invoices";

    public function supplier(){
        return $this->belongsTo('App\Models\Supplier', "RINC_SUPP_ID");
    }

    public function dashuser(){
        return $this->belongsTo('App\Models\DashUser', "RINC_DASH_ID");
    }
}
