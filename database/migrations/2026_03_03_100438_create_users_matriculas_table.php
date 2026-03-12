<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersMatriculasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_matriculas', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('id_user');
            $table->string('tipo', 20); // 'MN', 'MP', etc.
            $table->string('nro', 50)->nullable();
            $table->date('fecha_vencimiento')->nullable();

            // Archivos (frente/dorso)
            $table->string('archivo_frente')->nullable(); // nombre original
            $table->string('hash_frente')->nullable();    // nombre físico
            $table->string('archivo_dorso')->nullable();
            $table->string('hash_dorso')->nullable();

            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['id_user', 'tipo']); // 1 matrícula por tipo por usuario
            $table->index(['tipo', 'fecha_vencimiento']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_matriculas');
    }
}
