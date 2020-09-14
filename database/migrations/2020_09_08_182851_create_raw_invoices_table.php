<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRawInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('RINC_SUPP_ID')->constrained('suppliers');
            $table->foreignId('RINC_DASH_ID')->constrained('dash_users');
            $table->double("RINC_KGS")->default(0);
            $table->double("RINC_COST")->default(0);
            $table->double("RINC_PAID")->default(0);
            $table->double("RINC_DLVR_FEES")->default(0);
            $table->string("RINC_CMNT")->nullable();
            $table->timestamps();
        });

        Schema::table("raw_inventory", function(Blueprint $table){
            $table->foreignId('RINV_RINC_ID')->nullable()->constrained("raw_invoices");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("raw_inventory", function(Blueprint $table){
            $table->dropForeign('raw_inventory_rinv_rinc_id_foreign');
            $table->dropColumn('RINV_RINC_ID');
        });
        Schema::dropIfExists('raw_invoices');
    }
}
