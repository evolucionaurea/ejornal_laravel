<?php

use App\PreocupacionalTipoEstudio;
use Illuminate\Database\Seeder;

class TipoPreocupacionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PreocupacionalTipoEstudio::create([
            'name' => 'Preocupacional Básico Ley',
            'created_at' => date('Y-m-d H:m:s'),
            'updated_at' => date('Y-m-d H:m:s')
          ]);

        PreocupacionalTipoEstudio::create([
          'name' => 'Examen Periódico',
          'created_at' => date('Y-m-d H:m:s'),
          'updated_at' => date('Y-m-d H:m:s')
        ]);

        PreocupacionalTipoEstudio::create([
          'name' => 'Libreta Sanitaria',
          'created_at' => date('Y-m-d H:m:s'),
          'updated_at' => date('Y-m-d H:m:s')
        ]);

        PreocupacionalTipoEstudio::create([
          'name' => 'Especial (altura - espacios confinados - chofer/conductor autoelevador)',
          'created_at' => date('Y-m-d H:m:s'),
          'updated_at' => date('Y-m-d H:m:s')
        ]);

        PreocupacionalTipoEstudio::create([
          'name' => 'Estudio Complementario',
          'created_at' => date('Y-m-d H:m:s'),
          'updated_at' => date('Y-m-d H:m:s')
        ]);

        PreocupacionalTipoEstudio::create([
          'name' => 'Interconsulta',
          'created_at' => date('Y-m-d H:m:s'),
          'updated_at' => date('Y-m-d H:m:s')
        ]);
    }
}
