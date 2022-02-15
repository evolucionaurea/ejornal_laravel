<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultaMedicacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consulta_medicacion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_consulta_medica')->nullable();
            $table->integer('id_consulta_enfermeria')->nullable();
            $table->integer('id_medicamento');
            $table->integer('id_cliente');
            $table->integer('suministrados');
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
        Schema::dropIfExists('consulta_medicacion');
    }
}
