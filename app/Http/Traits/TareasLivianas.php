<?php

namespace App\Http\Traits;
use App\TareaLiviana;
use App\Cliente;
use Carbon\Carbon;
use Illuminate\Support\Str;

trait TareasLivianas {

	public function searchTareasLivianas($id_cliente=null)
	{


		$now = Carbon::now();


		$query = TareaLiviana::join('nominas','nominas.id','=','tareas_livianas.id_trabajador')
			/*whereHas('trabajador',function($query) use ($id_cliente){
				return $query->where('id_cliente',$id_cliente);
			})*/
			->join('tareas_livianas_tipos','tareas_livianas_tipos.id','=','tareas_livianas.id_tipo')
			->select(
				'tareas_livianas.*',

				'nominas.nombre as trabajador_nombre',
				'nominas.dni as trabajador_dni',
				'nominas.sector as trabajador_sector',

				'nominas.id_cliente',
				'tareas_livianas_tipos.nombre as tareas_livianas_tipos'
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
					->where('tareas_livianas.fecha_regreso_trabajar',null)
					->orWhere('tareas_livianas.fecha_regreso_trabajar','>',$now);
			});
			$query->where('tareas_livianas.fecha_inicio','<=',$now);
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


	public function exportTareasLivianas($idcliente=null)
	{

		if(!$idcliente) dd('Faltan parámetros');
		$cliente = Cliente::findOrFail($idcliente);

		$tareas_livianas = TareaLiviana::join('nominas','nominas.id','=','tareas_livianas.id_trabajador')
		->join('tareas_livianas_tipo','tareas_livianas_tipo.id','=','tareas_livianas.id_tipo')
		->where('nominas.id_cliente',$idcliente)
		->select(
			'tareas_livianas.*',

			'nominas.nombre as trabajador_nombre',
			'nominas.dni as trabajador_dni',
			'nominas.sector as trabajador_sector',

			'nominas.id_cliente',
			'tareas_livianas_tipo.nombre as tareas_livianas_tipo'
		)
		->orderBy('fecha_inicio','desc')
		->get();

		$now = Carbon::now();
		$file_name = 'tareas_livianas-'.Str::slug($cliente->nombre).'-'.$now->format('YmdHis').'.csv';


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


		foreach($tareas_livianas as $tarea_liviana){
			$hoy = '';
			if(is_null($tarea_liviana->fecha_regreso_trabajar)){
				$hoy = 'ausente';
			}else{
				if($tarea_liviana->fecha_regreso_trabajar > $now) $hoy = 'ausente';
			}
			fputcsv($fp,[
				$tarea_liviana->trabajador_nombre,
				$tarea_liviana->trabajador_dni,
				$tarea_liviana->trabajador_sector,
				$tarea_liviana->tarea_liviana_tipo,
				$tarea_liviana->fecha_inicio,
				$tarea_liviana->fecha_final??'[no cargada]',
				$tarea_liviana->fecha_regreso_trabajar??'[no cargada]',
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