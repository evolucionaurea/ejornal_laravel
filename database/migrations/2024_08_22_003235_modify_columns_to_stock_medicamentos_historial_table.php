<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnsToStockMedicamentosHistorialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_medicamentos_historial', function (Blueprint $table) {
            $table->unsignedBigInteger('id_stock_medicamentos')->change();
            $table->text('motivo')->nullable()->default(null)->change();

            $table->foreign('id_stock_medicamentos')->references('id')->on('stock_medicamentos')->change();
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
            //
        });
    }
}
