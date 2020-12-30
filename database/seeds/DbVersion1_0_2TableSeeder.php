<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DbVersion1_0_2TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('db_version')->insert([
            'version' => '1.0.2',
        ]);
    }
}
