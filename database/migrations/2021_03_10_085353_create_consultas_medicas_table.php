<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultasMedicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultas_medicas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_nomina');
            $table->date('fecha');
            $table->integer('amerita_salida');
            $table->float('peso')->nullable();
            $table->float('altura')->nullable();
            $table->float('imc')->nullable(); // (Cuenta de peso y altura)
            $table->integer('glucemia')->nullable();
            $table->integer('saturacion_oxigeno')->nullable();
            $table->integer('id_diagnostico_consulta');
            $table->text('tension_arterial')->nullable();
            $table->integer('frec_cardiaca')->nullable();
            $table->string('derivacion_consulta');
            $table->text('anamnesis');
            $table->text('tratamiento');
            $table->text('observaciones')->nullable();
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
        Schema::dropIfExists('consultas_medicas');
    }
}
