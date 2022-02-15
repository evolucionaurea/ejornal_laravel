<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCovidTesteosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('covid_testeos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_nomina');
            $table->integer('id_tipo');
            $table->date('fecha');
            $table->text('resultado');
            $table->string('laboratorio');
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
        Schema::dropIfExists('covid_testeos');
    }
}
