<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

     
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId("INVT_PROD_ID")->constrained("products");
            $table->foreignId("INTR_DASH_ID")->nullable()->constrained("dash_users"); //producer
            $table->double("INVT_KGS")->default(0);
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
        Schema::dropIfExists('inventory');

    }
}
