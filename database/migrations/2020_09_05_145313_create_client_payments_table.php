<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('CLPY_CLNT_ID')->constrained('clients');
            $table->foreignId('CLPY_DASH_ID')->constrained('dash_users');
            $table->double('CLPY_PAID');
            $table->double('CLPY_BLNC');
            $table->foreignId('CLPY_ORDR_ID')->nullable()->constrained('orders');
            $table->string('CLPY_CMNT')->nullable();
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
        Schema::dropIfExists('client_payments');
    }
}
