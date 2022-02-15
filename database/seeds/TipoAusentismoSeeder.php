<?php

use Illuminate\Database\Seeder;
use App\AusentismoTipo;

class TipoAusentismoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      AusentismoTipo::create([
        'nombre' => 'EI-Gastrointestinal',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);


      AusentismoTipo::create([
        'nombre' => 'EI-osteomuscular',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      AusentismoTipo::create([
        'nombre' => 'EI-respiratorio',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      AusentismoTipo::create([
        'nombre' => 'EI-cardiovascular',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      AusentismoTipo::create([
        'nombre' => 'EI-neurológico',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      AusentismoTipo::create([
        'nombre' => 'EI-genitourinario',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      AusentismoTipo::create([
        'nombre' => 'EI-psicopatológico',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      AusentismoTipo::create([
        'nombre' => 'caso sospechoso covid 19',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      AusentismoTipo::create([
        'nombre' => 'caso confirmado covid 19',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      AusentismoTipo::create([
        'nombre' => 'aislamiento preventivo contacto estrecho',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      AusentismoTipo::create([
        'nombre' => 'ART',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      AusentismoTipo::create([
        'nombre' => 'accidente in itinere',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      AusentismoTipo::create([
        'nombre' => 'enfermedad profesional',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);


    }


}
