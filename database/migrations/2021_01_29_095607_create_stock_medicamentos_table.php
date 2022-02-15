<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockMedicamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_medicamentos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_medicamento');
            $table->integer('id_user');
            $table->integer('id_cliente');
            $table->integer('ingreso');
            $table->integer('suministrados');
            $table->integer('egreso');
            $table->integer('stock');
            $table->date('fecha_ingreso');
            $table->text('motivo');
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
        Schema::dropIfExists('stock_medicamentos');
    }
}
