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
		->select('clientes.nombre', 'clientes.id')
		->get();

	}

	public function resumen($id_cliente){


		$today = CarbonImmutable::now();


		/// AUSENTISMOS
		/// Mes actual
		DB::enableQueryLog();

		///traer también las personas que sigan ausentes desde antes del mes actual
		$inicio_mes = $today->startOfMonth()->format('Y-m-d');
		$ausentismos_mes_actual = Ausentismo::selectRaw("SUM( DATEDIFF(IFNULL(fecha_regreso_trabajar,DATE(NOW())), IF(fecha_inicio>='{$inicio_mes}',fecha_inicio,'{$inicio_mes}') )+1 ) dias")

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
			})
			->first();

		//dd($ausentismos_mes_actual->toArray());
		//dd(DB::getQueryLog());
		$ausentismos_mes_actual = $ausentismos_mes_actual->dias;


		/// Mes pasado
		$inicio_mes_pasado = $today->subMonth()->startOfMonth()->format('Y-m-d');
		$fin_mes_pasado = $today->subMonth()->endOfMonth()->format('Y-m-d');
		DB::enableQueryLog();

		///SUM(DATEDIFF('{$fin_mes_pasado}', IF(fecha_inicio<'{$inicio_mes_pasado}','{$inicio_mes_pasado}', fecha_inicio) )) dias
		$ausentismos_mes_pasado = Ausentismo::selectRaw("SUM(DATEDIFF( IF(fecha_regreso_trabajar<'{$fin_mes_pasado}', fecha_regreso_trabajar, '{$fin_mes_pasado}') , IF(fecha_inicio<'{$inicio_mes_pasado}','{$inicio_mes_pasado}', fecha_inicio) )+1) dias")

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
					->where('deleted_at',null);
			})
			->first();
		//dd($ausentismos_mes_pasado->toArray());
		$ausentismos_mes_pasado = $ausentismos_mes_pasado->dias;
		///dd(DB::getQueryLog());



		/// Mes año anterior
		$inicio_mes_anio_anterior = $today->subYear()->startOfMonth()->format('Y-m-d');
		$fin_mes_anio_anterior = $today->subYear()->endOfMonth()->format('Y-m-d');
		///id, fecha_inicio, fecha_regreso_trabajar,
		$ausentismos_mes_anio_anterior = Ausentismo::selectRaw("SUM(DATEDIFF( IF(fecha_regreso_trabajar<'{$fin_mes_anio_anterior}', fecha_regreso_trabajar, '{$fin_mes_anio_anterior}') , IF(fecha_inicio<'{$inicio_mes_anio_anterior}','{$inicio_mes_anio_anterior}', fecha_inicio) )+1) dias")
			->where(function($query) use ($today){

				$query->where(function($query) use ($today){
					$query
						->whereBetween('fecha_inicio',[$today->subYear()->startOfMonth(),$today->subYear()->endOfMonth()])
						->where(function($query) use ($today){
							$query->where('fecha_regreso_trabajar','<=',$today->subMonth()->endOfMonth())
								->orWhere('fecha_regreso_trabajar',null);
						});
				})


				// los que estuvieron ausentes durante el curso de ese mes pero iniciaron ausentismo antes de ese mes y volvieron dsp
				->orWhere(function($query) use ($today){
					$query->where('fecha_inicio','<',$today->subYear()->startOfMonth())
						->where(function($query) use ($today){
							$query->where('fecha_regreso_trabajar','>',$today->subYear()->endOfMonth())
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
			})
			->first();

		///dd( $ausentismos_mes_anio_anterior->toArray()  );
		$ausentismos_mes_anio_anterior = $ausentismos_mes_anio_anterior->dias;


		/// Mes año anterior
		$inicio_anio = $today->format('Y-01-01');
		///id, fecha_inicio, fecha_regreso_trabajar,
		$ausentismos_anio_actual = Ausentismo::selectRaw("SUM(DATEDIFF( IF(fecha_regreso_trabajar<DATE(NOW()), fecha_regreso_trabajar, DATE(NOW())) , IF(fecha_inicio<'{$inicio_anio}','{$inicio_anio}', fecha_inicio) )+1) dias")
			->where(function($query) use ($today){

				$query->where(function($query) use ($today){
					$query
						->whereBetween('fecha_inicio',[$today->startOfYear(),$today])
						->where(function($query) use ($today){
							$query->where('fecha_regreso_trabajar','<=',$today)
								->orWhere('fecha_regreso_trabajar',null);
						});
				})


				// los que estuvieron ausentes durante el curso de ese mes pero iniciaron ausentismo antes de ese mes y volvieron dsp
				->orWhere(function($query) use ($today){
					$query->where('fecha_inicio','<',$today->startOfYear())
						->where(function($query) use ($today){
							$query->where('fecha_regreso_trabajar','>',$today->startOfYear())
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
			})
			->first();

			///dd($ausentismos_anio_actual->toArray());
			$ausentismos_anio_actual = $ausentismos_anio_actual->dias;









		/// ACCIDENTES
		/// Mes actual
		$accidentes_mes_actual = Ausentismo::
			whereHas('tipo',function($query){
				$query
					->where('nombre','=','ART')
					->orWhere('nombre','LIKE','%accidente%');
			})


			->where(function($query) use ($today){

				$query->where('fecha_inicio','>=',$today->startOfMonth())
				->orWhere(function($query) use ($today){

					$query->where('fecha_inicio','<',$today->startOfMonth())
					->where('fecha_regreso_trabajar',null);

				});

			})

			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',$id_cliente)
					//->where('estado',1)
					->where('deleted_at',null);
			})
			->count();

		/// Mes pasado
		$accidentes_mes_pasado = Ausentismo::
			whereHas('tipo',function($query){
				$query
					->where('nombre','=','ART')
					->orWhere('nombre','LIKE','%accidente%');
			})
			->where('fecha_inicio','>=',$today->subMonth()->startOfMonth())
			->where('fecha_inicio','<=',$today->subMonth()->endOfMonth())
			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',$id_cliente)
					//->where('estado',1)
					->where('deleted_at',null);
			})
			->count();


		/// Mes año anterior
		$accidentes_mes_anio_anterior = Ausentismo::
			whereHas('tipo',function($query){
				$query
					->where('nombre','=','ART')
					->orWhere('nombre','LIKE','%accidente%');
			})
			->where('fecha_inicio','>=',$today->subYear()->startOfMonth())
			->where('fecha_inicio','<=',$today->subYear()->endOfMonth())
			->whereIn('id_trabajador',function($query) use ($id_cliente,$today){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',$id_cliente)
					->where('created_at','<=',$today->subYear()->endOfMonth()->toDateString())
					//->where('estado',1)
					->where('deleted_at',null);
			})
			->count();

		/// Mes año anterior
		$accidentes_anio_actual = Ausentismo::
			whereHas('tipo',function($query){
				$query
					->where('nombre','=','ART')
					->orWhere('nombre','LIKE','%accidente%');
			})
			->where('fecha_inicio','>=',$today->firstOfYear())
			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',$id_cliente)
					//->where('estado',1)
					->where('deleted_at',null);
			})
			->count();



		/// INCIDENTES
		/// Mes actual
		$incidentes_mes_actual = Ausentismo::
			whereHas('tipo',function($query){
				$query
					->where('nombre','LIKE','%incidente%');
			})

			->where(function($query) use ($today){

				$query->where('fecha_inicio','>=',$today->startOfMonth())
				->orWhere(function($query) use ($today){

					$query->where('fecha_inicio','<',$today->startOfMonth())
					->where('fecha_regreso_trabajar',null);

				});

			})

			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',$id_cliente)
					//->where('estado',1)
					->where('deleted_at',null);
			})
			->count();

		/// Mes pasado
		$incidentes_mes_pasado = Ausentismo::
			whereHas('tipo',function($query){
				$query
					->where('nombre','LIKE','%incidente%');
			})
			->where('fecha_inicio','>=',$today->subMonth()->startOfMonth())
			->where('fecha_inicio','<=',$today->subMonth()->endOfMonth())
			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',$id_cliente)
					//->where('estado',1)
					->where('deleted_at',null);
			})
			->count();


		/// Mes año anterior
		$incidentes_mes_anio_anterior = Ausentismo::
			whereHas('tipo',function($query){
				$query
					->where('nombre','LIKE','%incidente%');
			})
			->where('fecha_inicio','>=',$today->subYear()->startOfMonth())
			->where('fecha_inicio','<=',$today->subYear()->endOfMonth())
			->whereIn('id_trabajador',function($query) use ($id_cliente,$today){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',$id_cliente)
					->where('created_at','<=',$today->subYear()->endOfMonth()->toDateString())
					//->where('estado',1)
					->where('deleted_at',null);
			})
			->count();

		/// Mes año anterior
		$incidentes_anio_actual = Ausentismo::
			whereHas('tipo',function($query){
				$query
					->where('nombre','LIKE','%incidente%');
			})
			->where('fecha_inicio','>=',$today->firstOfYear())
			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',$id_cliente)
					//->where('estado',1)
					->where('deleted_at',null);
			})
			->count();



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



		$nomina_actual = Nomina::
			where('id_cliente',$id_cliente)
			//->where('estado',1)
			->count();

		$nomina_mes_anterior = Nomina::
			where('id_cliente',$id_cliente)
			->whereDate('created_at','<=',$today->endOfMonth()->subMonth()->toDateString())
			//->where('estado',1)
			->count();

		$nomina_mes_anio_anterior = Nomina::
			where('id_cliente',$id_cliente)
			->where('created_at','<=',$today->endOfMonth()->subYear()->toDateString())
			//->where('estado',1)
			->count();

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