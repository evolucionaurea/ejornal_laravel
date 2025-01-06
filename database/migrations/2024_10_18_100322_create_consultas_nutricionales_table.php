<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultasNutricionalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultas_nutricionales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_nomina');
            $table->unsignedBigInteger('id_cliente');
            $table->enum('tipo', ['inicial', 'seguimiento'])->default('inicial');

            //Consulta Inicial
            $table->date('fecha');
            $table->text('objetivos')->nullable();
            $table->text('gustos_alimentarios')->nullable();
            $table->text('comidas_diarias')->nullable();
            $table->text('descanso')->nullable();
            $table->text('intolerancias_digestivas')->nullable();
            $table->text('alergias_alimentarias')->nullable();

            // Consulta Seguimiento
            $table->date('fecha_atencion')->nullable();
            $table->text('act_fisica')->nullable();
            $table->decimal('circunferencia_cintura', 5, 2)->nullable(); // Hasta 3 enteros y 2 decimales
            $table->decimal('porcent_masa_grasa', 5, 2)->nullable();     // Hasta 3 enteros y 2 decimales
            $table->decimal('porcent_masa_muscular', 5, 2)->nullable();  // Hasta 3 enteros y 2 decimales
            $table->text('transito_intestinal')->nullable();
            $table->text('evolucion')->nullable();
            $table->date('prox_cita')->nullable();
            $table->text('medicaciones')->nullable();
            $table->timestamps();

            $table->foreign('id_nomina')->references('id')->on('nominas');
            $table->foreign('id_cliente')->references('id')->on('clientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nutricionales');
    }
}
