<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIdNominaIdTipoToCovidTesteosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('covid_testeos', function (Blueprint $table) {
          // Fk
          $table->unsignedBigInteger('id_nomina')->change();
          $table->unsignedBigInteger('id_tipo')->change();

          // Relaciones
          $table->foreign('id_nomina')->references('id')->on('nominas')->change();
          $table->foreign('id_tipo')->references('id')->on('covid_testeos_tipo')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('covid_testeos', function (Blueprint $table) {
            //
        });
    }
}
