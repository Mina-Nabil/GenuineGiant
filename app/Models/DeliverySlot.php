<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliverySlot extends Model
{
    protected $table = "delivery_slots";

    public $timestamps = false;

    protected $dates = ['DSLT_STRT', 'DSLT_END'];

    public function orders(){
        return $this->hasMany('App\Models\Order', 'ORDR_DSLT_ID');
    }

    
}
