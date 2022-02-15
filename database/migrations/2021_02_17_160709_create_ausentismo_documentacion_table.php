<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAusentismoDocumentacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ausentismo_documentacion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_ausentismo');
            $table->string('institucion', 60);
            $table->string('medico', 60);
            $table->integer('matricula_provincial')->nullable();
            $table->integer('matricula_nacional')->nullable();
            $table->date('fecha_documento');
            $table->string('diagnostico');
            $table->text('observaciones')->nullable();
            $table->string('archivo');
            $table->string('hash_archivo');
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
        Schema::dropIfExists('ausentismo_documentacion');
    }
}
