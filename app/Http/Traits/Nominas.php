<?php

namespace App\Http\Traits;
use App\Nomina;
use App\Cliente;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

trait Nominas
{

	public function searchNomina($idcliente=null, Request $request)
	{

		$today = CarbonImmutable::now();

		$query = Nomina::select('*')
			->with(['ausentismos'=>function($query){
				$query->with(['tipo'=>function($query){
					$query->select('id','nombre');
				}]);
			}])
			->where('id_cliente',$idcliente);

		$total = $query->count();

		if($request->search){
			$query->where(function($query) use($request){
				$filtro = '%'.$request->search.'%';
				$query->where('nombre','like',$filtro)
					->orWhere('email','like',$filtro)
					->orWhere('dni','like',$filtro)
					->orWhere('telefono','like',$filtro);
			});
		}

		if(!is_null($request->estado)) $query->where('estado','=',(int) $request->estado);


		if($request->order){
			$sort = $request->columns[$request->order[0]['column']]['name'];
			///dd($sort);
			$dir  = $request->order[0]['dir'];
			$query->orderBy($sort,$dir);
		}

		if($request->ausentes){

			if($request->ausentes=='hoy'){
				$query->whereHas(
					'ausentismos',function($query) use ($today) {

						$query->where(function($query) use ($today) {
							$query
								->where('fecha_final',null)
								->orWhere('fecha_final','>=',$today->format('Y-m-d'));
						})
						->where('fecha_inicio','<=',$today->format('Y-m-d'))
						->with(['tipo'=>function($query){
							$query->where('incluir_indice',1);
						}]);

					}
				);
			}else{
				$query->whereHas(
					'ausentismos',function($query) use ($today,$request) {

						$query
							->join('ausentismo_tipo','ausentismo_tipo.id','ausentismos.id_tipo')
							->where(function($query) use ($today){
								$query
									->where('fecha_final',null)
									->orWhere('fecha_final','>',$today);
							})
							->where('ausentismo_tipo.nombre','LIKE','%'.$request->ausentes.'%');
					}
				);
			}

		}

		//$query->onlyTrashed();
		$records_filtered = $query->count();
		$nominas = $query->skip($request->start)->take($request->length)->get();
		foreach($nominas as $k=>$nomina){
			$nominas[$k]->photo_url = $nomina->photo_url;
			$nominas[$k]->thumb_url = $nomina->thumbnail_url;
		}

		return [
			'draw'=>$request->draw,
			'recordsTotal'=>$total,
			'recordsFiltered'=>$records_filtered,
			'data'=>$nominas,
			'request'=>$request->all()
		];


	}

	public function exportNomina($idcliente=null, Request $request)
	{

		if(!$idcliente) dd('Faltan parámetros');

		$cliente = Cliente::findOrFail($idcliente);

		//$request->search = ['value'=>null];
		$request->start = 0;
		$request->length = 5000;
		$results = $this->searchNomina($idcliente,$request);
		$nominas = $results['data'];

		///$nominas = Nomina::where('id_cliente',$idcliente)->orderBy('nombre','asc')->get();
		///if(!$nominas) dd('No se han encontrado trabajadores');

		$hoy = Carbon::now();
		$file_name = 'nomina-'.Str::slug($cliente->nombre).'-'.$hoy->format('YmdHis').'.csv';

		$fp = fopen('php://memory', 'w');
		fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
		fputcsv($fp,[
			'Nombre',
			'Email',
			'Teléfono',
			'DNI',
			'Estado',
			'Sector',
			'Calle',
			'Nro',
			'Entre Calles',
			'Localidad',
			'Partido',
			'Código Postal',
			'Observaciones'
		],';');

		foreach($nominas as $nomina){
			$estado = $nomina->estado ? 'activo' : 'inactivo';
			fputcsv($fp,[
				$nomina->nombre,
				$nomina->email,
				$nomina->telefono,
				$nomina->dni,
				$estado,
				$nomina->sector,
				$nomina->calle,
				$nomina->nro,
				$nomina->entre_calles,
				$nomina->localidad,
				$nomina->partido,
				$nomina->cod_postal,
				$nomina->observaciones
			],';');
		}
		fseek($fp, 0);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="'.$file_name.'";');
		fpassthru($fp);


		return;
	}

}