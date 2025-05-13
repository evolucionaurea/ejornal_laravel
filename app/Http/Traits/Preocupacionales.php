<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use App\Preocupacional;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

trait Preocupacionales {

	public function preocupacionalesAjax(Request $request){

		$now = CarbonImmutable::now();

		DB::enableQueryLog();

		$query = Preocupacional::with(['trabajador','tipo','cliente'])
			->select(
				'preocupacionales.*',
				'nominas.id_cliente as trabajador_cliente'
			)
			->join('nominas', 'preocupacionales.id_nomina', 'nominas.id')
			->join('preocupacionales_tipos_estudio', 'preocupacionales_tipos_estudio.id', 'preocupacionales.tipo_estudio_id')
			->with('archivos');

		if($request->cliente_id) $query->where('preocupacionales.id_cliente',$request->cliente_id);


		$total = $query->count();

		// FILTROS
		if($request->from){
			$query->whereDate('preocupacionales.fecha','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		}
		if($request->to){
			$query->whereDate('preocupacionales.fecha','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
		}

		if($request->tipo){
			$query->where('tipo_estudio_id','=',$request->tipo);
		}
		if(!is_null($request->vencimiento)){
			if($request->vencimiento==='1'){
				$query->whereNotNull('fecha_vencimiento');
			}
			if($request->vencimiento==='0'){
				$query->whereNull('fecha_vencimiento');
			}
		}
		if(!is_null($request->vencimiento_estado)){
			if($request->vencimiento_estado==='vencidos'){
				$query->whereDate('fecha_vencimiento','<',$now);
			}
			if($request->vencimiento_estado==='vencimiento_proximo'){
				$hasta_fecha = $now->addDays(30);
				$query->where('fecha_vencimiento','<=',$hasta_fecha);
				$query->where('fecha_vencimiento','>',$now);
			}
		}
		if(!is_null($request->completado)){
			$query->where('completado','=',$request->completado);
		}
		if($request->vencimiento_dias){
			$hasta_fecha = $now->addDays($request->vencimiento_dias);
			$query->where('fecha_vencimiento','<=',$hasta_fecha);
		}


		// BUSQUEDA
		if(isset($request->search)){
			$query->where(function($query) use($request) {
				$filtro = '%'.$request->search.'%';
				$query->where('nominas.nombre','like',$filtro)
					->orWhere('nominas.email','like',$filtro)
					->orWhere('nominas.dni','like',$filtro)
					->orWhere('nominas.telefono','like',$filtro)
					->orWhere('preocupacionales_tipos_estudio.name','like',$filtro);
			});
		}

		if($request->order){
			$sort = $request->columns[$request->order[0]['column']]['name'];
			$dir  = $request->order[0]['dir'];
			$query->orderBy($sort,$dir);
		}


		$total_filtered = $query->count();


		return [
			'draw'=>$request->draw,
			'recordsTotal'=>$total,
			'recordsFiltered'=>$total_filtered,
			'data'=>$query->skip($request->start)->take($request->length)->get(),
			'fichada_user'=>auth()->user()->fichada,
			'fichar_user'=>auth()->user()->fichar,
			'request'=>$request->all(),
			'query'=>DB::getQueryLog()
		];

	}



}