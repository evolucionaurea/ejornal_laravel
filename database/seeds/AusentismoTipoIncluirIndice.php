<?php

use Illuminate\Database\Seeder;
use App\AusentismoTipo;

class AusentismoTipoIncluirIndice extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      AusentismoTipo::where('incluir_indice',null)->update(['incluir_indice'=>1]);
    }
}
