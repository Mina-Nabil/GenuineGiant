<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table){
            $table->id();
            $table->string("AREA_NAME")->unique();
            $table->string("AREA_ARBC_NAME");
            $table->double("AREA_RATE")->default(20);
            $table->tinyInteger("AREA_ACTV")->default(1);
        });


        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string("CLNT_NAME");
            $table->string("CLNT_ADRS")->nullable();
            $table->foreignId("CLNT_AREA_ID")->nullable()->constrained("areas");
            $table->string("CLNT_MOBN");
            $table->double("CLNT_BLNC")->default(0);
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
        Schema::dropIfExists('clients');
    
        Schema::dropIfExists('areas');
    }
}
