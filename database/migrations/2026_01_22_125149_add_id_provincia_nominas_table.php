<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdProvinciaNominasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nominas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_provincia')->nullable()->after('localidad');

            $table->foreign('id_provincia')->references('id')->on('provincias_recetas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nominas', function (Blueprint $table) {
            $table->dropForeign(['id_provincia']);
            $table->dropColumn('id_provincia');
        });
    }
}
