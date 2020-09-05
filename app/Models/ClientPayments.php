<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientPayments extends Model
{
    protected $table = "client_payments";
    protected $fillable = ['CLNT_PAID', 'CLNT_DASH_ID', 'CLNT_BLNC'];
    public function dash_user(){
        return $this->belongsTo("App\Models\DashUser", "CLPY_DASH_ID");
    }
}
