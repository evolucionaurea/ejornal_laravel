<?php

namespace App\Http\Traits;
use App\ClienteUser;
use App\Ausentismo;
use App\Nomina;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

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




		/// AUSENTISMOS
		/// MES ACTUAL
		///DB::enableQueryLog();
		$inicio_mes = $today->startOfMonth()->format('Y-m-d');
		$q_ausentismos_mes_actual = Ausentismo::selectRaw("
			SUM(
				DATEDIFF(
					IFNULL(
						fecha_regreso_trabajar,
						DATE(NOW())
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
				->whereBetween('fecha_inicio',[$today->startOfMonth(),$today])
				->where(function($query) use($today){
					$query->where('fecha_regreso_trabajar','<=',$today->startOfMonth())
						->orWhere('fecha_regreso_trabajar',null);
				});
			})
			->orWhere(function($query) use ($today){
				// los que siguen ausentes fuera del mes actual
				$query->where('fecha_inicio','<',$today->startOfMonth())
				->where(function($query) use($today){
					$query->where('fecha_regreso_trabajar','>=',$today->startOfMonth())
						->orWhere('fecha_regreso_trabajar',null);
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

		$ausentismos_mes_actual = $nomina_actual ? (round($q_ausentismos_mes_actual->first()->dias/($nomina_actual*$today->format('d')),4)*100) : 0;

		///dd(DB::getQueryLog());


		/// MES PASADO
		$inicio_mes_pasado = $today->subMonth()->startOfMonth()->format('Y-m-d');
		$fin_mes_pasado = $today->subMonth()->endOfMonth()->format('Y-m-d');
		$q_ausentismos_mes_pasado = Ausentismo::selectRaw("
			SUM(
				DATEDIFF(
					IF(
						fecha_regreso_trabajar<'{$fin_mes_pasado}',
						fecha_regreso_trabajar,
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
			$query->where(function($query) use ($today){
				$query
					->whereBetween('fecha_inicio',[$today->subMonth()->startOfMonth(),$today->subMonth()->endOfMonth()])
					->where(function($query) use ($today){
						$query->where('fecha_regreso_trabajar','<=',$today->subMonth()->endOfMonth())
							->orWhere('fecha_regreso_trabajar',null);
					});
			})
			// los que estuvieron ausentes durante el curso de ese mes pero iniciaron ausentismo antes de ese mes y volvieron dsp
			->orWhere(function($query) use ($today){
				$query->where('fecha_inicio','<',$today->subMonth()->startOfMonth())
					->where(function($query) use ($today){
						$query->where('fecha_regreso_trabajar','>',$today->subMonth()->endOfMonth())
							->orWhere('fecha_regreso_trabajar',null);
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
						fecha_regreso_trabajar<'{$fin_mes_anio_anterior}',
						fecha_regreso_trabajar,
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
					->whereBetween('fecha_inicio',[$today->subYear()->startOfMonth(),$today->subYear()->endOfMonth()])
					->where(function($query) use ($today){
						$query
							->where('fecha_regreso_trabajar','<=',$today->subMonth()->endOfMonth())
							->orWhere('fecha_regreso_trabajar',null);
					});
			})
			// los que estuvieron ausentes durante el curso de ese mes pero iniciaron ausentismo antes de ese mes y volvieron dsp
			->orWhere(function($query) use ($today){
				$query->where('fecha_inicio','<',$today->subYear()->startOfMonth())
					->where(function($query) use ($today){
						$query
							->where('fecha_regreso_trabajar','>',$today->subYear()->endOfMonth())
							->orWhere('fecha_regreso_trabajar',null);
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
		$q_ausentismos_anio_actual = Ausentismo::selectRaw("
			SUM(
				DATEDIFF(
					IF(
						fecha_regreso_trabajar<DATE(NOW()),
						fecha_regreso_trabajar,
						DATE(NOW())
					),
					IF(
						fecha_inicio<'{$inicio_anio}',
						'{$inicio_anio}',
						fecha_inicio
					)
				)+1
			) dias"
		)

		->where(function($query) use ($today){
			$query->where(function($query) use ($today){
				$query
					->whereBetween('fecha_inicio',[$today->startOfYear(),$today])
					->where(function($query) use ($today){
						$query
							->where('fecha_regreso_trabajar','<=',$today)
							->orWhere('fecha_regreso_trabajar',null);
					});
			})
			// los que estuvieron ausentes durante el curso de ese mes pero iniciaron ausentismo antes de ese mes y volvieron dsp
			->orWhere(function($query) use ($today){
				$query
					->where('fecha_inicio','<',$today->startOfYear())
					->where(function($query) use ($today){
						$query
							->where('fecha_regreso_trabajar','>',$today->startOfYear())
							->orWhere('fecha_regreso_trabajar',null);
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
		//dd(DB::getQueryLog());
		$ausentismos_anio_actual = $nomina_actual ? (round($q_ausentismos_anio_actual->first()->dias/($nomina_actual*$today->dayOfYear()),4)*100) : 0;
		///dd($q_ausentismos_anio_actual->first()->dias);





		/// ACCIDENTES
		/// MES ACTUAL
		//DB::enableQueryLog();
		DB::enableQueryLog();
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
		//DB::enableQueryLog();
		$ausentismos_top_10 = Ausentismo::
			selectRaw('SUM(DATEDIFF( IFNULL(fecha_regreso_trabajar,DATE(NOW())),fecha_inicio )) total_dias, id_trabajador')
			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',$id_cliente)
					//->where('estado',1)
					->where('deleted_at',null);
			})
			->with(['trabajador'=>function($query){
				$query->selectRaw('id,nombre,(SELECT COUNT(a.id) FROM ausentismos a WHERE a.fecha_regreso_trabajar IS NULL AND a.id_trabajador=nominas.id) as regreso_trabajo');
			}])
			->where('fecha_inicio','>=',$today->subYear())
			->groupBy('id_trabajador')
			->orderBy('total_dias','desc')
			->limit(10)
			->get();
		//dd(DB::getQueryLog());
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
			'nomina_mes_anio_anterior'
		);


	}

}