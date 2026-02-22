<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSellosUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('sello_linea_1')->nullable()->after('sexo');
            $table->text('sello_linea_2')->nullable()->after('sello_linea_1');
            $table->text('sello_linea_3')->nullable()->after('sello_linea_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('sello_linea_1');
            $table->dropColumn('sello_linea_2');
            $table->dropColumn('sello_linea_3');
        });
    }
}
