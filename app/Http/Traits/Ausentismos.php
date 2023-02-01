<?php

namespace App\Http\Traits;
use App\Ausentismo;
use App\Cliente;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

trait Ausentismos {

	public function searchAusentismos($id_cliente=null)
	{


		$now = CarbonImmutable::now();


		DB::enableQueryLog();

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
				'nominas.estado as trabajador_estado',

				'ausentismo_tipo.nombre as ausentismo_tipo'
			)
			->where('nominas.id_cliente',$id_cliente);

		$total = $query->count();

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
		if($this->request->ausentes=='mes'){
			$query->where('ausentismos.fecha_inicio','>=',$now->startOfMonth());
			$query->where('ausentismos.fecha_inicio','<=',$now->endOfMonth());
		}
		if($this->request->ausentes=='mes-pasado'){
			$query->where('ausentismos.fecha_inicio','>=',$now->subMonth()->startOfMonth());
			$query->where('ausentismos.fecha_inicio','<=',$now->subMonth()->endOfMonth());
		}



		if($this->request->estado){
			$query->where('nominas.estado','=',$this->request->estado=='activo' ? 1 : 0);
		}


		if($this->request->from) $query->whereDate('fecha_inicio','>=',Carbon::createFromFormat('d/m/Y', $this->request->from)->format('Y-m-d'));
		if($this->request->to) $query->whereDate('fecha_inicio','<=',Carbon::createFromFormat('d/m/Y', $this->request->to)->format('Y-m-d'));

		if($this->request->tipo) $query->where('id_tipo',$this->request->tipo);


		if($this->request->order){
			$sort = $this->request->columns[$this->request->order[0]['column']]['name'];
			$dir  = $this->request->order[0]['dir'];
			$query->orderBy($sort,$dir);
		}

		$total_filtered = $query->count();


		return [
			'draw'=>$this->request->draw,
			'recordsTotal'=>$total,
			'recordsFiltered'=>$total_filtered,
			'data'=>$query->skip($this->request->start)->take($this->request->length)->get(),
			'request'=>$this->request->all(),
			'queries'=>DB::getQueryLog()
		];

	}


	public function exportAusentismos($id_cliente=null)
	{

		if(!$id_cliente) dd('Faltan parámetros');
		$cliente = Cliente::findOrFail($id_cliente);

		$ausentismos = Ausentismo::join('nominas','nominas.id','=','ausentismos.id_trabajador')
		->join('ausentismo_tipo','ausentismo_tipo.id','=','ausentismos.id_tipo')
		->where('nominas.id_cliente',$id_cliente)
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

	public function ausentismosAjax($id_cliente=null)
	{

		$today = CarbonImmutable::now();

		//DB::enableQueryLog();
		$ausentismos_mes = Ausentismo::selectRaw('count(*) as total, id_tipo')
			->with(['tipo'=>function($query){
				$query->select('id','nombre');
			}])
			->where('fecha_inicio','>=',$today->startOfMonth())
			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('estado',1)
					->where('deleted_at',null)
					->where('id_cliente',$id_cliente);
			})
			->groupBy('id_tipo')
			->orderBy('total','desc')
			->get();
		//dd($ausentismos_mes->toArray());
		//$query = DB::getQueryLog();

		$ausentismos_mes_anterior = Ausentismo::selectRaw('count(*) as total, id_tipo')
			->with(['tipo'=>function($query){
				$query->select('id','nombre');
			}])
			->where('fecha_inicio','>=',$today->subMonth()->startOfMonth())
			->where(function($query) use ($today){
				$query
					->where('fecha_regreso_trabajar',null)
					->orwhere('fecha_regreso_trabajar','<=',$today->subMonth()->endOfMonth());
			})
			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('estado',1)
					->where('deleted_at',null)
					->where('id_cliente',$id_cliente);
			})
			->groupBy('id_tipo')
			->get();

		$ausentismos_mes_anio_anterior = Ausentismo::selectRaw('count(*) as total, id_tipo')
			->with(['tipo'=>function($query){
				$query->select('id','nombre');
			}])
			->where('fecha_inicio','>=',$today->subYear()->firstOfYear())
			->where(function($query) use ($today){
				$query
					->where('fecha_regreso_trabajar',null)
					->orwhere('fecha_regreso_trabajar','<=',$today->subYear()->lastOfYear());
			})
			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('estado',1)
					->where('deleted_at',null)
					->where('id_cliente',$id_cliente);
			})
			->groupBy('id_tipo')
			->get();

		$ausentismos_anual = Ausentismo::selectRaw('count(*) as total, id_tipo')
			->with(['tipo'=>function($query){
				$query->select('id','nombre');
			}])
			->whereIn('id_trabajador',function($query) use ($id_cliente){
				$query->select('id')
					->from('nominas')
					->where('estado',1)
					->where('deleted_at',null)
					->where('id_cliente',$id_cliente);
			})
			->groupBy('id_tipo')
			->where('fecha_inicio','>=',$today->firstOfYear())
			->orderBy('total','desc')
			->get();

		$status = 'ok';

		return compact(
			'ausentismos_mes',
			'ausentismos_mes_anterior',
			'ausentismos_mes_anio_anterior',
			'ausentismos_anual',
			'status'
		);
	}


}