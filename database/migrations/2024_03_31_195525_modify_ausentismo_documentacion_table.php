<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAusentismoDocumentacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ausentismo_documentacion', function (Blueprint $table) {
            $table->string('archivo')->nullable()->change();
            $table->string('hash_archivo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ausentismo_documentacion', function (Blueprint $table) {
            $table->string('archivo')->change();
            $table->string('hash_archivo')->change();
        });
    }
}
