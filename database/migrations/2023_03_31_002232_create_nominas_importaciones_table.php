<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNominasImportacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // id, total, borrados, nuevos, actualizados, user, filename, timestamps

        Schema::create('nominas_importaciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('total');
            $table->unsignedInteger('nuevos');
            $table->unsignedInteger('existentes');
            $table->unsignedInteger('actualizados');
            $table->unsignedInteger('borrados');
            $table->unsignedInteger('year_month');
            $table->string('filename');
            $table->unsignedBigInteger('user_id')->nullable(); //el usuario que lo importó
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->foreign('cliente_id')->references('id')->on('clientes')->nullOnDelete();
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
        Schema::dropIfExists('nominas_importaciones');
    }
}
