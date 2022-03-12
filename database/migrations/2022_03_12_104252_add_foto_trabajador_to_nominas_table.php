<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFotoTrabajadorToNominasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nominas', function (Blueprint $table) {
          $table->string('foto')->after('dni')->nullable();
          $table->string('hash_foto')->after('estado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nominas', function (Blueprint $table) {
          $table->dropColumn('foto');
          $table->dropColumn('hash_foto');
        });
    }
}
