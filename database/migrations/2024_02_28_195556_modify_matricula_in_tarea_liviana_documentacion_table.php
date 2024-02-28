<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyMatriculaInTareaLivianaDocumentacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tarea_liviana_documentacion', function (Blueprint $table) {
            $table->string('matricula_provincial', 80)->change();
            $table->string('matricula_nacional', 80)->change();
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
            $table->string('matricula_provincial')->change();
            $table->string('matricula_nacional')->change();
        });
    }
}
