<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recetas', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->unsignedBigInteger('id_user');
            $t->unsignedBigInteger('id_nomina');
            $t->unsignedBigInteger('id_cliente');
            $t->string('hash_id')->unique();     // identificador HASH para anular
            $t->string('id_receta')->nullable(); // número de receta (si lo devuelve la API)
            $t->string('estado')->default('emitida'); // Estado Local: emitida|anulada|error
            $t->string('pdf_url')->nullable(); // URL o ruta al PDF (si la API devuelve un link)
            $t->json('payload');       // lo que enviamos a la API (Paciente/Medico/Matricula/Medicamentos/…)
            $t->json('response')->nullable(); // respuesta remota

            $t->timestamps();
            $t->softDeletes();

            $t->foreign('id_user')->references('id')->on('users');
            $t->foreign('id_nomina')->references('id')->on('nominas');
            $t->foreign('id_cliente')->references('id')->on('clientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recetas');
    }
}
