<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreocupacionalesArchivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preocupacionales_archivos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('preocupacional_id');
            $table->string('archivo');
            $table->string('hash_archivo');
            $table->timestamps();

            $table->foreign('preocupacional_id','fk_preocupacional_id')
                ->references('id')
                ->on('preocupacionales')
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
        Schema::dropIfExists('preocupacionales_archivos');
    }
}
