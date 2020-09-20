<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class DashUser extends Authenticatable
{
    use Notifiable;
    protected $table = "dash_users";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'DASH_USNM', 'DASH_FLNM', 'DASH_PASS', 'DASH_IMGE', 'DASH_TYPE_ID',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         'remember_token',
    ];

    public function getAuthPassword(){
        return $this->DASH_PASS;
    }

    public function dash_types(){
        return $this->hasOne( "App\Models\DashType" , 'id', 'DASH_TYPE_ID');
    }

    public function modules(){
        return DB::table("modules")
                ->select("MDUL_NAME", "modules.id")
                ->join('type_modules', "TPMD_MDUL_ID", '=', 'modules.id')
                ->join('dash_types', 'TPMD_DHTP_ID', '=', 'dash_types.id')
                ->where("dash_types.id", "=", $this->DASH_TYPE_ID)->get();
    }
}
