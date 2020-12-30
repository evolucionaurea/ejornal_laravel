<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(v1_0_0TableSeeder::class);
        // $this->call(v1_0_1TableSeeder::class);
        // $this->call(v1_0_2TableSeeder::class);
    }
}
