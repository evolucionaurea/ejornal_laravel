<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoEstudioIdToPreocupacionalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preocupacionales', function (Blueprint $table) {
            $table->unsignedBigInteger('tipo_estudio_id')->after('id_nomina')->nullable()->default(null);

            $table->foreign('tipo_estudio_id','fk_preoc_tipo_estudio_id')
                ->references('id')
                ->on('preocupacionales_tipos_estudio')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('preocupacionales', function (Blueprint $table) {
            $table->dropForeign('fk_preoc_tipo_estudio_id');
            $table->dropColumn('tipo_estudio_id');
        });
    }
}
