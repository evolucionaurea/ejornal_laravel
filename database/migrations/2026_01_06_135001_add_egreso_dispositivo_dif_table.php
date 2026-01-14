<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEgresoDispositivoDifTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fichadas_nuevas', function (Blueprint $table) {
            $table->json('egreso_dispositivo_dif')->after('dispositivo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fichadas_nuevas', function (Blueprint $table) {
            $table->dropColumn('egreso_dispositivo_dif');
        });
    }
}
