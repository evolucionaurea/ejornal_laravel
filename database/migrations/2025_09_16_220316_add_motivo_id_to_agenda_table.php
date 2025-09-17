<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMotivoIdToAgendaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agenda', function (Blueprint $table) {
            $table->unsignedBigInteger('motivo_id')->nullable()->after('nomina_id');

            $table->foreign('motivo_id')->references('id')->on('agenda_motivos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agenda', function (Blueprint $table) {
            $table->dropForeign(['motivo_id']);
            $table->dropColumn('motivo_id');
        });
    }
}
