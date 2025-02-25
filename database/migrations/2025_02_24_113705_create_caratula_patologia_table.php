<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaratulaPatologiaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caratula_patologia', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_caratula');
            $table->unsignedBigInteger('id_patologia');
            $table->timestamps();

            $table->foreign('id_caratula')->references('id')->on('caratulas')->onDelete('cascade');
            $table->foreign('id_patologia')->references('id')->on('patologias')->onDelete('cascade');
        });

        // Eliminamos la columna id_patologia de la tabla caratulas ya que ahora la relación es en una tabla intermedia
        Schema::table('caratulas', function (Blueprint $table) {
            $table->dropForeign(['id_patologia']);
            $table->dropColumn('id_patologia');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('caratulas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_patologia')->nullable();
            $table->foreign('id_patologia')->references('id')->on('patologias');
        });

        Schema::dropIfExists('caratula_patologia');
    }
}
