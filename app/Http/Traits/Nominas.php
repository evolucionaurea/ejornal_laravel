<?php

namespace App\Http\Traits;
use App\Nomina;
use App\Cliente;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;

trait Nominas
{

	public function searchNomina($idcliente=null)
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

		if($this->request->search){
			$query->where(function($query) {
				$filtro = '%'.$this->request->search['value'].'%';
				$query->where('nombre','like',$filtro)
					->orWhere('email','like',$filtro)
					->orWhere('dni','like',$filtro)
					->orWhere('telefono','like',$filtro);
			});
		}


		if(!is_null($this->request->estado)) $query->where('estado','=',(int) $this->request->estado);


		if($this->request->order){
			$sort = $this->request->columns[$this->request->order[0]['column']]['data'];
			$dir  = $this->request->order[0]['dir'];
			$query->orderBy($sort,$dir);
		}

		if($this->request->ausentes){

			if($this->request->ausentes=='hoy'){
				$query->whereHas(
					'ausentismos',function($query) use ($today) {
						$query
							->where('fecha_regreso_trabajar',null)
							->orWhere('fecha_regreso_trabajar','>',$today);
					}
				);
			}else{
				$query->whereHas('ausentismos',function($query) use ($today) {
						$query
							->join('ausentismo_tipo','ausentismo_tipo.id','ausentismos.id_tipo')
							->where(function($query) use ($today){
								$query
									->where('fecha_regreso_trabajar',null)
									->orWhere('fecha_regreso_trabajar','>',$today);
							})
							->where('ausentismo_tipo.nombre','LIKE','%'.$this->request->ausentes.'%');
					}
				);
			}

		}

		//$query->onlyTrashed();



		return [
			'draw'=>$this->request->draw,
			'recordsTotal'=>$total,
			'recordsFiltered'=>$query->count(),
			'data'=>$query->skip($this->request->start)->take($this->request->length)->get(),
			'request'=>$this->request->all()
		];


	}

	public function exportNomina($idcliente=null)
	{

		if(!$idcliente) dd('Faltan parámetros');

		$cliente = Cliente::findOrFail($idcliente);

		$nominas = Nomina::where('id_cliente',$idcliente)->orderBy('nombre','asc')->get();

		if(!$nominas) dd('No se han encontrado trabajadores');

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