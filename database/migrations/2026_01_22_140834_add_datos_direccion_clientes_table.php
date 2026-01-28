<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatosDireccionClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('calle')->nullable()->after('direccion');
            $table->string('nro')->nullable()->after('calle');
            $table->unsignedBigInteger('id_provincia')->nullable()->after('nro');

            $table->foreign('id_provincia')->references('id')->on('provincias_recetas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('calle');
            $table->dropColumn('nro');
            $table->dropColumn('id_provincia');
        });
    }
}
