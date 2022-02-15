<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClienteUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cliente_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_cliente'); // Relacion
            $table->unsignedBigInteger('id_user'); // Relacion
            $table->timestamps();
            $table->unique(['id_cliente', 'id_user']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cliente_empleado');
    }
}
