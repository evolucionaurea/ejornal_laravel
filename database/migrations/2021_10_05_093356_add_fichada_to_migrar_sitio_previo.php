<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFichadaToMigrarSitioPrevio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('migrar_sitio_previo', function (Blueprint $table) {
            $table->integer('fichada')->after('nominas')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('migrar_sitio_previo', function (Blueprint $table) {
            $table->dropColumn('fichada');
        });
    }
}
