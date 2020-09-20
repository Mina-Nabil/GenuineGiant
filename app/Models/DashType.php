<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DashType extends Model
{
    protected $table = "dash_types";


    public function modules(){
        return $this->belongsToMany("App\Models\Module", "type_modules", "TPMD_DHTP_ID", "TPMD_MDUL_ID");
    }
}
