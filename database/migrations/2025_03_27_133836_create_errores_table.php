<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateErroresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('errores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type'); // Tipo de error (warning, fatal, etc.)
            $table->text('message'); // Mensaje del error
            $table->string('file')->nullable(); // Archivo donde ocurrió el error
            $table->integer('line')->nullable(); // Línea del error
            $table->unsignedBigInteger('id_user')->nullable();
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
        Schema::dropIfExists('errores');
    }
}
