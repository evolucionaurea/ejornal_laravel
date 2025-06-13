<?php

namespace App\Http\Traits;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use App\Nomina;
use App\NominaClienteHistorial;
use App\Cliente;
use App\CovidTesteo;
use App\CovidVacuna;
use App\ConsultaMedica;
use App\ConsultaEnfermeria;
use App\Ausentismo;
use App\Caratula;
use App\ConsultaNutricional;
use App\TareaLiviana;
use App\Preocupacional;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait Nominas
{

	public function searchNomina($idcliente=null, Request $request)
	{

		$today = CarbonImmutable::now();

		DB::statement("SET time_zone = '-03:00'");

		DB::enableQueryLog();

		$query = Nomina::select('*')
			->with(['ausentismos'=>function($query){
				$query->with(['tipo'=>function($query){
					$query
						->select('id','nombre');
				}])
				->selectRaw("
					*,
					IF(
						IFNULL(fecha_final,DATE(NOW())) >= DATE(NOW()),
						1,
						0
					) ausente_hoy,
					NOW() fecha_servidor"
				);
			}])
			->where('id_cliente',$idcliente);

		$total = $query->count();

		if($request->search){
			$query->where(function($query) use($request){
				$filtro = '%'.$request->search.'%';
				$query->where('nombre','like',$filtro)
					->orWhere('email','like',$filtro)
					->orWhere('legajo','like',$filtro)
					->orWhere('dni','like',$filtro)
					->orWhere('telefono','like',$filtro);
			});
		}

		if(!is_null($request->estado)) {
			$query->where('estado','=',(int) $request->estado);
		}


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
			'request'=>$request->all(),
			'queries'=>DB::getQueryLog()
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
			'Estado',
			'Sector',
			'Legajo',
			'Teléfono',
			'DNI',
			'Fecha de Nacimiento',
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
				$estado,
				$nomina->sector,
				$nomina->legajo,
				$nomina->telefono,
				$nomina->dni,
				$nomina->fecha_final,
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

	public function perfilTrabajador($id)
	{
		$clientes = $this->getClientesUser();

		$caratula = Caratula::where('id_nomina', $id)->latest()->first();

		$trabajador = Nomina::findOrFail($id);


		$testeos = CovidTesteo::where('id_nomina',$id)
			->with('tipo')
			->orderBy('fecha', 'desc')
			->get();
		$vacunas = CovidVacuna::where('id_nomina',$id)
			->with('tipo')
			->orderBy('fecha', 'desc')
			->get();


		$consultas_medicas = ConsultaMedica::where('id_nomina',$id)
			->with(['diagnostico','cliente'])
			->orderBy('fecha','desc')
			->get();

		$consultas_enfermeria = ConsultaEnfermeria::where('id_nomina',$id)
			->with(['diagnostico','cliente'])
			->orderBy('fecha','desc')
			->get();

		$consultas_nutricionales = ConsultaNutricional::where('id_nomina',$id)
		->with(['nomina','cliente'])
		->orderBy('fecha_atencion','desc')
		->get();

		$ausentismos = Ausentismo::where('id_trabajador', $id)
			->with([
				'trabajador',
				'tipo',
				'comunicaciones.tipo',
				'documentaciones',
				'cliente',
				'comunicaciones.archivos' // Cargar archivos relacionados a las comunicaciones
			])
			->orderBy('fecha_inicio', 'desc')
			->get();


		$preocupacionales = Preocupacional::where('id_nomina',$id)
			->with(['trabajador','cliente','tipo'])
			/*->whereHas('trabajador',function($query){
				$query->where('id_cliente', auth()->user()->id_cliente_actual);
			})*/
			->where('id_cliente', auth()->user()->id_cliente_actual)
			->orderBy('fecha', 'desc')
			->get();

		///historial fix

		$resumen_historial = [];
		foreach($ausentismos as $ausentismo){
			$resumen_historial[$ausentismo->fecha_inicio->format('Ymd')] = (object) [
				'fecha'=>$ausentismo->fecha_inicio,
				'tipo'=>$ausentismo->tipo->nombre,
				'evento'=>'Ausentismo',
				'observaciones'=>$ausentismo->comentario,
				'usuario'=>$ausentismo->user,
				'cliente'=>$ausentismo->cliente
			];
		}
		foreach($consultas_enfermeria as $enfermeria){
			$resumen_historial[$enfermeria->fecha->format('Ymd')] = (object) [
				'fecha'=>$enfermeria->fecha,
				'tipo'=>$enfermeria->diagnostico->nombre,
				'evento'=>'Consulta Enfermería',
				'observaciones'=>$enfermeria->observaciones,
				'usuario'=>$enfermeria->user,
				'cliente'=>$enfermeria->cliente
			];
		}
		foreach($consultas_medicas as $medica){
			$resumen_historial[$medica->fecha->format('Ymd')] = (object) [
				'fecha'=>$medica->fecha,
				'tipo'=>$medica->diagnostico->nombre,
				'evento'=>'Consulta Médicas',
				'observaciones'=>$medica->observaciones,
				'usuario'=>$medica->user,
				'cliente'=>$medica->cliente
			];
		}
		foreach($consultas_nutricionales as $nutricional){
			$resumen_historial[$nutricional->fecha_atencion->format('Ymd')] = (object) [
				'fecha'=>$nutricional->fecha_atencion,
				'tipo'=>$nutricional->tipo,
				'evento'=>'Consulta Nutricional',
				'Objetivos'=>$nutricional->objetivos,
				'usuario'=>$nutricional->user,
				'cliente'=>$nutricional->cliente
			];
		}
		foreach($preocupacionales as $preocupacional){
			$resumen_historial[$preocupacional->fecha->format('Ymd')] = (object) [
				'fecha'=>$preocupacional->fecha,
				'tipo' => isset($preocupacional->tipo) ? $preocupacional->tipo->nombre : 'Sin especificar',
				'evento'=>'Exámen Médico Complementario',
				'observaciones'=>$preocupacional->observaciones,
				'usuario'=>'',
				'cliente'=>$preocupacional->cliente
			];
		}
		krsort($resumen_historial);
		
		return compact(
			'caratula',
			'trabajador',
			'consultas_medicas',
			'consultas_enfermeria',
			'consultas_nutricionales',
			'ausentismos',
			'clientes',
			'vacunas',
			'testeos',
			'preocupacionales',
			'resumen_historial'
		);

	}

	public function hasAusentismoOrTareaLiviana($trabajador){

		///VALIDAR QUE NO TENGA UN AUSENTISMO VIGENTE
		$ausentismo = Ausentismo::where('id_trabajador',$trabajador->id)
			->where(function($query){
				$query
					->where('fecha_final','>=',Carbon::now()->format('Y-m-d'))
					->orWhereNull('fecha_final');
			})
			->get();
		if($ausentismo->count()){
			return 'Este trabajador posee un ausentismo vigente y no puede ser cambiado de cliente/sucursal.';
		}

		///VALIDAR QUE NO TENGA UNA TAREA ADECUADA VIGENTE
		$tarea_liviana = TareaLiviana::where('id_trabajador',$trabajador->id)
			->where(function($query){
				$query
					->where('fecha_final','>=',Carbon::now()->format('Y-m-d'))
					->orWhereNull('fecha_final');
			})
			->get();
		if($tarea_liviana->count()){
			return 'Este trabajador posee una tarea adecuada vigente y no puede ser cambiado de cliente/sucursal.';
		}


		return false;
	}


	public function movimientosListado($cliente_ids=[],Request $request){

		$nominas_clientes = Nomina::select('*')
			->withCount('movimientos_cliente')
			->having('movimientos_cliente_count','>',1)
			->pluck('id');


		//DB::raw('COUNT(*) as cantidad')
		$query = NominaClienteHistorial::select('*')
			->with(['trabajador','cliente','usuario'])
			->whereIn('nomina_id',$nominas_clientes)
			->whereIn('cliente_id',$cliente_ids)

			->orderBy('created_at','desc');

		$total = $query->count();

		if($request->cliente_id){
			$query->where('cliente_id',$request->cliente_id);
		}

		if(isset($request->search)){
			$search = '%'.$request->search.'%';
			$query->where(function($query) use($search){
				$query
					->whereHas('trabajador',function($query) use($search){
						$query
							->where('nombre','like',$search)
							->orWhere('dni','like',$search);
					})
					->orWhereHas('cliente',function($query) use($search){
						$query->where('nombre','like',$search);
					})
					->orWhereHas('usuario',function($query) use($search){
						$query->where('nombre','like',$search);
					});
			});
		}

		if($request->order){
			//$sort = $request->columns[$request->order[0]['column']]['name'];
			//$dir  = $request->order[0]['dir'];
			//$query->orderBy($sort,$dir);
		}

		$total_filtered = $query->count();


		return [
			'draw'=>$request->draw,
			'recordsTotal'=>$total,
			'recordsFiltered'=>$total_filtered,
			'data'=>$query->skip($request->start)->take($request->length)->get(),
			'request'=>$request->all()
		];


	}



}