<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeMatriculaToJsonInUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Pasar los strings vacíos a NULL para evitar conflictos
        DB::statement("UPDATE users SET matricula = NULL WHERE matricula = ''");

        // 2. Envolver los strings existentes en comillas para que sean JSON válido
        DB::statement("UPDATE users SET matricula = JSON_QUOTE(matricula) WHERE matricula IS NOT NULL");

        // 3. Ejecutar el cambio de tipo de dato
        DB::statement('ALTER TABLE users MODIFY matricula JSON');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE users MODIFY matricula VARCHAR(255)');
    }
}
