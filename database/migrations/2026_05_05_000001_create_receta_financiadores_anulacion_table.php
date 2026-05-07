<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecetaFinanciadoresAnulacionTable extends Migration
{
    public function up()
    {
        Schema::create('receta_financiadores_anulacion', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->integer('id_financiador');
            $t->string('nombre');
            $t->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('receta_financiadores_anulacion');
    }
}
