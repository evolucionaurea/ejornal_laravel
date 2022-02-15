<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIdNominaToConsultasEnfermeriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consultas_enfermerias', function (Blueprint $table) {
          // Fk
          $table->unsignedBigInteger('id_nomina')->change();

          // Relaciones
          $table->foreign('id_nomina')->references('id')->on('nominas')->change();
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
