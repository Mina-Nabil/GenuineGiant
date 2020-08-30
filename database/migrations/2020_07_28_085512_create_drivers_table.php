<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string("DRVR_NAME")->unique();
            $table->string("DRVR_MOBN")->unique();
            $table->string("DRVR_SRID")->unique();
            $table->tinyInteger("DRVR_ACTV")->default(1);
        });

        Schema::table("orders", function (Blueprint $table){
            $table->foreignId("ORDR_DRVR_ID")->nullable()->constrained("drivers");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("orders", function (Blueprint $table){
            $table->dropForeign("orders_ordr_drvr_id_foreign");
            $table->dropColumn("ORDR_DRVR_ID");
        });
        Schema::dropIfExists('drivers');
    }
}
