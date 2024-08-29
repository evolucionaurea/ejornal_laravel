<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyMultipleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        //nominas
        DB::statement("
            UPDATE nominas n
            SET n.estado = '0'
            WHERE n.estado=''
        ");
        DB::statement("
            ALTER TABLE `nominas`
            CHANGE `estado` `estado` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1'"
        );
        Schema::table('nominas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_cliente')->nullable()->default(null)->change();
        });
        DB::statement("
            UPDATE nominas n
            LEFT JOIN clientes c ON c.id=n.id_cliente
            SET n.id_cliente = NULL
            WHERE c.id IS NULL
        ");
        Schema::table('nominas', function (Blueprint $table) {
            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('set null');
        });


        //ausentismos
        Schema::table('ausentismos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_trabajador')->nullable()->default(null)->change();
            $table->unsignedBigInteger('id_cliente')->after('id_trabajador')->nullable()->default(null);
        });
        DB::statement("
            UPDATE ausentismos a
            LEFT JOIN nominas n ON n.id=a.id_trabajador
            SET a.id_trabajador = NULL
            WHERE n.id IS NULL
        ");

        Schema::table('ausentismos', function (Blueprint $table) {

            $table->foreign('id_trabajador')->references('id')->on('nominas')->onDelete('set null');
            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('set null');
        });
        DB::statement("
            UPDATE ausentismos
            INNER JOIN nominas ON nominas.id = ausentismos.id_trabajador
            SET ausentismos.id_cliente = nominas.id_cliente
        ");


        //consultas_enfermeria
        Schema::table('consultas_enfermerias', function (Blueprint $table) {
            $table->unsignedBigInteger('id_nomina')->nullable()->default(null)->change();
            $table->unsignedBigInteger('id_cliente')->after('id_nomina')->nullable()->default(null);
        });
        DB::statement("
            UPDATE consultas_enfermerias a
            LEFT JOIN nominas n ON n.id=a.id_nomina
            SET a.id_nomina = NULL
            WHERE n.id IS NULL
        ");
        Schema::table('consultas_enfermerias', function (Blueprint $table) {
            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('set null');
        });
        DB::statement("
            UPDATE consultas_enfermerias
            INNER JOIN nominas ON nominas.id = consultas_enfermerias.id_nomina
            SET consultas_enfermerias.id_cliente = nominas.id_cliente
        ");


        //consultas_medicas
        Schema::table('consultas_medicas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_nomina')->nullable()->default(null)->change();
            $table->unsignedBigInteger('id_cliente')->after('id_nomina')->nullable()->default(null);
            $table->unsignedBigInteger('id_diagnostico_consulta')->nullable()->default(null)->change();
        });
        DB::statement("
            UPDATE consultas_medicas a
            LEFT JOIN nominas n ON n.id=a.id_nomina
            SET a.id_nomina = NULL
            WHERE n.id IS NULL
        ");
        Schema::table('consultas_medicas', function (Blueprint $table) {
            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('set null');
            $table->foreign('id_diagnostico_consulta')->references('id')->on('diagnostico_consulta')->onDelete('set null');
        });
        DB::statement("
            UPDATE consultas_medicas
            INNER JOIN nominas ON nominas.id = consultas_medicas.id_nomina
            SET consultas_medicas.id_cliente = nominas.id_cliente
        ");


        //covid_testeos
        Schema::table('covid_testeos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_nomina')->nullable()->default(null)->change();
            $table->unsignedBigInteger('id_cliente')->after('id_nomina')->nullable()->default(null);
        });
        DB::statement("
            UPDATE covid_testeos a
            LEFT JOIN nominas n ON n.id=a.id_nomina
            SET a.id_nomina = NULL
            WHERE n.id IS NULL
        ");
        Schema::table('covid_testeos', function (Blueprint $table) {
            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('set null');
        });
        DB::statement("
            UPDATE covid_testeos
            INNER JOIN nominas ON nominas.id = covid_testeos.id_nomina
            SET covid_testeos.id_cliente = nominas.id_cliente
        ");


        //covid_vacunas
        Schema::table('covid_vacunas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_nomina')->nullable()->default(null)->change();
            $table->unsignedBigInteger('id_cliente')->after('id_nomina')->nullable()->default(null);
            $table->unsignedBigInteger('id_tipo')->nullable()->default(null)->change();
        });
        DB::statement("
            UPDATE covid_vacunas a
            LEFT JOIN nominas n ON n.id=a.id_nomina
            SET a.id_nomina = NULL
            WHERE n.id IS NULL
        ");
        Schema::table('covid_vacunas', function (Blueprint $table) {
            $table->foreign('id_nomina')->references('id')->on('nominas')->onDelete('set null');
            $table->foreign('id_tipo')->references('id')->on('covid_vacunas_tipo')->onDelete('set null');
            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('set null');
        });
        DB::statement("
            UPDATE covid_vacunas
            INNER JOIN nominas ON nominas.id = covid_vacunas.id_nomina
            SET covid_vacunas.id_cliente = nominas.id_cliente
        ");


        //preocupacionales
        Schema::table('preocupacionales', function (Blueprint $table) {
            $table->unsignedBigInteger('id_nomina')->nullable()->default(null)->change();
            $table->unsignedBigInteger('id_cliente')->after('id_nomina')->nullable()->default(null);
        });
        DB::statement("
            UPDATE preocupacionales a
            LEFT JOIN nominas n ON n.id=a.id_nomina
            SET a.id_nomina = NULL
            WHERE n.id IS NULL
        ");
        Schema::table('preocupacionales', function (Blueprint $table) {
            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('set null');
        });
        DB::statement("
            UPDATE preocupacionales
            INNER JOIN nominas ON nominas.id = preocupacionales.id_nomina
            SET preocupacionales.id_cliente = nominas.id_cliente
        ");


        //tareas_livianas
        Schema::table('tareas_livianas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_trabajador')->nullable()->default(null)->change();
            $table->unsignedBigInteger('id_cliente')->after('id_trabajador')->nullable()->default(null);
            $table->unsignedBigInteger('id_tipo')->nullable()->default(null)->change();
        });
        DB::statement("
            UPDATE tareas_livianas a
            LEFT JOIN nominas n ON n.id=a.id_trabajador
            SET a.id_trabajador = NULL
            WHERE n.id IS NULL
        ");
        Schema::table('tareas_livianas', function (Blueprint $table) {
            $table->foreign('id_trabajador')->references('id')->on('nominas')->onDelete('set null');
            $table->foreign('id_tipo')->references('id')->on('tareas_livianas_tipos')->onDelete('set null');
            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('set null');
        });
        DB::statement("
            UPDATE tareas_livianas
            INNER JOIN nominas ON nominas.id = tareas_livianas.id_trabajador
            SET tareas_livianas.id_cliente = nominas.id_cliente
        ");


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nominas', function (Blueprint $table) {
            $table->dropForeign(['id_cliente']);
        });
        Schema::table('ausentismos', function (Blueprint $table) {
            $table->dropForeign(['id_cliente']);
            $table->dropForeign(['id_trabajador']);
            $table->dropColumn('id_cliente');
        });
        Schema::table('consultas_enfermerias', function (Blueprint $table) {
            $table->dropForeign(['id_cliente']);
            $table->dropColumn('id_cliente');
        });
        Schema::table('consultas_medicas', function (Blueprint $table) {
            $table->dropForeign(['id_nomina']);
            $table->dropForeign(['id_diagnostico_consulta']);
            $table->dropForeign(['id_cliente']);
            $table->dropColumn('id_cliente');
        });
        Schema::table('covid_testeos', function (Blueprint $table) {
            $table->dropForeign(['id_cliente']);
            $table->dropColumn('id_cliente');
        });
        Schema::table('covid_vacunas', function (Blueprint $table) {
            $table->dropForeign(['id_nomina']);
            $table->dropForeign(['id_tipo']);
            $table->dropForeign(['id_cliente']);
            $table->dropColumn('id_cliente');
        });
        Schema::table('preocupacionales', function (Blueprint $table) {
            $table->dropForeign(['id_cliente']);
            $table->dropColumn('id_cliente');
        });
        Schema::table('tareas_livianas', function (Blueprint $table) {
            $table->dropForeign(['id_trabajador']);
            $table->dropForeign(['id_tipo']);
            $table->dropForeign(['id_cliente']);
            $table->dropColumn('id_cliente');
        });
    }
}
