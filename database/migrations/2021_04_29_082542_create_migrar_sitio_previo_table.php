<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMigrarSitioPrevioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('migrar_sitio_previo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('clientes')->default(0);
            $table->integer('user_empleados')->default(0);
            $table->integer('nominas')->default(0);
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
        Schema::dropIfExists('migrar_sitio_previo');
    }
}
