<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEdicionesFichadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ediciones_fichadas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_fichada');
            $table->dateTime('old_ingreso')->nullable();
            $table->dateTime('old_egreso')->nullable();
            $table->dateTime('new_ingreso')->nullable();
            $table->dateTime('new_egreso')->nullable();
            $table->string('ip')->nullable();
            $table->string('dispositivo')->nullable();
            $table->timestamps();

            //Relaciones
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_fichada')->references('id')->on('fichadas_nuevas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ediciones_fichadas');
    }
}
