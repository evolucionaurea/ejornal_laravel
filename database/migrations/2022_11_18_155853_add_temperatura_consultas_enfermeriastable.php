<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTemperaturaConsultasEnfermeriastable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consultas_enfermerias', function (Blueprint $table) {
            $table->float('temperatura_auxiliar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consultas_enfermerias', function (Blueprint $table) {
            $table->dropColumn('temperatura_auxiliar');
        });
    }
}
