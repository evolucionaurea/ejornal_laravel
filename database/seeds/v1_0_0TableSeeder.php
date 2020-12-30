<?php

use Illuminate\Database\Seeder;

class v1_0_0TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      /* verifica si ya se ejecuto este seeder */
      $version = DB::table('db_version')->where('version', '1.0.0')->first();
      if ($version) {
          $this->command->info(self::class . ' :: Ya fue ejecutado el dia ' . $version->created_at . '. Se omite.');

          return;
      }

        $this->call(DbVersion1_0_0TableSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(RolSeeder::class);
        $this->call(ClienteSeeder::class);
        $this->call(ClienteUserSeeder::class);
    }
}
