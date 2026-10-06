<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddFechaToComunicacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comunicaciones', function (Blueprint $table) {
            $table->date('fecha')->nullable()->after('descripcion');
        });

        // Las comunicaciones existentes toman como fecha el día en que fueron cargadas
        DB::statement('UPDATE comunicaciones SET fecha = DATE(created_at) WHERE fecha IS NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comunicaciones', function (Blueprint $table) {
            $table->dropColumn('fecha');
        });
    }
}
