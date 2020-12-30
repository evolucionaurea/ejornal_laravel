<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      User::create([
        'nombre' => 'Sebastian Admin',
        'id_rol' => 1,
        'estado' => 1,
        'email' => 'sebas_admin@ejornal.com.ar',
        'password' => bcrypt('123456'),
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);


      User::create([
        'nombre' => 'Sebastian Empleado',
        'id_rol' => 2,
        'estado' => 1,
        'email' => 'sebas_empleado@ejornal.com.ar',
        'password' => bcrypt('123456'),
        'personal_interno' => 1,
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);


      User::create([
        'nombre' => 'Juan Lopez',
        'id_rol' => 2,
        'estado' => 0,
        'email' => 'juan_empleado@ejornal.com.ar',
        'password' => bcrypt('123456'),
        'personal_interno' => 1,
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);


      User::create([
        'nombre' => 'Miguel Aris',
        'id_rol' => 3,
        'estado' => 1,
        'email' => 'coca@ejornal.com.ar',
        'password' => bcrypt('123456'),
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);


      User::create([
        'nombre' => 'Ricardo Moly',
        'id_rol' => 3,
        'estado' => 1,
        'email' => 'coto@ejornal.com.ar',
        'password' => bcrypt('123456'),
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);


    }
}
