<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultasEnfermeriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultas_enfermerias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_nomina');
            $table->date('fecha');
            $table->integer('amerita_salida');
            $table->integer('peso')->nullable();
            $table->integer('altura')->nullable();
            $table->integer('imc')->nullable(); // (peso sobre altura al cuadrado)
            $table->integer('glucemia')->nullable();
            $table->integer('saturacion_oxigeno')->nullable();
            $table->integer('id_diagnostico_consulta');
            $table->text('tension_arterial')->nullable();
            $table->integer('frec_cardiaca')->nullable();
            $table->string('derivacion_consulta');
            $table->text('observaciones');
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
        Schema::dropIfExists('consultas_enfermerias');
    }
}
