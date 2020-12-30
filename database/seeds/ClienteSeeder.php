<?php

use Illuminate\Database\Seeder;
use App\Cliente;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      Cliente::create([
        'nombre' => 'Coca Cola',
        'direccion' => 'Av Juan B justo 1234'
      ]);

      Cliente::create([
        'nombre' => 'Coto',
        'direccion' => 'Av del libertador 456'
      ]);

    }
}
