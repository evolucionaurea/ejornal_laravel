<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNutricionalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nutricionales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_patologia');
            $table->string('objetivos');
            $table->string('medicacion');
            $table->string('objetivos');
            $table->string('descanso');
            $table->string('act_fisica');
            $table->string('peso');
            $table->string('talla');
            $table->string('circunferencia_cintura');
            $table->string('porcent_masa_grasa');
            $table->string('porcent_masa_muscular');
            $table->string('gustos_alimentarios');
            $table->string('tolerancia_digestiva');
            $table->string('comidas_diarias');
            $table->string('evolucion');
            $table->string('medicaciones');
            $table->timestamps();

            $table->foreign('id_patologia')->references('id')->on('patologias');

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
