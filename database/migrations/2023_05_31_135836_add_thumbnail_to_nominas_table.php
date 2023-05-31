<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThumbnailToNominasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nominas', function (Blueprint $table) {
            $table->string('thumbnail')->after('hash_foto')->nullable();
            $table->string('hash_thumbnail')->after('thumbnail')->nullable();
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
            $table->dropColumn('thumbnail');
            $table->dropColumn('hash_thumbnail');
        });
    }
}
