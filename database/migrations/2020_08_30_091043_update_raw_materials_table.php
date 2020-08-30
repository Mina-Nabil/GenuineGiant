<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRawMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("raw_materials", function(Blueprint $table){
            $table->double('RWMT_ESTM_COST')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("raw_materials", function(Blueprint $table){
            $table->dropColumn('RWMT_ESTM_COST');
        });
    }
}
