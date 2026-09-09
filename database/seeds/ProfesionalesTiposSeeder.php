<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;


class ProfesionalesTiposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('profesionales_tipos')->insert([
            'nombre' => 'Kinesiología',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('profesionales_tipos')->insert([
            'nombre' => 'Psicología',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
