<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdUserToConsultasMedicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consultas_medicas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_user')->after('user')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consultas_medicas', function (Blueprint $table) {
            $table->dropColumn('id_user');
        });
    }
}
