<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierPayment extends Model
{
    protected $table = "supplier_payments";
    protected $fillable = ['SUPP_PAID', 'SUPP_DASH_ID', 'SUPP_BLNC'];

    public function dash_user(){
        return $this->belongsTo("App\Models\DashUser", "SPPY_DASH_ID");
    }

    public function supplier(){
        return $this->belongsTo("App\Models\Supplier", "SPPY_SUPP_ID");
    }
}
