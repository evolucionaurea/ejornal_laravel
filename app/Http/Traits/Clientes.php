<?php

namespace App\Http\Traits;
use App\ClienteUser;
use App\Ausentismo;
use App\Nomina;
use App\NominaHistorial;
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
		->orderBy('clientes.nombre', 'ASC')
		->get();

	}

	public function getClientesIds()
	{
		return ClienteUser::select('*')
			->where('id_user', '=', auth()->user()->id)
			->whereHas('cliente',function($query){
				$query->whereNull('deleted_at');
			})
			->pluck('id_cliente');
	}

	public function resumen($id_cliente){


		$today = CarbonImmutable::now();

		$route = explode('/',Route::currentRouteName());
		$route = array_values(array_filter($route));

		if(!$id_cliente) return redirect($route[0])->with('error', 'Debes seleccionar un cliente primero!');

		///dd($route[0]);
		// NOMINAS
		$q_nomina = NominaHistorial::select('*')
			->where('year_month',$today->format('Ym'))
			->where('cliente_id',$id_cliente)
			->first();
		///RESOLVER SI NO ESTÁ GENERADO EL REGISTRO CON EL CRON
		//dd($q_nomina->cantidad);
		if(!$q_nomina){
			\Artisan::call('db:seed', [
				'--class' => 'NominaHistorialSeeder',
				'--force'=>true
			]);

			$q_nomina = NominaHistorial::select('*')
			->where('year_month',$today->format('Ym'))
			->where('cliente_id',$id_cliente)
			->first();
		}
		$nomina_actual = $q_nomina->cantidad;


		$nomina_mes_anterior = NominaHistorial::select('*')
			->where('year_month',$today->firstOfMonth()->subMonth()->format('Ym'))
			->where('cliente_id',$id_cliente)
			->first()
			->cantidad;
		$nomina_mes_anio_anterior = NominaHistorial::select('*')
			->where('year_month',$today->firstOfMonth()->subYear()->format('Ym'))
			->where('cliente_id',$id_cliente)
			->first()
			->cantidad;
		$nomina_promedio_actual = NominaHistorial::selectRaw("CEIL(AVG(cantidad)) as cantidad")
			->whereBetween('year_month',[$today->startOfYear()->format('Ym'),$today->format('Ym')])
			->where('cliente_id',$id_cliente)
			->first()
			->cantidad;



		/// AUSENTISMOS
		/// MES ACTUAL
		//DB::enableQueryLog();
		$inicio_mes = $today->startOfMonth()->format('Y-m-d');
		$today_formatted = $today->format('Y-m-d');
		///dump($inicio_mes,$today_formatted);
		$q_ausentismos_mes_actual = Ausentismo::selectRaw("
			SUM(
				ABS(DATEDIFF(

					IF(
						IFNULL(
							fecha_final,
							'{$today_formatted}'
						) >= '{$today_formatted}',
						'{$today_formatted}',
						fecha_final
					),

					IF(
						fecha_inicio>='{$inicio_mes}',
						fecha_inicio,
						'{$inicio_mes}'
					)
				))+1
			) dias"
		)
		->where(function($query) use ($today){
			$query
				->whereBetween('fecha_inicio',[$today->startOfMonth(),$today])
				->orWhere(function($query) use ($today){
					// los que siguen ausentes fuera del mes actual
					$query->where('fecha_inicio','<',$today->startOfMonth())
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
				->where('estado',1)
				->where('deleted_at',null);
		})
		->orderBy('dias','desc');

		//dd($q_ausentismos_mes_actual->toSql());
		$mes_actual = clone $q_ausentismos_mes_actual;
		$mes_actual->whereHas('tipo',function($query){
			$query
				->where('incluir_indice','=',1)
				->where('nombre','NOT LIKE','%incidente%')
				->where(function($query){
					$query
						->where('agrupamiento','!=','ART')
						->orWhere('agrupamiento',null);
				});
		});
		$dias_mes_actual = $mes_actual->first()->dias;
		$ausentismos_mes_actual_indice = $nomina_actual ? ($dias_mes_actual/($nomina_actual*$today->format('d'))*100) : 0;
		//$ausentismos_mes_actual = number_format($ausentismos_mes_actual,2,',','.');
		//dump($q_ausentismos_mes_actual->first()->dias);
		//dd(DB::getQueryLog());



		/// MES PASADO
		$inicio_mes_pasado = $today->startOfMonth()->subMonth()->format('Y-m-d');
		$fin_mes_pasado = $today->startOfMonth()->subMonth()->endOfMonth()->format('Y-m-d');

		$q_ausentismos_mes_pasado = Ausentismo::selectRaw("
			SUM(
				ABS(DATEDIFF(
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
				))+1
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
						$query->where('fecha_final','>=',$today->startOfMonth()->subMonth())
							->orWhere('fecha_final',null);
					});
			});
		})
		->whereIn('id_trabajador',function($query) use ($id_cliente){
			$query->select('id')
				->from('nominas')
				->where('id_cliente',$id_cliente)
				->where('estado',1)
				->where('deleted_at',null); ////consultar
		});
		$mes_pasado = clone $q_ausentismos_mes_pasado;
		$mes_pasado->whereHas('tipo',function($query){
			$query
				->where('incluir_indice','=',1)
				->where('nombre','NOT LIKE','%incidente%')
				->where(function($query){
					$query
						->where('agrupamiento','!=','ART')
						->orWhere('agrupamiento',null);
				});
		});
		$dias_mes_pasado = $mes_pasado->first()->dias;
		$ausentismos_mes_pasado_indice = $nomina_mes_anterior ? ($dias_mes_pasado/($nomina_mes_anterior*$today->startOfMonth()->subMonth()->endOfMonth()->format('d'))*100) : 0;
		//$ausentismos_mes_pasado = number_format($ausentismos_mes_pasado,2,',','.');



		/// MES AÑO ANTERIOR
		$inicio_mes_anio_anterior = $today->subYear()->startOfMonth()->format('Y-m-d');
		$fin_mes_anio_anterior = $today->subYear()->endOfMonth()->format('Y-m-d');
		$q_ausentismos_mes_anio_anterior = Ausentismo::selectRaw("
			SUM(
				ABS(DATEDIFF(
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
				))+1
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
							->where('fecha_final','>=',$today->startOfMonth()->subYear())
							->orWhere('fecha_final',null);
					});
			});
		})
		->whereIn('id_trabajador',function($query) use ($id_cliente){
			$query->select('id')
				->from('nominas')
				->where('id_cliente',$id_cliente)
				->where('estado',1)
				->where('deleted_at',null);
		});
		$mes_anio_anterior = clone $q_ausentismos_mes_anio_anterior;
		$mes_anio_anterior->whereHas('tipo',function($query){
			$query
				->where('incluir_indice','=',1)
				->where('nombre','NOT LIKE','%incidente%')
				->where(function($query){
					$query
						->where('agrupamiento','!=','ART')
						->orWhere('agrupamiento',null);
				});
		});
		$dias_mes_anio_anterior = $mes_anio_anterior->first()->dias;
		$ausentismos_mes_anio_anterior_indice = $nomina_mes_anio_anterior ? ($dias_mes_anio_anterior/($nomina_mes_anio_anterior*$today->startOfMonth()->subYear()->endOfMonth()->format('d'))*100) : 0;
		//$ausentismos_mes_anio_anterior = number_format($ausentismos_mes_anio_anterior,2,',','.');


		/// AÑO ACTUAL
		//DB::enableQueryLog();
		$inicio_anio = $today->format('Y-01-01');

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
				->where('estado',1)
				->where('deleted_at',null);
		})
		->orderBy('dias','desc');
		$anio_actual = clone $q_ausentismos_anio_actual;
		$anio_actual->whereHas('tipo',function($query){
			$query
				->where('incluir_indice','=',1)
				->where('nombre','NOT LIKE','%incidente%')
				->where(function($query){
					$query
						->where('agrupamiento','!=','ART')
						->orWhere('agrupamiento',null);
				});
		});
		$dias_anio_actual = $anio_actual->first()->dias;
		$ausentismos_anio_actual_indice = $nomina_promedio_actual ? ($dias_anio_actual/($nomina_promedio_actual*$today->dayOfYear())*100) : 0;
		//$ausentismos_anio_actual = number_format($ausentismos_anio_actual,2,',','.');


		/// ACCIDENTES
		/// MES ACTUAL
		///DB::enableQueryLog();
		$q_accidentes_mes_actual = clone $q_ausentismos_mes_actual;
		$q_accidentes_mes_actual->whereHas('tipo',function($query){
			$query
				->where('agrupamiento','=','ART')
				->where('nombre','NOT LIKE','%incidente%')
				->where('incluir_indice','=',1);
		});
		$dias_accidentes_mes_actual = (int) $q_accidentes_mes_actual->first()->dias;
		$accidentes_mes_actual_indice = $nomina_actual ? ($dias_accidentes_mes_actual/($nomina_actual*$today->format('d'))*100) : 0;
		//$accidentes_mes_actual_indice = number_format($accidentes_mes_actual_indice,2,',','.');
		//dd(DB::getQueryLog());

		/// MES PASADO
		$q_accidentes_mes_pasado = clone $q_ausentismos_mes_pasado;
		$q_accidentes_mes_pasado->whereHas('tipo',function($query){
			$query
				->where('agrupamiento','=','ART')
				->where('nombre','NOT LIKE','%incidente%')
				->where('incluir_indice','=',1);
		});
		$dias_accidentes_mes_pasado = (int) $q_accidentes_mes_pasado->first()->dias;
		$accidentes_mes_pasado_indice = $nomina_mes_anterior ? ($dias_accidentes_mes_pasado/($nomina_mes_anterior*$today->startOfMonth()->subMonth()->endOfMonth()->format('d'))*100) : 0;
		//$accidentes_mes_pasado_indice = number_format($accidentes_mes_pasado_indice,2,',','.');

		/// MES AÑO ANTERIOR
		$q_accidentes_mes_anio_anterior = clone $q_ausentismos_mes_anio_anterior;
		$q_accidentes_mes_anio_anterior->whereHas('tipo',function($query){
			$query
				->where('agrupamiento','=','ART')
				->where('nombre','NOT LIKE','%incidente%')
				->where('incluir_indice','=',1);
		});
		$dias_accidentes_mes_anio_anterior = (int) $q_accidentes_mes_anio_anterior->first()->dias;
		$accidentes_mes_anio_anterior_indice = $nomina_mes_anio_anterior ? ($dias_accidentes_mes_anio_anterior/($nomina_mes_anio_anterior*$today->startOfMonth()->subYear()->endOfMonth()->format('d'))*100) : 0;
		//$accidentes_mes_anio_anterior_indice = number_format($accidentes_mes_anio_anterior_indice,2,',','.');

		/// AÑO ACTUAL
		$q_accidentes_anio_actual = clone $q_ausentismos_anio_actual;
		$q_accidentes_anio_actual->whereHas('tipo',function($query){
			$query
				->where('agrupamiento','=','ART')
				->where('nombre','NOT LIKE','%incidente%')
				->where('incluir_indice','=',1);
		});
		$dias_accidentes_anio_actual = (int) $q_accidentes_anio_actual->first()->dias;
		$accidentes_anio_actual_indice = $nomina_promedio_actual ? ($dias_accidentes_anio_actual/($nomina_promedio_actual*$today->dayOfYear())*100) : 0;
		//$accidentes_anio_actual_indice = number_format($accidentes_anio_actual_indice,2,',','.');





		/// INCIDENTES
		/// MES ACTUAL
		$q_incidentes_mes_actual = $q_ausentismos_mes_actual;
		$q_incidentes_mes_actual->without('whereHas:tipo');
		$q_incidentes_mes_actual->whereHas('tipo',function($query){
			$query->where('nombre','LIKE','%incidente%')->where('incluir_indice','=',1);
		});
		$dias_incidentes_mes_actual = (int) $q_incidentes_mes_actual->first()->dias;
		$incidentes_mes_actual_indice = $nomina_actual ? ($dias_incidentes_mes_actual/($nomina_actual*$today->format('d'))*100) : 0;
		//$incidentes_mes_actual_indice = number_format($incidentes_mes_actual_indice,2,',','.');

		/// MES PASADO
		$q_incidentes_mes_pasado = clone $q_ausentismos_mes_pasado;
		$q_incidentes_mes_pasado->whereHas('tipo',function($query){
			$query->where('nombre','LIKE','%incidente%')->where('incluir_indice','=',1);
		});
		$dias_incidentes_mes_pasado = (int) $q_incidentes_mes_pasado->first()->dias;
		$incidentes_mes_pasado_indice = $nomina_mes_anterior ? ($dias_incidentes_mes_pasado/($nomina_mes_anterior*$today->startOfMonth()->subMonth()->endOfMonth()->format('d'))*100) : 0;
		//$incidentes_mes_pasado_indice = number_format($incidentes_mes_pasado_indice,2,',','.');


		/// MES AÑO ANTERIOR
		$q_incidentes_mes_anio_anterior = clone $q_ausentismos_mes_anio_anterior;
		$q_incidentes_mes_anio_anterior->whereHas('tipo',function($query){
			$query->where('nombre','LIKE','%incidente%')->where('incluir_indice','=',1);
		});
		$dias_incidentes_mes_anio_anterior = (int) $q_incidentes_mes_anio_anterior->first()->dias;
		$incidentes_mes_anio_anterior_indice = $nomina_mes_anio_anterior ? ($dias_incidentes_mes_anio_anterior/($nomina_mes_anio_anterior*$today->startOfMonth()->subYear()->endOfMonth()->format('d'))*100) : 0;
		//$incidentes_mes_anio_anterior_indice = number_format($incidentes_mes_anio_anterior_indice,2,',','.');

		/// AÑO ACTUAL
		$q_incidentes_anio_actual = clone $q_ausentismos_anio_actual;
		$q_incidentes_anio_actual->whereHas('tipo',function($query){
			$query->where('nombre','LIKE','%incidente%')->where('incluir_indice','=',1);
		});
		$dias_incidentes_anio_actual = (int) $q_incidentes_anio_actual->first()->dias;
		$incidentes_anio_actual_indice = $nomina_promedio_actual ? ($dias_incidentes_anio_actual/($nomina_promedio_actual*$today->dayOfYear())*100) : 0;
		//$incidentes_anio_actual_indice = number_format($incidentes_anio_actual_indice,2,',','.');




		/// TOP 10
		DB::enableQueryLog();
		/*$ausentismos_top_10 = Ausentismo::selectRaw("
			SUM(
				DATEDIFF(
					IFNULL(
						fecha_final,
						DATE(NOW())
					),
					fecha_inicio
				)
			)+1 total_dias,
			id_trabajador"
		)*/
		$ausentismos_top_10 = Ausentismo::selectRaw("
			SUM(
				ABS(DATEDIFF(
					IF(
						IFNULL(
							fecha_final,
							DATE(NOW())
						) >= DATE(NOW()),
						DATE(NOW()),
						fecha_final
					),
					fecha_inicio
				)) + 1
			) total_dias,

			id_trabajador
		")
			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',$id_cliente)
					->where('estado',1)
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
			->whereHas('tipo',function($query){
				$query
					->where('incluir_indice','=',1)
					->where('nombre','NOT LIKE','%incidente%')
					->where(function($query){
						$query
							->where('agrupamiento','!=','ART')
							->orWhere('agrupamiento',null);
					});
			})
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
					->where('estado',1)
					->where('deleted_at',null);
			})
			->with(['trabajador'=>function($query){
				$query->select('id','nombre');
			}])
			->whereHas('tipo',function($query){
				$query
					->where('incluir_indice','=',1)
					->where('nombre','NOT LIKE','%incidente%')
					->where(function($query){
						$query
							->where('agrupamiento','!=','ART')
							->orWhere('agrupamiento',null);
					});
			})
			->where('fecha_inicio','>=',$today->subYear())
			->groupBy('id_trabajador')
			->orderBy('total','desc')
			->limit(10)
			->get();


		//$nomina_mes_anio_anterior = !$nomina_mes_anio_anterior ? $nomina_actual : $nomina_mes_anio_anterior;

		///dd($today->endOfMonth()->subYear()->toDateString());



		return compact(
			'dias_mes_actual',
			'ausentismos_mes_actual_indice',
			'dias_mes_pasado',
			'ausentismos_mes_pasado_indice',
			'dias_mes_anio_anterior',
			'ausentismos_mes_anio_anterior_indice',
			'dias_anio_actual',
			'ausentismos_anio_actual_indice',

			'dias_accidentes_mes_actual',
			'accidentes_mes_actual_indice',
			'dias_accidentes_mes_pasado',
			'accidentes_mes_pasado_indice',
			'dias_accidentes_mes_anio_anterior',
			'accidentes_mes_anio_anterior_indice',
			'dias_accidentes_anio_actual',
			'accidentes_anio_actual_indice',

			'dias_incidentes_mes_actual',
			'incidentes_mes_actual_indice',
			'dias_incidentes_mes_pasado',
			'incidentes_mes_pasado_indice',
			'dias_incidentes_mes_anio_anterior',
			'incidentes_mes_anio_anterior_indice',
			'dias_incidentes_anio_actual',
			'incidentes_anio_actual_indice',

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