<?php

use Illuminate\Database\Seeder;
use App\Nomina;
use App\NominaHistorial;
use App\Cliente;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;

class NominaHistorialSeeder extends Seeder
{
		/**
		 * Run the database seeds.
		 *
		 * @return void
		 */
		public function run()
		{

			$nomina_historial_creados = NominaHistorial::get();
			$clientes = Cliente::withTrashed()->get();
			$today = CarbonImmutable::now();
			$period = CarbonPeriod::create(CarbonImmutable::create(2021,7,1),'1 month', $today);

			foreach($clientes as $client){
				$count_nomina = [];
				foreach ($period as $date) {
					$yearmonth = $date->format('Ym');
					$cantidad = Nomina::
						where('id_cliente',$client->id)
						->withTrashed()
						->whereRaw("EXTRACT(YEAR_MONTH FROM created_at)<={$yearmonth}")
						->where(function($query) use($yearmonth){
							$query
								->where('deleted_at',null)
								->orWhereRaw("EXTRACT(YEAR_MONTH FROM deleted_at)>{$yearmonth}");
						})
						->where('estado',1)
						->count();

					$nomina_historial = new NominaHistorial;
					foreach($nomina_historial_creados as $nh){
						if($nh->year_month == $date->format('Ym') && $nh->cliente_id==$client->id) {
							$nomina_historial = NominaHistorial::find($nh->id);
							//$nomina_historial->id = $nh->id;
						}
					}

					$nomina_historial->year_month = $date->format('Ym');
					$nomina_historial->cliente_id = $client->id;
					$nomina_historial->cantidad = $cantidad;
					$nomina_historial->save();
				}

			}



		}
}
