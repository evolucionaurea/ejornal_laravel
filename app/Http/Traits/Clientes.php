<?php

namespace App\Http\Traits;
use App\ClienteUser;
use App\Ausentismo;
use App\Nomina;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

trait Clientes {

	public function getClientesUser(){

		return ClienteUser::join('clientes', 'cliente_user.id_cliente', 'clientes.id')
		->where('cliente_user.id_user', '=', auth()->user()->id)
		->where('clientes.deleted_at', '=', null)
		->select('clientes.nombre', 'clientes.id')
		->get();

	}

	public function resumen($id_cliente){


		$today = CarbonImmutable::now();


		$route = explode('/',Route::currentRouteName());
		$route = array_values(array_filter($route));

		///dd($route[0]);

		// NOMINAS
		$nomina_actual = Nomina::
			where('id_cliente',$id_cliente)
			//->where('estado',1)
			->count();
		$nomina_mes_anterior = Nomina::
			where('id_cliente',$id_cliente)
			->whereDate('created_at','<=',$today->firstOfMonth()->subMonth()->endOfMonth()->toDateString())
			//->where('estado',1)
			->count();
		$nomina_mes_anio_anterior = Nomina::
			where('id_cliente',$id_cliente)
			->where('created_at','<=',$today->firstOfMonth()->subYear()->endOfMonth()->toDateString())
			//->where('estado',1)
			->count();
		///nomina año actual: promedio de nominas mes a mes
		$period = CarbonPeriod::create($today->startOfYear(),'1 month', $today);
		// Iterate over the period
		$count_nomina = [];
		DB::enableQueryLog();
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

		///dd($count_nomina);


		$valores = collect($count_nomina);
		$nomina_promedio_actual = (int) ceil($valores->average());



		/*$nomina_anio_actual = Nomina::
			select(
				DB::raw('YEAR(created_at) as year'),
				DB::raw('MONTH(created_at) as month'),
				DB::raw('COUNT(*) as count'),
				DB::raw('(
						SELECT AVG(COUNT(*))
						FROM nominas u2
						WHERE YEAR(u2.created_at) = YEAR(nominas.created_at)
						AND MONTH(u2.created_at) <= MONTH(nominas.created_at)
				) as acumulated_average')
			)
			->where('id_cliente',$id_cliente)
			->groupBy('year','month')
			->orderBy('year','month')
			->get();*/
		//dd($nomina_anio_actual->toArray());


		/// AUSENTISMOS
		/// MES ACTUAL
		//DB::enableQueryLog();
		$inicio_mes = $today->startOfMonth()->format('Y-m-d');
		$today_formatted = $today->format('Y-m-d');
		$q_ausentismos_mes_actual = Ausentismo::selectRaw("
			SUM(
				DATEDIFF(

					IF(
						IFNULL(
							fecha_final,
							DATE(NOW())
						) >= DATE(NOW()),
						DATE(NOW()),
						fecha_final
					),

					IF(
						fecha_inicio>='{$inicio_mes}',
						fecha_inicio,
						'{$inicio_mes}'
					)
				)+1
			) dias"
		)
		->where(function($query) use ($today){
			$query->where(function($query) use ($today){
				$query
				->whereBetween('fecha_inicio',[$today->startOfMonth(),$today]);
				/*->where(function($query) use($today){
					$query->where('fecha_final','<=',$today)
						->orWhere('fecha_final',null);
				});*/
			})
			->orWhere(function($query) use ($today){
				// los que siguen ausentes fuera del mes actual
				$query->where('fecha_inicio','<=',$today->startOfMonth())
				->where(function($query) use($today){
					$query->where('fecha_final','>=',$today->startOfMonth())
						->orWhere('fecha_final',null);
				});
			});
		})

		->whereIn('id_trabajador',function($query) use ($id_cliente){
			$query->select('id')
				->from('nominas')
				->where('id_cliente',$id_cliente)
				//->where('estado',1)
				->where('deleted_at',null);
		})
		->orderBy('dias','desc');

		$ausentismos_mes_actual = $nomina_actual ? (round($q_ausentismos_mes_actual->first()->dias/($nomina_actual*$today->format('d')),4)*100) : 0;

		//dd(DB::getQueryLog()[0]);


		/// MES PASADO
		$inicio_mes_pasado = $today->subMonth()->startOfMonth()->format('Y-m-d');
		$fin_mes_pasado = $today->subMonth()->endOfMonth()->format('Y-m-d');
		$q_ausentismos_mes_pasado = Ausentismo::selectRaw("
			SUM(
				DATEDIFF(
					IF(
						fecha_final<'{$fin_mes_pasado}',
						fecha_final,
						'{$fin_mes_pasado}'
					),
					IF(
						fecha_inicio<'{$inicio_mes_pasado}',
						'{$inicio_mes_pasado}',
						fecha_inicio
					)
				)+1
			) dias"
		)
		->where(function($query) use ($today){
			$query->whereBetween('fecha_inicio',[$today->startOfMonth()->subMonth(),$today->startOfMonth()->subMonth()->endOfMonth()])
			/*$query->where(function($query) use ($today){
				$query
					->whereBetween('fecha_inicio',[$today->subMonth()->startOfMonth(),$today->subMonth()->endOfMonth()])
					->where(function($query) use ($today){
						$query->where('fecha_final','<=',$today->subMonth()->endOfMonth())
							->orWhere('fecha_final',null);
					});
			})*/
			// los que estuvieron ausentes durante el curso de ese mes pero iniciaron ausentismo antes de ese mes y volvieron dsp
			->orWhere(function($query) use ($today){
				$query->where('fecha_inicio','<',$today->startOfMonth()->subMonth())
					->where(function($query) use ($today){
						$query->where('fecha_final','>',$today->startOfMonth()->subMonth()->endOfMonth())
							->orWhere('fecha_final',null);
					});
			});
		})

		->whereIn('id_trabajador',function($query) use ($id_cliente){
			$query->select('id')
				->from('nominas')
				->where('id_cliente',$id_cliente)
				//->where('estado',1)
				->where('deleted_at',null); ////consultar
		});
		$ausentismos_mes_pasado = $nomina_mes_anterior ? (round($q_ausentismos_mes_pasado->first()->dias/($nomina_mes_anterior*$today->firstOfMonth()->subMonth()->endOfMonth()->format('d')),4)*100) : 0;



		/// MES AÑO ANTERIOR
		$inicio_mes_anio_anterior = $today->subYear()->startOfMonth()->format('Y-m-d');
		$fin_mes_anio_anterior = $today->subYear()->endOfMonth()->format('Y-m-d');
		$q_ausentismos_mes_anio_anterior = Ausentismo::selectRaw("
			SUM(
				DATEDIFF(
					IF(
						fecha_final<'{$fin_mes_anio_anterior}',
						fecha_final,
						'{$fin_mes_anio_anterior}'
					),
					IF(
						fecha_inicio<'{$inicio_mes_anio_anterior}',
						'{$inicio_mes_anio_anterior}',
						fecha_inicio
					)
				)+1
			) dias"
		)

		->where(function($query) use ($today){
			$query->where(function($query) use ($today){
				$query
					->whereBetween('fecha_inicio',[$today->startOfMonth()->subYear(),$today->startOfMonth()->subYear()->endOfMonth()])
					->where(function($query) use ($today){
						$query
							->where('fecha_final','<=',$today->startOfMonth()->subMonth()->endOfMonth())
							->orWhere('fecha_final',null);
					});
			})
			// los que estuvieron ausentes durante el curso de ese mes pero iniciaron ausentismo antes de ese mes y volvieron dsp
			->orWhere(function($query) use ($today){
				$query->where('fecha_inicio','<',$today->startOfMonth()->subYear())
					->where(function($query) use ($today){
						$query
							->where('fecha_final','>',$today->startOfMonth()->subYear()->endOfMonth())
							->orWhere('fecha_final',null);
					});
			});
		})

		->whereIn('id_trabajador',function($query) use ($id_cliente){
			$query->select('id')
				->from('nominas')
				->where('id_cliente',$id_cliente)
				//->where('estado',1)
				->where('deleted_at',null);
		});
		$ausentismos_mes_anio_anterior = $nomina_mes_anio_anterior ? (round($q_ausentismos_mes_anio_anterior->first()->dias/($nomina_mes_anio_anterior*$today->firstOfMonth()->subYear()->endOfMonth()->format('d')),4)*100) : 0;


		/// AÑO ACTUAL
		//DB::enableQueryLog();
		$inicio_anio = $today->format('Y-01-01');


		/*IF(
			fecha_final<DATE(NOW()),
			fecha_final,
			DATE(NOW())
		),*/
		$q_ausentismos_anio_actual = Ausentismo::selectRaw("
			SUM(
				ABS(DATEDIFF(

					IF(
						IFNULL(
							fecha_final,
							DATE(NOW())
						) < DATE(NOW()),
						fecha_final,
						DATE(NOW())
					),

					IF(
						fecha_inicio<'{$inicio_anio}',
						'{$inicio_anio}',
						fecha_inicio
					)
				))+1
			) dias"
		)

		->where(function($query) use ($today){

			$query->whereDate('fecha_inicio','>=',$today->startOfYear())
			/*$query->where(function($query) use ($today){
				$query
					->where(function($query) use ($today){
						$query
							->where('fecha_final','<=',$today)
							->orWhere('fecha_final',null);
					});
			})*/
			// los que estuvieron ausentes durante el curso de ese mes pero iniciaron ausentismo antes de ese mes y volvieron dsp
			->orWhere(function($query) use ($today){
				$query
					->where('fecha_inicio','<',$today->startOfYear())
					->where(function($query) use ($today){
						$query
							->where('fecha_final','>=',$today->startOfYear())
							->orWhere('fecha_final',null);
					});
			});
		})

		->whereIn('id_trabajador',function($query) use ($id_cliente){
			$query->select('id')
				->from('nominas')
				->where('id_cliente',$id_cliente)
				//->where('estado',1)
				->where('deleted_at',null);
		})
		->orderBy('dias','desc');

		//echo($q_ausentismos_anio_actual->toSql());
		//echo $q_ausentismos_anio_actual->first()->dias;

		//dd($today->dayOfYear());

		$ausentismos_anio_actual = $nomina_promedio_actual ? (round($q_ausentismos_anio_actual->first()->dias/($nomina_promedio_actual*$today->dayOfYear()),4)*100) : 0;


		/// ACCIDENTES
		/// MES ACTUAL
		//DB::enableQueryLog();
		//DB::enableQueryLog();
		$q_accidentes_mes_actual = clone $q_ausentismos_mes_actual;
		$q_accidentes_mes_actual->whereHas('tipo',function($query){
			$query->where('agrupamiento','=','ART');
		});
		$accidentes_mes_actual = $q_accidentes_mes_actual->first()->dias;
		//dd($accidentes_mes_actual);

		/// MES PASADO
		$q_accidentes_mes_pasado = clone $q_ausentismos_mes_pasado;
		$q_accidentes_mes_pasado->whereHas('tipo',function($query){
			$query->where('agrupamiento','=','ART');
		});
		$accidentes_mes_pasado = $q_accidentes_mes_pasado->first()->dias;

		/// MES AÑO ANTERIOR
		$q_accidentes_mes_anio_anterior = clone $q_ausentismos_mes_anio_anterior;
		$q_accidentes_mes_anio_anterior->whereHas('tipo',function($query){
			$query->where('agrupamiento','=','ART');
		});
		$accidentes_mes_anio_anterior = $q_accidentes_mes_anio_anterior->first()->dias;

		/// AÑO ACTUAL
		$q_accidentes_anio_actual = clone $q_ausentismos_anio_actual;
		$q_accidentes_anio_actual->whereHas('tipo',function($query){
			$query->where('agrupamiento','=','ART');
		});
		$accidentes_anio_actual = $q_accidentes_anio_actual->first()->dias;





		/// INCIDENTES
		/// MES ACTUAL
		$q_incidentes_mes_actual = clone $q_ausentismos_mes_actual;
		$q_incidentes_mes_actual->whereHas('tipo',function($query){
			$query->where('nombre','LIKE','%incidente%');
		});
		$incidentes_mes_actual = $q_incidentes_mes_actual->first()->dias;

		/// MES PASADO
		$q_incidentes_mes_pasado = clone $q_ausentismos_mes_pasado;
		$q_incidentes_mes_pasado->whereHas('tipo',function($query){
			$query->where('nombre','LIKE','%incidente%');
		});
		$incidentes_mes_pasado = $q_incidentes_mes_pasado->first()->dias;


		/// MES AÑO ANTERIOR
		$q_incidentes_mes_anio_anterior = clone $q_ausentismos_mes_anio_anterior;
		$q_incidentes_mes_anio_anterior->whereHas('tipo',function($query){
			$query->where('nombre','LIKE','%incidente%');
		});
		$incidentes_mes_anio_anterior = $q_incidentes_mes_anio_anterior->first()->dias;

		/// AÑO ACTUAL
		$q_incidentes_anio_actual = clone $q_ausentismos_anio_actual;
		$q_incidentes_anio_actual->whereHas('tipo',function($query){
			$query->where('nombre','LIKE','%incidente%');
		});
		$incidentes_anio_actual = $q_incidentes_anio_actual->first()->dias;




		/// TOP 10
		DB::enableQueryLog();
		$ausentismos_top_10 = Ausentismo::
			selectRaw('SUM(DATEDIFF( IFNULL(fecha_final,DATE(NOW())),fecha_inicio ))+1 total_dias, id_trabajador')
			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',$id_cliente)
					//->where('estado',1)
					->where('deleted_at',null);
			})
			->with(['trabajador'=>function($query){
				$query->selectRaw("id, nombre,
				(
					SELECT COUNT(a.id)
					FROM ausentismos a
					WHERE (a.fecha_final IS NULL OR a.fecha_final>DATE(NOW())) AND a.id_trabajador=nominas.id
				) as regreso_trabajo");
			}])
			->where('fecha_inicio','>=',$today->subYear())
			->groupBy('id_trabajador')
			->orderBy('total_dias','desc')
			->limit(10)
			->get();
		//dd(DB::getQueryLog());
		//dd($ausentismos_top_10->toArray());

		$ausentismos_top_10_solicitudes = Ausentismo::
			selectRaw('count(*) as total, id_trabajador')
			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',$id_cliente)
					//->where('estado',1)
					->where('deleted_at',null);
			})
			->with(['trabajador'=>function($query){
				$query->select('id','nombre');
			}])
			->where('fecha_inicio','>=',$today->subYear())
			->groupBy('id_trabajador')
			->orderBy('total','desc')
			->limit(10)
			->get();


		//$nomina_mes_anio_anterior = !$nomina_mes_anio_anterior ? $nomina_actual : $nomina_mes_anio_anterior;

		///dd($today->endOfMonth()->subYear()->toDateString());



		return compact(
			'ausentismos_mes_actual',
			'ausentismos_mes_pasado',
			'ausentismos_mes_anio_anterior',
			'ausentismos_anio_actual',

			'accidentes_mes_actual',
			'accidentes_mes_pasado',
			'accidentes_mes_anio_anterior',
			'accidentes_anio_actual',

			'incidentes_mes_actual',
			'incidentes_mes_pasado',
			'incidentes_mes_anio_anterior',
			'incidentes_anio_actual',

			'ausentismos_top_10',
			'ausentismos_top_10_solicitudes',

			'nomina_actual',
			'nomina_mes_anterior',
			'nomina_mes_anio_anterior',
			'nomina_promedio_actual',

			'route'
		);


	}

}