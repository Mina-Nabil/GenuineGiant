<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_options', function (Blueprint $table) {
            $table->id();
            $table->string("PYOP_NAME")->unique();
            $table->string("PYOP_ARBC_NAME");
            $table->tinyInteger("PYOP_ACTV")->default(1);
        });

        Schema::create('order_status', function (Blueprint $table) {
            $table->id();
            $table->string("STTS_NAME")->unique();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId("ORDR_CLNT_ID")->nullable()->constrained("clients");
            $table->dateTime("ORDR_OPEN_DATE");
            $table->dateTime("ORDR_DLVR_DATE")->nullable();
            $table->foreignId("ORDR_STTS_ID")->constrained("order_status");
            $table->string("ORDR_GEST_NAME")->nullable();
            $table->string("ORDR_GEST_MOBN")->nullable();
            $table->string("ORDR_ADRS");
            $table->foreignId("ORDR_AREA_ID")->constrained("areas");
            $table->foreignId("ORDR_PYOP_ID")->constrained("payment_options");
            $table->double("ORDR_TOTL");
            $table->string("ORDR_NOTE")->nullable();
            $table->double("ORDR_PAID")->default(0);
            $table->foreignId("ORDR_DASH_CLCT")->constrained("dash_users"); 
            $table->timestamps();
        });

        Schema::create('order_items', function(Blueprint $table){
            $table->id();
            $table->foreignId("ORIT_ORDR_ID")->constrained("orders");
            $table->foreignId("ORIT_INVT_ID")->constrained("inventory");
            $table->tinyInteger("ORIT_PRCE");
            $table->tinyInteger("ORIT_KMS")->default(1);
        });

        Schema::table('inventory_transactions', function(Blueprint $table){
            $table->foreignId("INTR_ORDR_ID")->nullable()->constrained("orders");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_transactions', function(Blueprint $table){
            $table->dropForeign("inventory_transactions_intr_ordr_id_foreign");
            $table->dropColumn("INTR_ORDR_ID");
        });
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_status');
        Schema::dropIfExists('payment_options');
    }
}
