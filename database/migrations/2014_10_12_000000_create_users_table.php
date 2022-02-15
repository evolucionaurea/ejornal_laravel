<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_rol'); // Relacion
            $table->unsignedBigInteger('id_especialidad'); // Relacion
            $table->tinyInteger('id_cliente_actual')->nullable();
            $table->string('nombre');
            $table->tinyInteger('estado');
            $table->tinyInteger('fichada')->nullable()->default(0);
            $table->tinyInteger('personal_interno')->nullable()->default(0);
            $table->tinyInteger('permiso_desplegables')->nullable()->default(1);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            //Relaciones
            $table->foreign('id_rol')->references('id')->on('roles')
      			->onDelete('cascade')
      			->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
