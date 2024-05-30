<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToPreocupacionalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preocupacionales', function (Blueprint $table) {
            $table->string('resultado')->after('completado')->nullable()->default(null);
            $table->text('completado_comentarios')->after('resultado')->nullable()->default(null);
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
            $table->dropColumn('resultado');
            $table->dropColumn('completado_comentarios');
        });
    }
}
