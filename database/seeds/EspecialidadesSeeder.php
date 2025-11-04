<?php

use Illuminate\Database\Seeder;
use App\Especialidad;

class EspecialidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      Especialidad::create([
        'nombre' => 'médico',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);


      Especialidad::create([
        'nombre' => 'enfermero',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);


    }
}
