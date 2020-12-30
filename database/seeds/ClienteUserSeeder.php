<?php

use Illuminate\Database\Seeder;
use App\ClienteUser;

class ClienteUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      ClienteUser::create([
        'id_cliente' => 1,
        'id_user' => 2
      ]);

      ClienteUser::create([
        'id_cliente' => 1,
        'id_user' => 3
      ]);

      ClienteUser::create([
        'id_cliente' => 2,
        'id_user' => 2
      ]);

      ClienteUser::create([
        'id_cliente' => 1,
        'id_user' => 4
      ]);

      ClienteUser::create([
        'id_cliente' => 2,
        'id_user' => 5
      ]);


    }
}
