<?php

use Illuminate\Database\Seeder;
use App\CovidVacunaTipo;

class TipoCovidVacunasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      CovidVacunaTipo::create([
        'nombre' => 'Sputnik V- dosis 1',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      CovidVacunaTipo::create([
        'nombre' => 'Sputnik V- dosis 2',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      CovidVacunaTipo::create([
        'nombre' => 'Covidshield - dosis 1',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      CovidVacunaTipo::create([
        'nombre' => 'Covidshield - dosis 2',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      CovidVacunaTipo::create([
        'nombre' => 'Sinopharm - Dosis 1',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      CovidVacunaTipo::create([
        'nombre' => 'Sinopharm - Dosis 2',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      CovidVacunaTipo::create([
        'nombre' => 'Aztrazeneca dosis 1',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      CovidVacunaTipo::create([
        'nombre' => 'Aztrazeneca dosis 2',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);


    }


}
