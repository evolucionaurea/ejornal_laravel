<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToFichadasNuevasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fichadas_nuevas', function (Blueprint $table) {

            $table->string('browser')->after('sistema_operativo')->nullable()->default(null);
            $table->string('dispositivo')->after('browser')->nullable()->default(null);
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

            $table->dropColumn('dispositivo');
            $table->dropColumn('browser');

        });
    }
}
