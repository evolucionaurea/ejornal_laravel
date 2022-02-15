<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIdAusentismoIdTipoToComunicacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comunicaciones', function (Blueprint $table) {
          // Fk
          $table->unsignedBigInteger('id_ausentismo')->change();
          $table->unsignedBigInteger('id_tipo')->change();

          // Relaciones
          $table->foreign('id_ausentismo')->references('id')->on('ausentismos')->change();
          $table->foreign('id_tipo')->references('id')->on('tipo_comunicacion')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comunicaciones', function (Blueprint $table) {
            //
        });
    }
}
