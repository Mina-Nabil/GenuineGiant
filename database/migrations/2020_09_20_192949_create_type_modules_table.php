<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId("TPMD_DHTP_ID")->constrained("dash_types");
            $table->foreignId("TPMD_MDUL_ID")->constrained("modules");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('type_modules');
    }
}
