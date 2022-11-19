<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVariosCamposNominasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nominas', function (Blueprint $table) {
          $table->string('calle')->nullable();
          $table->string('nro')->nullable();
          $table->string('entre_calles')->nullable();
          $table->string('localidad')->nullable();
          $table->string('partido')->nullable();
          $table->string('cod_postal')->nullable();
          $table->text('observaciones')->nullable();
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
          $table->dropColumn('calle');
          $table->dropColumn('nro');
          $table->dropColumn('entre_calles');
          $table->dropColumn('localidad');
          $table->dropColumn('partido');
          $table->dropColumn('cod_postal');
          $table->dropColumn('observaciones');
        });
    }
}
