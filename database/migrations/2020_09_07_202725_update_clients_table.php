<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("clients", function(Blueprint $table){
            $table->string("CLNT_LONG")->nullable();
            $table->string("CLNT_LATT")->nullable();
            $table->string("CLNT_AVGK")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("clients", function(Blueprint $table){
            $table->dropColumn("CLNT_LONG");
            $table->dropColumn("CLNT_LATT");
            $table->dropColumn("CLNT_AVGK");
        });
    }
}
