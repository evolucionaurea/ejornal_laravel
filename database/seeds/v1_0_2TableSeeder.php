<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class v1_0_2TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* verifica si ya se ejecuto este seeder */
        $version = DB::table('db_version')->where('version', '1.0.2')->first();
        if ($version) {
            $this->command->info(self::class . ' :: Ya fue ejecutado el dia ' . $version->created_at . '. Se omite.');

            return;
        }

        $this->call(DbVersion1_0_2TableSeeder::class);
        // $this->call(OtroSeeder::class);

    }
}
