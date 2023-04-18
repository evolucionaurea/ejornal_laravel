<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use App\Ausentismo;
use App\Cliente;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

trait Ausentismos {

	public function searchAusentismos($id_cliente=null,Request $request)
	{

		//dd($request->toArray());

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

		$query->where(function($query) use($request) {
			$filtro = '%'.$request->search['value'].'%';
			$query->where('nominas.nombre','like',$filtro)
				->orWhere('nominas.email','like',$filtro)
				->orWhere('nominas.dni','like',$filtro)
				->orWhere('nominas.telefono','like',$filtro);
		});


		if($request->ausentes=='hoy'){
			$query->where(function($query) use ($now) {
				$query
					->where('ausentismos.fecha_regreso_trabajar',null)
					->orWhere('ausentismos.fecha_regreso_trabajar','>',$now);
			});
			$query->where('ausentismos.fecha_inicio','<=',$now);
		}


		if($request->ausentes=='mes'){
			/*$query->where(function($query) use ($now) {
				$query
					->where('ausentismos.fecha_regreso_trabajar',null)
					->orWhere('ausentismos.fecha_regreso_trabajar','>',$now->startOfMonth());
			});*/
			$query->where('ausentismos.fecha_inicio','>=',$now->startOfMonth());
			$query->where('ausentismos.fecha_inicio','<=',$now->endOfMonth());
		}
		if($request->ausentes=='mes-pasado'){
			$query->where('ausentismos.fecha_inicio','>=',$now->subMonth()->startOfMonth());
			$query->where('ausentismos.fecha_inicio','<=',$now->subMonth()->endOfMonth());
		}



		if($request->estado){
			$query->where('nominas.estado','=',$request->estado=='activo' ? 1 : 0);
		}


		if($request->from) {
			$from = Carbon::createFromFormat('d/m/Y',$request->from);
			$query->whereRaw("'{$from->toDateString()}' BETWEEN fecha_inicio AND IFNULL(fecha_regreso_trabajar,NOW())");
			//$query->where(function($query) use ($request){
				//->where('fecha_inicio','>=',Carbon::createFromFormat('d/m/Y', $from))
				// los que siguen ausentes fuera rango actual
				/*->orWhere(function($query) use ($request) {
						$query->where('fecha_regreso_trabajar','>=',Carbon::createFromFormat('d/m/Y', $request->from))
							->orWhere('fecha_regreso_trabajar',null);
				});*/
			//});
		}
		if($request->to) {
			$from = $request->from ? Carbon::createFromFormat('d/m/Y',$request->from) : $now;
			$to = Carbon::createFromFormat('d/m/Y',$request->to);
			$query->whereRaw("IFNULL(fecha_regreso_trabajar,DATE(NOW())) BETWEEN '{$from->toDateString()}' AND '{$to->toDateString()}'");
			/*$query->where(function($query) use ($request){
				$query->where('fecha_regreso_trabajar','<=',$to)
					->orWhere('fecha_regreso_trabajar',null);
			});*/
		}

		if($request->tipo) $query->where('id_tipo',$request->tipo);


		if($request->order){
			$sort = $request->columns[$request->order[0]['column']]['name'];
			$dir  = $request->order[0]['dir'];
			$query->orderBy($sort,$dir);
		}

		$query->where('nominas.deleted_at',null);

		$total_filtered = $query->count();


		return [
			'draw'=>$request->draw,
			'recordsTotal'=>$total,
			'recordsFiltered'=>$total_filtered,
			'data'=>$query->skip($request->start)->take($request->length)->get(),
			'request'=>$request->all(),
			'queries'=>DB::getQueryLog()
		];

	}
	public function exportAusentismos($id_cliente=null,Request $request)
	{

		if(!$id_cliente) dd('Faltan parámetros');
		$cliente = Cliente::findOrFail($id_cliente);

		$request->search = ['value'=>null];
		$request->start = 0;
		$request->length = 5000;
		$results = $this->searchAusentismos($id_cliente,$request);
		$ausentismos = $results['data'];
		//dd($ausentismos);

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