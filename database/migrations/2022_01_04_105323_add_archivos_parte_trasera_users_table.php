<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArchivosParteTraseraUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
          $table->string('archivo_dni_detras')->nullable();
          $table->string('hash_dni_detras')->nullable();
          $table->string('archivo_matricula_detras')->nullable();
          $table->string('hash_matricula_detras')->nullable();
          $table->string('archivo_titulo_detras')->nullable();
          $table->string('hash_titulo_detras')->nullable();
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
          $table->dropColumn('archivo_dni_detras');
          $table->dropColumn('hash_dni_detras');
          $table->dropColumn('archivo_matricula_detras');
          $table->dropColumn('hash_matricula_detras');
          $table->dropColumn('archivo_titulo_detras');
          $table->dropColumn('hash_titulo_detras');
        });
    }
}
