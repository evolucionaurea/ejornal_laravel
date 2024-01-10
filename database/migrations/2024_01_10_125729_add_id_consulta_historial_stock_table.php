<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdConsultaHistorialStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_medicamentos_historial', function (Blueprint $table) {
            $table->unsignedBigInteger('id_consulta_medica')->after('id_stock_medicamentos')->nullable();
            $table->unsignedBigInteger('id_consulta_enfermeria')->after('ingreso')->nullable();

            // Relaciones
            $table->foreign('id_consulta_medica')->references('id')->on('consultas_medicas');
            $table->foreign('id_consulta_enfermeria')->references('id')->on('consultas_enfermerias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_medicamentos_historial', function (Blueprint $table) {
            $table->dropColumn('id_consulta_medica');
            $table->dropColumn('id_consulta_enfermeria');
        });
    }
}
