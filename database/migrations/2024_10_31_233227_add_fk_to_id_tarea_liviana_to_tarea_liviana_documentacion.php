<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkToIdTareaLivianaToTareaLivianaDocumentacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tarea_liviana_documentacion', function (Blueprint $table) {

            $table->unsignedBigInteger('id_tarea_liviana')->nullable()->default(null)->change();
            $table->foreign('id_tarea_liviana')->references('id')->on('tareas_livianas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tarea_liviana_documentacion', function (Blueprint $table) {
            $table->dropForeign(['id_tarea_liviana']);
        });
    }
}
