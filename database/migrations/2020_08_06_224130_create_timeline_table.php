<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimelineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timeline', function (Blueprint $table) {
            $table->id();
            $table->foreignId('TMLN_ORDR_ID')->constrained('orders');
            $table->unsignedInteger("TMLN_DASH_ID")->nullable();
            $table->foreign("TMLN_DASH_ID")->references("id")->on("dash_users");
            $table->string('TMLN_TEXT');
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table){
            $table->foreignId('ORDR_RTRN_ID')->nullable()->constrained('orders');
            $table->unsignedInteger("ORDR_DASH_ID")->nullable();
            $table->foreign("ORDR_DASH_ID")->references("id")->on("dash_users");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table){
            $table->dropForeign("orders_ordr_rtrn_id_foreign");
            $table->dropColumn("ORDR_RTRN_ID");
        });
        Schema::dropIfExists('timeline');
    }
}
