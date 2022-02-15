<?php

use Illuminate\Database\Seeder;
use App\CovidTesteoTipo;

class TipoCovidTesteosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      CovidTesteoTipo::create([
        'nombre' => 'Anticuerpos IgG + IgM/IgA',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);


      CovidTesteoTipo::create([
        'nombre' => 'Anticuerpos IgG',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      CovidTesteoTipo::create([
        'nombre' => 'Anticuerpos IgM/IgA',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      CovidTesteoTipo::create([
        'nombre' => ' PCR (Hisopado)',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      CovidTesteoTipo::create([
        'nombre' => 'Test rápidos IgM/IgG',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      CovidTesteoTipo::create([
        'nombre' => 'Test rápido de Antígenos',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      CovidTesteoTipo::create([
        'nombre' => 'PCR (Saliva)',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

    }


}
