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
            $table->unsignedInteger("INTR_DASH_ID")->nullable(); //producer
            $table->foreign("INTR_DASH_ID")->references("id")->on("dash_users"); //dash user
            $table->double("INVT_KMS")->default(0);
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
        Schema::dropIfExists('sizes');
    }
}
