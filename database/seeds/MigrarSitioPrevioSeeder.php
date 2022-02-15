<?php

use Illuminate\Database\Seeder;
use App\MigrarSitioPrevio;

class MigrarSitioPrevioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      MigrarSitioPrevio::create([
        'clientes' => 0,
        'user_empleados' => 0,
        'nominas' => 0
      ]);

    }
}
