<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRawMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
            $table->string("RWMT_NAME")->unique();
            $table->string("RWMT_ARBC_NAME")->nullable();
            $table->double("RWMT_COST")->nullable(); //estimated cost
        });
        
        Schema::create('raw_inventory', function (Blueprint $table){
            $table->id();
            $table->foreignId("RINV_RWMT_ID")->constrained('raw_materials');
            $table->double("RINV_KMIN")->default(0);
            $table->double("RINV_KMIN")->default(0);
            $table->double("RINV_BLNC");
            $table->double("RINV_PRCE")->default(0); //price per kilo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('raw_materials');
    }
}
