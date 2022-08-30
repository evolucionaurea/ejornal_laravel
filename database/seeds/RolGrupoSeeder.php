<?php

use Illuminate\Database\Seeder;
use App\Rol;

class RolGrupoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


      Rol::create([
        'id'=>4,
        'nombre' => 'grupo',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);


    }
}
