<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIdTrabajadorToAusentismosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ausentismos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_trabajador')->change();
            //$table->foreign('id_trabajador')->references('id')->on('nominas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ausentismos', function (Blueprint $table) {
            //
        });
    }
}
