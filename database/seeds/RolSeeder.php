<?php

use Illuminate\Database\Seeder;
use App\Rol;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      Rol::create([
        'id'=>1,
        'nombre' => 'admin',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);


      Rol::create([
        'id'=>2,
        'nombre' => 'empleado',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);


      Rol::create([
        'id'=>3,
        'nombre' => 'cliente',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);


    }
}
