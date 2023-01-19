<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTareasLivianasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tareas_livianas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_trabajador');
            $table->integer('id_tipo');
            $table->string('user');
            $table->date('fecha_inicio');
            $table->date('fecha_final')->nullable();
            $table->date('fecha_regreso_trabajar')->nullable();
            $table->string('archivo')->nullable();
            $table->text('hash_archivo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tareas_livianas');
    }
}
