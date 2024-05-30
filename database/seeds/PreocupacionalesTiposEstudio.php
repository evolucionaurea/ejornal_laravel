<?php

use Illuminate\Database\Seeder;
use App\PreocupacionalTipoEstudio;
use App\Preocupacional;

class PreocupacionalesTiposEstudio extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $tipo = PreocupacionalTipoEstudio::create([
        'name' => 'Complementarios',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);

      Preocupacional::query()->update(['tipo_estudio_id'=>$tipo->id]);
    }
}
