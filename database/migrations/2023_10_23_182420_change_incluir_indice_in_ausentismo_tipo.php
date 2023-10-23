<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIncluirIndiceInAusentismoTipo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ausentismo_tipo', function (Blueprint $table) {
            $table->boolean('incluir_indice')->default(1)->change();
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
            $table->boolean('incluir_indice')->nullable()->change();
        });
    }
}
