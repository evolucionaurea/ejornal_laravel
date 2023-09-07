<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIncluirIndiceTipoAusentismosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ausentismo_tipo', function (Blueprint $table) {
            $table->tinyInteger('incluir_indice')->after('nombre')->nullable();
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
            $table->dropColumn('incluir_indice');
        });
    }
}
