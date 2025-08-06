<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyMultipleColumnsInConsultasEnfermeriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consultas_enfermerias', function (Blueprint $table) {
            $table->float('peso')->unsigned()->nullable()->change();
            $table->float('altura')->unsigned()->nullable()->change();
            $table->float('imc')->unsigned()->nullable()->change();
            $table->float('glucemia')->unsigned()->nullable()->change();
            $table->float('saturacion_oxigeno')->unsigned()->nullable()->change();
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
            //
        });
    }
}
