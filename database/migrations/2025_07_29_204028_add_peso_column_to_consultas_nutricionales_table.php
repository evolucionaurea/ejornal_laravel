<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPesoColumnToConsultasNutricionalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consultas_nutricionales', function (Blueprint $table) {
            $table->float('peso')->unsigned()->nullable()->after('user');
            $table->float('altura')->unsigned()->nullable()->after('peso');
            $table->float('imc')->unsigned()->nullable()->after('altura');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consultas_nutricionales', function (Blueprint $table) {
            $table->dropColumn('peso');
            $table->dropColumn('altura');
            $table->dropColumn('imc');
        });
    }
}
