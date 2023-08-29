<?php

use Illuminate\Database\Seeder;
use App\AusentismoTipo;


class AusentismoTipoColor extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $tipos = AusentismoTipo::get();
      foreach($tipos as $tipo){
      	$tipo->color = sprintf("#%02x%02x%02x", rand(0, 255), rand(0, 255), rand(0, 255));
      	$tipo->save();
      }

    }
}
