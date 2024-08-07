<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserToConsultasEnfermerias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consultas_enfermerias', function (Blueprint $table) {
            $table->string('user')->after('id_nomina')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consultas_enfermerias', function (Blueprint $table) {
            $table->dropColumn('user');
        });
    }
}
