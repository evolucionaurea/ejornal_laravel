<?php

namespace App\Http\Traits;
use App\ClienteUser;
use App\Ausentismo;
use Carbon\CarbonImmutable;

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
		$ausentismos_mes_actual = Ausentismo::
			where('fecha_inicio','>=',$today->startOfMonth())
			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',$id_cliente)
					->where('deleted_at',null);
			})
			->count();


		/// Mes pasado
		$ausentismos_mes_pasado = Ausentismo::
			where('fecha_inicio','>=',$today->startOfMonth()->subMonth())
			->where('fecha_inicio','<=',$today->endOfMonth()->subMonth())
			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',$id_cliente)
					->where('deleted_at',null);
			})
			->count();

		/// Mes año anterior
		$ausentismos_mes_anio_anterior = Ausentismo::
			where('fecha_inicio','>=',$today->subYear()->startOfMonth())
			->where('fecha_inicio','<=',$today->subYear()->endOfMonth())
			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',$id_cliente)
					->where('deleted_at',null);
			})
			->count();

		/// Año actual
		$ausentismos_anio_actual = Ausentismo::
			where('fecha_inicio','>=',$today->firstOfYear())
			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',$id_cliente)
					->where('deleted_at',null);
			})
			->count();


		/// ACCIDENTES
		/// Mes actual
		$accidentes_mes_actual = Ausentismo::
			whereHas('tipo',function($query){
				$query
					->where('nombre','=','ART')
					->orWhere('nombre','LIKE','%accidente%');
			})
			->where('fecha_inicio','>=',$today->startOfMonth())
			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',$id_cliente)
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
			->where('fecha_inicio','>=',$today->startOfMonth()->subMonth())
			->where('fecha_inicio','<=',$today->endOfMonth()->subMonth())
			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',$id_cliente)
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
			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',$id_cliente)
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
			->where('fecha_inicio','>=',$today->startOfMonth())
			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',$id_cliente)
					->where('deleted_at',null);
			})
			->count();

		/// Mes pasado
		$incidentes_mes_pasado = Ausentismo::
			whereHas('tipo',function($query){
				$query
					->where('nombre','LIKE','%incidente%');
			})
			->where('fecha_inicio','>=',$today->startOfMonth()->subMonth())
			->where('fecha_inicio','<=',$today->endOfMonth()->subMonth())
			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',$id_cliente)
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
			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',$id_cliente)
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
					->where('deleted_at',null);
			})
			->count();



		/// TOP 10
		$ausentismos_top_10 = Ausentismo::
			selectRaw('SUM(DATEDIFF( IFNULL(fecha_regreso_trabajar,DATE(NOW())),fecha_inicio )) total_dias, id_trabajador')
			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',$id_cliente)
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




		$ausentismos_top_10_solicitudes = Ausentismo::
			selectRaw('count(*) as total, id_trabajador')
			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',$id_cliente)
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
			'ausentismos_top_10_solicitudes'
		);


	}

}