<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIdClienteIdUserToClienteUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cliente_user', function (Blueprint $table) {

          //// Esta migracion en el servidor no pasa, en local Si ////

          // Fk
          // $table->unsignedBigInteger('id_cliente')->change();
          // $table->unsignedBigInteger('id_user')->change();

          // Relaciones
          // $table->foreign('id_cliente')->references('id')->on('clientes')->change();
          // $table->foreign('id_user')->references('id')->on('users')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cliente_user', function (Blueprint $table) {
            //
        });
    }
}
