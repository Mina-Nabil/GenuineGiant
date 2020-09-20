<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrdersTableAgain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table("orders", function(Blueprint $table){
            $table->double("ORDR_DLVR_FEES")->default(0);
            $table->foreignId("ORDR_DSLT_ID")->nullable()->constrained('delivery_slots');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("orders", function(Blueprint $table){
            $table->dropColumn("ORDR_DLVR_FEES");
            $table->dropForeign("orders_ordr_dslt_id_foreign");
            $table->dropColumn("ORDR_DSLT_ID");
        });
    }
}
