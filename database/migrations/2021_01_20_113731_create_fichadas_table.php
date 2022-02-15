<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFichadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fichadas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('horario_ingreso')->nullable();
            $table->date('horario_egreso')->nullable();
            $table->date('fecha_actual');
            $table->integer('id_user');
            $table->integer('id_cliente');
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
        Schema::dropIfExists('fichadas');
    }
}
