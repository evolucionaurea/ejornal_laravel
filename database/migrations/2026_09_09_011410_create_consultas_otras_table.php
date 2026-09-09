<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultasOtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultas_otras', function (Blueprint $table) {
            $table->bigIncrements('id');
						$table->unsignedBigInteger('nomina_id');
						$table->unsignedBigInteger('cliente_id');
						$table->unsignedBigInteger('user_id');
						$table->date('fecha');
						$table->text('motivo');
						$table->text('desarrollo');
						$table->unsignedBigInteger('tipo_profesional_id');
						$table->timestamps();

						$table->foreign('nomina_id')->references('id')->on('nominas')->onDelete('cascade');
						$table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
						$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
						$table->foreign('tipo_profesional_id')->references('id')->on('profesionales_tipos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consultas_otras');
    }
}
