<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNominasHistorialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nominas_historial', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('year_month');
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedInteger('cantidad');
            $table->foreign('cliente_id')->references('id')->on('clientes')->cascadeOnDelete();
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
        Schema::dropIfExists('nominas_historial');
    }
}
