<?php

use Illuminate\Database\Seeder;
use App\Nomina;
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

     $period = CarbonPeriod::create(CarbonImmutable::create(2021,7,1),'1 month', $today);
			// Iterate over the period
			$count_nomina = [];

			foreach ($period as $date) {
				$yearmonth = $date->format('Ym');
				$count_nomina[$yearmonth] = Nomina::
					where('id_cliente',$id_cliente)
					->withTrashed()
					->whereRaw("EXTRACT(YEAR_MONTH FROM created_at)<={$yearmonth}")
					->where(function($query) use($yearmonth){
						$query
							->where('deleted_at',null)
							->orWhereRaw("EXTRACT(YEAR_MONTH FROM deleted_at)>{$yearmonth}");
					})
					->count();
			}


			dd($count_nomina);

    }
}
