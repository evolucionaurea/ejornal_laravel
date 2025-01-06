<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaratulasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caratulas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_nomina');
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_patologia');
            $table->text('medicacion_habitual')->nullable();
            $table->text('antecedentes')->nullable();
            $table->text('alergias')->nullable();
            $table->double('peso')->nullable();
            $table->double('altura')->nullable();
            $table->double('imc')->nullable(); 
            $table->timestamps();

            $table->foreign('id_nomina')->references('id')->on('nominas');
            $table->foreign('id_cliente')->references('id')->on('clientes');
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
        Schema::dropIfExists('caratulas');
    }
}
