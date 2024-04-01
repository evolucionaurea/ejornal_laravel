<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAusentismoDocumentacionArchivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ausentismo_documentacion_archivos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ausentismo_documentacion_id');
            $table->string('archivo');
            $table->string('hash_archivo');
            $table->timestamps();

            $table->foreign('ausentismo_documentacion_id','fk_aus_doc_arch_aus_doc_id')
                ->references('id')
                ->on('ausentismo_documentacion')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ausentismo_documentacion_archivos');
    }
}
