<?php

use Illuminate\Database\Seeder;
use App\TipoComunicacion;

class TipoComunicacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      TipoComunicacion::create([
        'nombre' => 'SMS',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);


      TipoComunicacion::create([
        'nombre' => 'Mail',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);

      TipoComunicacion::create([
        'nombre' => 'Llamado telefonico',
        'created_at' => date('Y-m-d H:m:s'),
        'updated_at' => date('Y-m-d H:m:s')
      ]);


    }
}
