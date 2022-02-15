<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFkToStockMedicamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_medicamentos', function (Blueprint $table) {

          $table->unsignedBigInteger('id_medicamento')->change();
          $table->unsignedBigInteger('id_user')->change();
          $table->unsignedBigInteger('id_cliente')->change();

          //Foreign Key
          $table->foreign('id_medicamento')->references('id')->on('medicamentos')->change();
          $table->foreign('id_user')->references('id')->on('users')->change();
          $table->foreign('id_cliente')->references('id')->on('clientes')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_medicamentos', function (Blueprint $table) {
            //
        });
    }
}
