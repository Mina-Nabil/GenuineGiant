<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("PROD_NAME")->unique();
            $table->string("PROD_ARBC_NAME")->nullable();
            $table->foreignId("PROD_SBCT_ID")->constrained("sub_categories");
            $table->string("PROD_DESC")->nullable();
            $table->string("PROD_ARBC_DESC")->nullable();
            $table->double("PROD_INSD_PRCE");   // Tager le tager price per kilo
            $table->double("PROD_WHLE_PRCE");   // Gomla price per kilo 
            $table->double("PROD_RETL_PRCE");   //Ata3y price per kilo
            $table->double("PROD_COST")->nullable();
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
        Schema::dropIfExists('products');
    }
}
