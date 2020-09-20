<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliverySlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("delivery_slots", function(Blueprint $table){
            $table->id();
            $table->string("DSLT_NAME")->unique();
            $table->time("DSLT_STRT")->nullable();
            $table->time("DSLT_END")->nullable();
            $table->tinyInteger("DSLT_ACTV")->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_slots');
    }
}
