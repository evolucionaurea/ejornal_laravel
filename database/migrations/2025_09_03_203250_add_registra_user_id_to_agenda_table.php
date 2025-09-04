<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRegistraUserIdToAgendaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agenda', function (Blueprint $table) {
            $table->unsignedBigInteger('registra_user_id')->nullable()->after('user_id');

            $table->foreign('registra_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agenda', function (Blueprint $table) {
            $table->dropForeign(['registra_user_id']);
            $table->dropColumn('registra_user_id');
        });
    }
}
