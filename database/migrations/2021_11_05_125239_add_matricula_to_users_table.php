<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMatriculaToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('matricula')->nullable();
            $table->dateTime('fecha_vencimiento')->nullable();
            $table->string('archivo_matricula')->nullable();
            $table->string('hash_matricula')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('matricula');
            $table->dropColumn('archivo_matricula');
            $table->dropColumn('hash_matricula');
        });
    }
}
