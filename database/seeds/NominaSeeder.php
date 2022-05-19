<?php

use Illuminate\Database\Seeder;
use App\Nomina;
use GuzzleHttp\Client;

class NominaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $url = 'https://randomuser.me/api/?results=500&nat=es';

      if(!$response = (new Client())->request('GET', $url)->getBody()) return false;
			$json = json_decode($response);
			$sectores = ['Comercial','Soporte Técnico','Ventas','Gerencia','Administración'];

			foreach($json->results as $person){

				Nomina::create([
					'id_cliente'=>1,
					'nombre'=>$person->name->first.' '.$person->name->last,
					'email'=>$person->email,
					'telefono'=>$person->phone,
					'dni'=>preg_replace('/[^0-9]/','',$person->id->value),
					'estado'=>rand(0,1),
					'sector'=>$sectores[rand(0,count($sectores)-1)]
				]);

			}

    }
}
