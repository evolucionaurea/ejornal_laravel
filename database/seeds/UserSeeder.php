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
        'id' => 100,
        'nombre' => 'Sebastian Admin',
        'id_rol' => 1,
        'estado' => 1,
        'email' => 'sebas_admin@ejornal.com.ar',
        'password' => bcrypt('123456'),
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);


      User::create([
        'id' => 101,
        'nombre' => 'Sebastian Empleado',
        'id_rol' => 2,
        'id_cliente_actual' => 1,
        'estado' => 1,
        'email' => 'sebas_empleado@ejornal.com.ar',
        'password' => bcrypt('123456'),
        'personal_interno' => 1,
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      // Medicos
      User::create([
        'id' => 102,
        'nombre' => 'MELISA SAMPIETRO',
        'id_rol' => 2,
        'id_cliente_actual' => 1,
        'id_especialidad' => 1,
        'estado' => 1,
        'email' => 'melisa_sampietro@carrefour.com',
        'password' => bcrypt('123456'),
        'personal_interno' => 1,
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      User::create([
        'id' => 103,
        'nombre' => 'RAUL CEPEDA',
        'id_rol' => 2,
        'id_cliente_actual' => 2,
        'id_especialidad' => 1,
        'estado' => 1,
        'email' => 'raul_miguel_cepeda@carrefour.com',
        'password' => bcrypt('123456'),
        'personal_interno' => 1,
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      User::create([
        'id' => 104,
        'nombre' => 'Noelia Skakavac',
        'id_rol' => 2,
        'id_cliente_actual' => 1,
        'id_especialidad' => 1,
        'estado' => 1,
        'email' => 'noeliaskakavac@jornalsalud.com',
        'password' => bcrypt('123456'),
        'personal_interno' => 1,
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      // Enfermeros
      User::create([
        'id' => 105,
        'nombre' => 'Gaston Suarez',
        'id_rol' => 2,
        'id_cliente_actual' => 1,
        'id_especialidad' => 2,
        'estado' => 1,
        'email' => 'gaston_alejandro_suarez@carrefour.com',
        'password' => bcrypt('123456'),
        'personal_interno' => 1,
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      User::create([
        'id' => 106,
        'nombre' => 'Sebastian Marquez',
        'id_rol' => 2,
        'id_cliente_actual' => 2,
        'id_especialidad' => 2,
        'estado' => 1,
        'email' => 'sebastian_n_marquez@carrefour.com',
        'password' => bcrypt('123456'),
        'personal_interno' => 1,
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      User::create([
        'id' => 107,
        'nombre' => 'Solange  Gonzalez',
        'id_rol' => 2,
        'id_cliente_actual' => 2,
        'id_especialidad' => 2,
        'estado' => 1,
        'email' => 'evelyn_solange_gonzalez@carrefour.com',
        'password' => bcrypt('123456'),
        'personal_interno' => 1,
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);


    }
}
