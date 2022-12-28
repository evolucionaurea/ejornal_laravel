<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComunicacionesLivianasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comunicaciones_livianas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_tarea_liviana'); // Relacion
            $table->unsignedBigInteger('id_tipo'); // Relacion
            $table->string('user')->nullable();
            $table->text('descripcion');
            $table->timestamps();

            //Relaciones
            $table->foreign('id_tarea_liviana')->references('id')->on('tareas_livianas');
            $table->foreign('id_tipo')->references('id')->on('tareas_livianas_tipos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comunicaciones_livianas');
    }
}
