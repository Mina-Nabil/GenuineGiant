<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) { //raw material invoice
            $table->id();
            $table->foreignID("INVC_SUPP_ID")->constrained("suppliers");
            $table->string("INVC_DESC");
            $table->string("INVC_CMNT")->nullable();
            $table->string("INVC_TOTL")->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
