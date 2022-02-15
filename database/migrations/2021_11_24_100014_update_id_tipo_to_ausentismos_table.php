<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIdTipoToAusentismosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ausentismos', function (Blueprint $table) {
          // Fk
          $table->unsignedBigInteger('id_tipo')->change();

          // Relaciones
          $table->foreign('id_tipo')->references('id')->on('ausentismo_tipo')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ausentismos', function (Blueprint $table) {
            //
        });
    }
}
