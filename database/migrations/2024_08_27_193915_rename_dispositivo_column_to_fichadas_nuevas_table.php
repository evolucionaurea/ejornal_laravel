<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameDispositivoColumnToFichadasNuevasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fichadas_nuevas', function (Blueprint $table) {
            $table->renameColumn('dispositivo','sistema_operativo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fichadas_nuevas', function (Blueprint $table) {
            $table->renameColumn('sistema_operativo','dispositivo');
        });
    }
}
