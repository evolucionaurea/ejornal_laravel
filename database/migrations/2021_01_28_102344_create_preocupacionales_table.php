<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreocupacionalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preocupacionales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_nomina');
            $table->date('fecha');
            $table->string('observaciones');
            $table->string('archivo');
            $table->text('hash_archivo');
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
        Schema::dropIfExists('preocupacionales');
    }
}
