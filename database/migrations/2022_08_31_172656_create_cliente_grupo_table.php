<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClienteGrupoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cliente_grupo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_grupo');
            $table->timestamps();
            $table->unique(['id_cliente', 'id_grupo']);

            //Foreign Key
            // $table->foreign('id_cliente')->references('id')->on('clientes')->cascadeOnDelete();
            // $table->foreign('id_grupo')->references('id')->on('grupos')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cliente_grupo');
    }
}
