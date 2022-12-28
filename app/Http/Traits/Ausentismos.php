<?php

namespace App\Http\Traits;
use App\Ausentismo;
use App\Cliente;
use Carbon\Carbon;
use Illuminate\Support\Str;

trait Ausentismos {

	public function searchAusentismos($id_cliente=null)
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
		if($this->request->to) $query->whereDate('fecha_final','<=',Carbon::createFromFormat('d/m/Y', $this->request->to)->format('Y-m-d'));
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


	public function exportAusentismos($idcliente=null)
	{

		if(!$idcliente) dd('Faltan parámetros');
		$cliente = Cliente::findOrFail($idcliente);

		$ausentismos = Ausentismo::join('nominas','nominas.id','=','ausentismos.id_trabajador')
		->join('ausentismo_tipo','ausentismo_tipo.id','=','ausentismos.id_tipo')
		->where('nominas.id_cliente',$idcliente)
		->select(
			'ausentismos.*',

			'nominas.nombre as trabajador_nombre',
			'nominas.dni as trabajador_dni',
			'nominas.sector as trabajador_sector',

			'nominas.id_cliente',
			'ausentismo_tipo.nombre as ausentismo_tipo'
		)
		->orderBy('fecha_inicio','desc')
		->get();

		$now = Carbon::now();
		$file_name = 'ausentismos-'.Str::slug($cliente->nombre).'-'.$now->format('YmdHis').'.csv';


		$fp = fopen('php://memory', 'w');
		fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
		fputcsv($fp,[
			'Trabajador',
			'DNI',
			'Sector',
			'Tipo',
			'Fecha Inicio',
			'Fecha Final',
			'Fecha en que Regresó',
			'Hoy ('.$now->format('d/m/Y').')'
		],';');


		foreach($ausentismos as $ausentismo){
			$hoy = '';
			if(is_null($ausentismo->fecha_regreso_trabajar)){
				$hoy = 'ausente';
			}else{
				if($ausentismo->fecha_regreso_trabajar > $now) $hoy = 'ausente';
			}
			fputcsv($fp,[
				$ausentismo->trabajador_nombre,
				$ausentismo->trabajador_dni,
				$ausentismo->trabajador_sector,
				$ausentismo->ausentismo_tipo,
				$ausentismo->fecha_inicio,
				$ausentismo->fecha_final??'[no cargada]',
				$ausentismo->fecha_regreso_trabajar??'[no cargada]',
				$hoy
			],';');
		}
		fseek($fp, 0);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="'.$file_name.'";');
		fpassthru($fp);


		return;



	}


}