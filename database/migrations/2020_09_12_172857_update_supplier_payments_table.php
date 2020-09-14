<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSupplierPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_payments', function (Blueprint $table) {
        $table->double('SPPY_RCVD')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplier_payments', function (Blueprint $table) {
            $table->dropColumn('SPPY_RCVD')->default(0);
            });
    }
}
