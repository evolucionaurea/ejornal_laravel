<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFichadasNuevasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fichadas_nuevas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('ingreso')->nullable();
            $table->dateTime('egreso')->nullable();
            $table->string('tiempo_dedicado')->nullable();
            $table->integer('id_user');
            $table->integer('id_cliente');
            $table->string('ip');
            $table->string('dispositivo')->nullable();
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
        Schema::dropIfExists('fichadas_nuevas');
    }
}
