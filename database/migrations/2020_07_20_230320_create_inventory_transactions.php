<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->dateTime("INTR_DATE");
            $table->bigInteger("INTR_CODE");
            $table->foreignId("INTR_INVT_ID")->constrained("inventory");
            $table->foreignId("INTR_DASH_ID")->constrained("dash_users"); 
            // $table->foreignId("INTR_ORDR_ID")->constrained("orders");
            $table->tinyInteger("INTR_IN")->default(0);
            $table->tinyInteger("INTR_OUT")->default(0);
            $table->integer("INTR_BLNC");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_transactions');
    }
}
