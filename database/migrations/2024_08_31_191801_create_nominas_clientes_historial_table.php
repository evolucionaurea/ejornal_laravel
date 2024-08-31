<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateNominasClientesHistorialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nominas_clientes_historial', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('nomina_id');
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('user_id')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('nomina_id')->references('id')->on('nominas')->onDelete('cascade');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        // carga inicial
        DB::statement("
            INSERT INTO nominas_clientes_historial (nomina_id, cliente_id, user_id, created_at)
            SELECT id, id_cliente, NULL, NOW()
            FROM nominas n
            WHERE n.id_cliente IS NOT NULL
          ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nominas_clientes_historial');
    }
}
