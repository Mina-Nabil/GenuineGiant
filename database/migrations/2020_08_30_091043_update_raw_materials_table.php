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

        Schema::table("raw_inventory", function(Blueprint $table){
            $table->foreignId('RINV_SUPP_ID')->nullable()->constrained("suppliers");
            $table->string('RINV_CMNT')->nullable();
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
        Schema::table("raw_inventory", function(Blueprint $table){
            $table->dropForeign('raw_inventory_rinv_supp_id');
            $table->dropColumn('RINV_SUPP_ID');
            $table->dropColumn('RINV_CMNT');
        });
    }
}
