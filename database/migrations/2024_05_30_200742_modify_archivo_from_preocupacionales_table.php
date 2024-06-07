<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyArchivoFromPreocupacionalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preocupacionales', function (Blueprint $table) {
            $table->string('archivo')->nullable()->default(null)->change();
            $table->string('hash_archivo')->nullable()->default(null)->change();
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
            $table->string('archivo');
            $table->text('hash_archivo');
        });
    }
}
