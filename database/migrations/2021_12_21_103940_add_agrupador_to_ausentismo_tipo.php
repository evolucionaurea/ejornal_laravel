<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAgrupadorToAusentismoTipo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ausentismo_tipo', function (Blueprint $table) {
            $table->string('agrupamiento', 25)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ausentismo_tipo', function (Blueprint $table) {
            $table->dropColumn('agrupamiento');
        });
    }
}
