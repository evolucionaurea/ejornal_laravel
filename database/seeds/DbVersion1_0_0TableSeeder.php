<?php

use Illuminate\Database\Seeder;

class DbVersion1_0_0TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('db_version')->insert([
            'version' => '1.0.0',
        ]);
    }
}
