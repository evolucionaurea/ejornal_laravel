<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockMedicamentosHistorialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_medicamentos_historial', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_stock_medicamentos');
            $table->integer('ingreso')->nullable();
            $table->integer('suministrados')->nullable()->default(0);
            $table->integer('egreso')->nullable()->default(0);
            $table->integer('stock')->nullable();
            $table->text('motivo');
            $table->date('fecha_ingreso');
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
        Schema::dropIfExists('stock_medicamentos_historial');
    }
}
