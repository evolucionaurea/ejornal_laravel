<?php

namespace App\Http\Traits;
use App\TareaLiviana;
use App\Cliente;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait TareasLivianas {

	public function searchTareasLivianas(Request $request)
	{

		$now = CarbonImmutable::now();

		$query = TareaLiviana::select(
				'tareas_livianas.*',

				'nominas.nombre as trabajador_nombre',
				'nominas.dni as trabajador_dni',
				'nominas.sector as trabajador_sector',

				'nominas.id_cliente',
				'tareas_livianas_tipos.nombre as tareas_livianas_tipos'
			)
			/*whereHas('trabajador',function($query) use ($id_cliente){
				return $query->where('id_cliente',$id_cliente);
			})*/
			->join('nominas','nominas.id','=','tareas_livianas.id_trabajador')
			->join('tareas_livianas_tipos','tareas_livianas_tipos.id','=','tareas_livianas.id_tipo')
			->with(['trabajador','tipo','cliente','comunicacion.tipo']);

		if($request->cliente_id) $query->where('tareas_livianas.id_cliente',$request->cliente_id);


		$query->where(function($query) use($request) {
			$filtro = '%'.$request->search.'%';
			$query->where('nominas.nombre','like',$filtro)
				->orWhere('nominas.email','like',$filtro)
				->orWhere('nominas.dni','like',$filtro)
				->orWhere('nominas.telefono','like',$filtro);
		});


		if($request->ausentes=='hoy'){
			$query->where(function($query) use ($now) {
				$query
					->where('tareas_livianas.fecha_final',null)
					->orWhere('tareas_livianas.fecha_final','>',$now);
			});
			$query->where('tareas_livianas.fecha_inicio','<=',$now);
		}


		if($request->from) $query->whereDate('fecha_inicio','>=',CarbonImmutable::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		if($request->to) $query->whereDate('fecha_final','<=',CarbonImmutable::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
		if($request->tipo) $query->where('id_tipo',$request->tipo);


		if($request->order){
			$sort = $request->columns[$request->order[0]['column']]['name'];
			$dir  = $request->order[0]['dir'];
			$query->orderBy($sort,$dir);
		}


		return [
			'draw'=>$request->draw,
			'recordsTotal'=>$query->count(),
			'recordsFiltered'=>$query->count(),
			'data'=>$query->skip($request->start)->take($request->length)->get(),
			'fichada_user'=>auth()->user()->fichada,
			'fichar_user'=>auth()->user()->fichar,
			'request'=>$request->all()
		];

	}


	public function exportTareasLivianas(Request $request)
	{

		if(!$request->idcliente) dd('Faltan parámetros');
		$cliente = Cliente::findOrFail($request->idcliente);



		/*$tareas_livianas = TareaLiviana::select(
				'tareas_livianas.*',

				'nominas.nombre as trabajador_nombre',
				'nominas.dni as trabajador_dni',
				'nominas.sector as trabajador_sector',

				'nominas.id_cliente',
			)
			->join('nominas','nominas.id','=','tareas_livianas.id_trabajador')
			//->join('tareas_livianas_tipo','tareas_livianas_tipo.id','=','tareas_livianas.id_tipo')
			->with(['tipo'=>function($query){
				$query->select('id','nombre');
			}])
			->where('nominas.id_cliente',$idcliente)
				//'tareas_livianas_tipo.nombre as tareas_livianas_tipo'
			->orderBy('fecha_inicio','desc')
			->get();*/

		$request->search = ['value'=>null];
		$request->start = 0;
		$request->length = 5000;
		$results = $this->searchTareasLivianas($request);
		$tareas_livianas = $results['data'];
		///dd($tareas_livianas);


		$now = CarbonImmutable::now();
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
			if(is_null($tarea_liviana->fecha_final)){
				$hoy = 'ausente';
			}else{
				if($tarea_liviana->fecha_final > $now) $hoy = 'ausente';
			}
			fputcsv($fp,[
				$tarea_liviana->trabajador_nombre,
				$tarea_liviana->trabajador_dni,
				$tarea_liviana->trabajador_sector,
				$tarea_liviana->tipo->nombre,
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