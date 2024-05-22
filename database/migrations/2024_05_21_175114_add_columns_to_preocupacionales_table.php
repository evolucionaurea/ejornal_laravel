<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPreocupacionalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('preocupacionales', function (Blueprint $table) {
            $table->date('fecha_vencimiento')->after('hash_archivo')->nullable()->default(null);
            $table->unsignedTinyInteger('completado')->after('fecha_vencimiento')->default(0);
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
            $table->dropColumn('fecha_vencimiento');
            $table->dropColumn('completado');
        });
    }
}
