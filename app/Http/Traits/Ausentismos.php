<?php

namespace App\Http\Traits;
use App\Ausentismo;
use Carbon\Carbon;

trait Ausentismos {

	public function search($id_cliente=null)
	{


		$now = Carbon::now();


		$query = Ausentismo::join('nominas','nominas.id','=','ausentismos.id_trabajador')
			/*whereHas('trabajador',function($query) use ($id_cliente){
				return $query->where('id_cliente',$id_cliente);
			})*/
			->join('ausentismo_tipo','ausentismo_tipo.id','=','ausentismos.id_tipo')
			->select(
				'ausentismos.*',

				'nominas.nombre as trabajador_nombre',
				'nominas.dni as trabajador_dni',
				'nominas.sector as trabajador_sector',

				'nominas.id_cliente',
				'ausentismo_tipo.nombre as ausentismo_tipo'
			)
			->where('nominas.id_cliente',$id_cliente);

		$query->where(function($query) {
			$filtro = '%'.$this->request->search['value'].'%';
			$query->where('nominas.nombre','like',$filtro)
				->orWhere('nominas.email','like',$filtro)
				->orWhere('nominas.dni','like',$filtro)
				->orWhere('nominas.telefono','like',$filtro);
		});


		if($this->request->ausentes=='hoy'){
			$query->where(function($query) use ($now) {
				$query
					->where('ausentismos.fecha_regreso_trabajar',null)
					->orWhere('ausentismos.fecha_regreso_trabajar','>',$now);
			});
			$query->where('ausentismos.fecha_inicio','<=',$now);
		}


		if($this->request->from) $query->whereDate('fecha_inicio','>=',Carbon::createFromFormat('d/m/Y', $this->request->from)->format('Y-m-d'));
		if($this->request->to) $query->whereDate('fecha_inicio','<=',Carbon::createFromFormat('d/m/Y', $this->request->to)->format('Y-m-d'));
		if($this->request->tipo) $query->where('id_tipo',$this->request->tipo);


		if($this->request->order){
			$sort = $this->request->columns[$this->request->order[0]['column']]['name'];
			$dir  = $this->request->order[0]['dir'];
			$query->orderBy($sort,$dir);
		}


		return [
			'draw'=>$this->request->draw,
			'recordsTotal'=>$query->count(),
			'recordsFiltered'=>$query->count(),
			'data'=>$query->skip($this->request->start)->take($this->request->length)->get(),
			'request'=>$this->request->all()
		];

	}


}