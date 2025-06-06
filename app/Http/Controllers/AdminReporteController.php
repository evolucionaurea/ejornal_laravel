<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;
use App\Fichada;
use App\FichadaNueva;
use App\User;
use App\Nomina;
use App\AusentismoTipo;
use App\AusentismoDocumentacion;
use App\AusentismoDocumentacionArchivos;
use App\Ausentismo;
use App\ConsultaMedica;
use App\ConsultaEnfermeria;
use App\Comunicacion;
use App\Cliente;
use App\TipoComunicacion;
use App\Preocupacional;
use App\PreocupacionalArchivo;
use App\TareaLiviana;
use App\TareaLivianaTipo;
use App\ComunicacionLiviana;
use App\ConsultaNutricional;
use App\TareaLivianaDocumentacion;
use App\EdicionFichada;
use App\PreocupacionalTipoEstudio;

use App\Http\Traits\Ausentismos;
use App\Http\Traits\Preocupacionales;
use App\Http\Traits\TareasLivianas;

class AdminReporteController extends Controller
{

	use Ausentismos,Preocupacionales,TareasLivianas;


	/* FICHADAS */
	public function reportes_fichadas_nuevas()
	{
		return view('admin.reportes.fichadas');
	}
	public function fichadas_ajax(Request $request)
	{

		$query = FichadaNueva::selectRaw('
			fichadas_nuevas.*,
			users.nombre as user_nombre,
			users.estado as user_estado,
			IF(users.id_especialidad = 0, "No aplica", especialidades.nombre) as user_especialidad,
			clientes.nombre as cliente_nombre,
			(
				fichadas_nuevas.id = (
					SELECT MAX(fn.id)
					FROM fichadas_nuevas fn
					WHERE fn.id_user = fichadas_nuevas.id_user
				)
			) as ultimo_registro_user
		')
		->join('users', 'users.id', '=', 'fichadas_nuevas.id_user')
		->leftJoin('especialidades', 'especialidades.id', '=', 'users.id_especialidad')
		->join('clientes', 'clientes.id', '=', 'fichadas_nuevas.id_cliente')
		->with(['user.especialidad', 'cliente']);

		$total_records = $query->count();

		// Filtro por estado de usuario
		if ($request->estado && $request->estado !== 'todos') {
			$estado = $request->estado === 'activos' ? 1 : 0;
			$query->whereHas('user', function($query) use($estado) {
				$query->where('estado', $estado);
			});
		}

		// Filtro de búsqueda general por nombre de usuario o cliente
		if ($request->search['value']) {
			$filtro = '%' . $request->search['value'] . '%';
			$query->where(function($query) use ($filtro) {
			$query->whereHas('user', function($query) use($filtro) {
					$query->where('nombre', 'like', $filtro);
				})
				->orWhereHas('cliente', function($query) use($filtro) {
					$query->where('nombre', 'like', $filtro);
				});
			});
		}

		// Filtro de rango de fechas
		if ($request->from) {
			$query->whereDate('ingreso', '>=', Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		}
		if ($request->to) {
			$query->whereDate('egreso', '<=', Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
		}

		// Ordenación según el parámetro de orden
		if ($request->order) {
			$sort = $request->columns[$request->order[0]['column']]['name'];
			$dir = $request->order[0]['dir'];
			$query->orderBy($sort, $dir);
		} else {
			$query->orderBy('ingreso', 'desc'); // Ordenación por defecto
		}

		// Respuesta con datos filtrados y paginados
		/*$data = $query->skip($request->start)->take($request->length)->first();
		dd($data->toArray());*/
		return [
			'draw' => $request->draw,
			'recordsTotal' => $total_records,
			'recordsFiltered' => $query->count(),
			'data' => $query->skip($request->start)->take($request->length)->get(),
			'request' => $request->all(),
			'user'=>auth()->user()
		];


	}
	public function exportar_fichadas($id_cliente=null, Request $request)
	{


		$request->search = ['value' => null];
		$request->start = 0;
		$request->order = [
			[
				'dir'=>'desc',
				'column'=>0
			]
		];
		$request->columns = [
			['name'=>'ingreso']
		];
		$request->length = 15000;
		//$request->length = 2;


		$fichadas = $this->fichadas_ajax($request)['data'];
		//dd($fichadas->toArray()[1]['egreso_carbon']->format('H:i'));


		$now = Carbon::now();
		$file_name = 'fichadas-'.$now->format('YmdHis').'.csv';

		$fp = fopen('php://memory', 'w');
		fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
		fputcsv($fp, [
				'Empleado',
				'Estado',
				'Especialidad',
				'Empresa',

				'Día Ingreso',
				'Fecha Ingreso',
				'Hora Ingreso',

				'Fecha Egreso',
				'Hora Egreso',

				'Tiempo trabajado',

				'Sistema Operativo',
				'Dispositivo',
				'Navegador',
				'IP'
		], ';');

		foreach($fichadas as $fichada){

			fputcsv($fp, [
				$fichada->user->nombre,
				$fichada->user->estado == 1 ? 'Activo' : 'Inactivo',
				$fichada->user->especialidad->nombre,
				$fichada->cliente ? $fichada->cliente->nombre : '',

				//$fichada->ingreso.' al '.($fichada->egreso ?? 'aún trabajando'),
				mb_convert_case($fichada->ingreso_carbon->translatedFormat('l'),MB_CASE_TITLE,'UTF-8'),
				$fichada->ingreso_carbon->format('d/m/Y'),
				$fichada->ingreso_carbon->format('H:i'),

				$fichada->egreso ? $fichada->egreso_carbon->format('d/m/Y') : '[aún trabajando]',
				$fichada->egreso ? $fichada->egreso_carbon->format('H:i') : '[aún trabajando]',

				$fichada->egreso ? $fichada->horas_minutos_trabajado : '[aún trabajando]',

				$fichada->sistema_operativo,
				$fichada->dispositivo,
				$fichada->browser,
				$fichada->ip
			], ';');
		}

		fseek($fp, 0);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="'.$file_name.'";');
		fpassthru($fp);

		return;
	}
	public function cambiar_fichada(Request $request){


		$request->validate([
			'id' => 'required|integer|exists:fichadas_nuevas,id',
			'new_date' => 'required|string', // Cambiado a 'string' para manipulación
			'action' => 'required|string',
		]);

		if(!auth()->user()->permiso_edicion_fichada){
			return response()->json([
				'success'=>false,
				'message'=>'No estás autorizado para cambiar la fecha de la fichada.'
			],400);
		}

		// Obtener datos de la fichada a editar
		$fichada = FichadaNueva::where('id',$request->id)->with('user')->first();
		if (!$fichada) {
			return response()->json([
				'success' => false,
				'message' => 'Fichada no encontrada.'
			], 404);
		}

		$userId = auth()->user()->id;
		//$oldValue = $request->oldDate;
		$old_date = $request->action == 'ingreso' ? $fichada->ingreso : $fichada->egreso;


		//$oldDateObj = new DateTime($oldValue);
		// Convertir la nueva fecha desde el formato DD/MM/YYYY
		$dateParts = explode('/', $request->new_date);

		if (count($dateParts) === 3) {
			// Crear la fecha en formato 'YYYY-MM-DD'
			$formattedDate = "{$dateParts[2]}-{$dateParts[1]}-{$dateParts[0]} {$request->new_hour}:{$request->new_minutes}:00"; // '2024-10-25'
			//dd($oldValue);
			$newDate = new DateTime($formattedDate); // Crear el objeto DateTime
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Formato de fecha inválido.'
			], 400);
		}

		// validar que no se superponga con la fecha de otra fichada
		$fichadaUserId = $fichada->id_user;

		$solapamiento = FichadaNueva::where('id_user',$fichadaUserId)
			->where('id','!=',$fichada->id)
			->whereRaw("'{$formattedDate}' >= ingreso AND '{$formattedDate}' < egreso")
			->first();

		if($solapamiento){

			return response()->json([
				'success' => false,
				'message' => 'La fecha/hora ingresada se superpone con otra fichada'
			], 400);

		}



		// Obtener el id_user de la fichada

		// Verificar si es la última fichada del usuario
		/*$ultimoRegistro = FichadaNueva::where('id_user', $fichadaUserId)
				->with('user')
				->orderBy('id', 'desc')
				->first();
		dd($ultimoRegistro);
		if (!$ultimoRegistro || $ultimoRegistro->id != $fichada->id) {
				return response()->json([
					'success' => false,
					'message' => 'No es posible editar el registro porque no es la última fichada del usuario.'
				], 400);
		}*/


		// Asignar id_user y id_fichada
		$edicionFichada = new EdicionFichada();
		$edicionFichada->id_user = auth()->user()->id;
		$edicionFichada->id_fichada = $fichada->id;

		// Validar según el tipo de edición
		if ($request->action=='ingreso') {
			// Validar que el egreso no tenga una fecha inferior a la nueva fecha de ingreso
			if ($fichada->egreso && new DateTime($fichada->egreso) < $newDate) {
					return response()->json([
						'success' => false,
						'message' => 'El egreso registrado es anterior a la nueva fecha de ingreso.'
					], 400);
			}

			$fichada->ingreso = $newDate;
			$fichada->save();

			$edicionFichada->old_ingreso = $fichada->ingreso;
			$edicionFichada->new_ingreso = $newDate;
			$edicionFichada->old_egreso = null;
			$edicionFichada->new_egreso = null;
		}
		if ($request->action=='egreso') {
			// Validar que el ingreso no tenga una fecha mayor a la nueva fecha de egreso
			if ($fichada->ingreso && new DateTime($fichada->ingreso) > $newDate) {
					return response()->json([
						'success' => false,
						'message' => 'El ingreso registrado es posterior a la nueva fecha de egreso.'
					], 400);
			}

			$fichada->egreso = $newDate;
			$fichada->save();

			$edicionFichada->old_egreso = $fichada->egreso;
			$edicionFichada->new_egreso = $newDate;
			$edicionFichada->old_ingreso = null;
			$edicionFichada->new_ingreso = null;
		}



		// Obtener la IP y el dispositivo
		$agent = new Agent();
		$edicionFichada->ip = $request->ip();
		$edicionFichada->dispositivo = $agent->browser() . ' (' . $agent->platform() . ' ' . $agent->version($agent->platform()) . ' | '. device_spanish($agent->deviceType()) .')';

		// Guardar el registro
		$edicionFichada->save();

		return response()->json([
			'success' => true,
			'message'=>'La fichada se actualizó correctamente!',
			'last_record'=>$fichada
		]);

	}
	public function find_fichada($id){
		return FichadaNueva::where('id',$id)->with('user')->first();
	}



	/* AUSENTISMOS */
	public function reportes_ausentismos()
	{
		$tipos = AusentismoTipo::get();
		$clientes = Cliente::get();
		return view('admin.reportes.ausentismos',compact('tipos','clientes'));
	}
	public function ausentismos_ajax(Request $request)
	{

		$filtro = '%'.$request->search['value'].'%';
		$query = Ausentismo::selectRaw(
			'ausentismos.*,
			(
				DATEDIFF(
					IFNULL(
						fecha_final,
						DATE(NOW())
					),fecha_inicio
				)
			)+1 as dias_ausente'
		)
		->join('ausentismo_tipo','ausentismo_tipo.id','ausentismos.id_tipo')
		->join('nominas','nominas.id','ausentismos.id_trabajador')
		->join('clientes','clientes.id','nominas.id_cliente');

		$total_records = $query->count();

		$query
			->with(['tipo'=>function($query){
				$query
					->select('id','nombre');
			}])
			->with(['trabajador'=>function($query){
				$query
					->select('id','nombre','id_cliente')
					->with(['cliente'=>function($query){
						$query->select('id','nombre');
					}]);
			}])
			->with('documentaciones.archivos');


		/// KEYWORDS
		$filtro = '%'.$request->search['value'].'%';
		$query->where(function($query) use ($filtro) {

			$query->whereIn('id_trabajador',function($query) use ($filtro){
				$query->select('id')
					->from('nominas')
					->where(function($query) use ($filtro){
						$query
							->where('deleted_at',null)
							->where('nombre','like',$filtro);
					})
					->orWhereIn('id_cliente',function($query) use ($filtro){
						$query->select('id')
							->from('clientes')
							->where('nombre','like',$filtro);
					});
			})
			->orWhereIn('id_tipo',function($query) use ($filtro){
				$query->select('id')
					->from('ausentismo_tipo')
					->where('nombre','like',$filtro);
			});

		});


		// FILTROS
		if($request->from) $query->whereDate('ausentismos.fecha_inicio','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		if($request->to) $query->whereDate('ausentismos.fecha_final','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
		if($request->tipo) $query->where('ausentismos.id_tipo',$request->tipo);
		if($request->cliente) $query->where('clientes.id',$request->cliente);


		// ORDER
		if($request->order){
			$sort = $request->columns[$request->order[0]['column']]['name'];
			$dir  = $request->order[0]['dir'];
			//$relations = ['trabajador','tipo'];
			$query->orderBy($sort,$dir);
		}

		return [
			'draw'=>$request->draw,
			'recordsTotal'=>$total_records,
			'recordsFiltered'=>$query->count(),
			'data'=>$query->skip($request->start)->take($request->length)->get(),
			'request'=>$request->all()
		];

	}

	/* CERTIFICADOS */
	public function reportes_certificaciones()
	{
		$clientes = Cliente::all();
		$ausentismo_tipos = AusentismoTipo::all();
		return view('admin.reportes.certificaciones',compact(
			'clientes',
			'ausentismo_tipos'
		));
	}
	public function certificaciones(Request $request)
	{
		return $this->ausentismos_ajax($request);
	}



	/* COMUNICACIONES */
	public function reportes_comunicaciones()
	{
		$clientes = Cliente::all();
		$ausentismo_tipos = AusentismoTipo::all();
		$comunicacion_tipos = TipoComunicacion::all();

		return view('admin.reportes.comunicaciones',compact(
			'clientes',
			'ausentismo_tipos',
			'comunicacion_tipos'
		));
	}
	public function comunicaciones(Request $request)
	{

		$query = Comunicacion::with([
			'tipo',
			'ausentismo.trabajador',
			'ausentismo.cliente',
			'ausentismo.tipo'
		])
		->select('comunicaciones.*')

		->join('ausentismos', 'comunicaciones.id_ausentismo', 'ausentismos.id')
		->join('tipo_comunicacion', 'comunicaciones.id_tipo', 'tipo_comunicacion.id')
		->join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
		->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
		->join('clientes', 'nominas.id_cliente', 'clientes.id')

		->whereHas('ausentismo.trabajador',function($query){
			$query->where('deleted_at',null);
		});

		$total_records = $query->count();


		// FILTROS
		if($request->from) $query->whereDate('comunicaciones.created_at','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		if($request->to) $query->whereDate('comunicaciones.created_at','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
		if($request->search) {
			$query->where(function($query) use($request) {
				$filtro = '%'.$request->search['value'].'%';
				$query->where('descripcion','like',$filtro)
					->orWhere('tipo_comunicacion.nombre','like',$filtro)
					->orWhere('ausentismo_tipo.nombre','like',$filtro)
					->orWhere('nominas.nombre','like',$filtro)
					->orWhere('clientes.nombre','like',$filtro);
			});
		}
		if($request->cliente) $query->where('clientes.id',$request->cliente);
		if($request->ausentismo_tipo) $query->where('ausentismo_tipo.id',$request->ausentismo_tipo);
		if($request->comunicacion_tipo) $query->where('tipo_comunicacion.id',$request->comunicacion_tipo);
		///////

		// SORT
		if($request->order){
			$sort = $request->columns[$request->order[0]['column']]['name'];
			$dir  = $request->order[0]['dir'];
			$query->orderBy($sort,$dir);
		}
		///////

		return [
			'draw'=>$request->draw,
			'recordsTotal'=>$total_records,
			'recordsFiltered'=>$query->count(),
			'data'=>$query->skip($request->start)->take($request->length)->get(),
			'request'=>$request->all()
		];

	}

	/* PREOCUPACIONALES */
	public function reportes_preocupacionales(){

		///dd($_SERVER);
		//dd(env('APP_URL'));

		$clientes = Cliente::all();
		$tipos = PreocupacionalTipoEstudio::all();

		return view('admin.reportes.preocupacionales',compact(
			'clientes',
			'tipos'
		));
	}
	public function preocupacionales(Request $request){
		return $this->preocupacionalesAjax($request);
	}
	public function descargar_archivo_preocupacionales($id){

		$archivo = PreocupacionalArchivo::find($id);
		$ruta = storage_path("app/preocupacionales/trabajador/{$archivo->preocupacional_id}/{$archivo->hash_archivo}");
		return download_file($ruta);

	}



	/* ADECUADAS */
	public function reportes_tareas_adecuadas(){

		$clientes = Cliente::all();
		$tipos = TareaLivianaTipo::all();

		return view('admin.reportes.tareas_adecuadas',compact(
			'clientes',
			'tipos'
		));
	}
	public function tareas_adecuadas(Request $request){
		return $this->searchTareasLivianas($request);
	}
	public function descargar_archivo_tarea_liviana($id){

		$archivo = TareaLiviana::find($id);
		$ruta = storage_path("app/tareas_livianas/trabajador/{$archivo->id}/{$archivo->hash_archivo}");
		return download_file($ruta);

	}



	public function fichadas_nuevas()
	{

		$fichadas =  FichadaNueva::join('users', 'fichadas_nuevas.id_user', 'users.id')
		->join('clientes', 'fichadas_nuevas.id_cliente', 'clientes.id')
		->select('fichadas_nuevas.*', DB::raw('clientes.nombre cliente'), DB::raw('users.nombre user'))
		->get();

		return $fichadas;
	}



	/*public function ausentismos(Request $request)
	{
		$results = Ausentismo::join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
		->join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
		->join('clientes', 'nominas.id_cliente', 'clientes.id')
		->select(DB::raw('clientes.nombre cliente'), 'ausentismos.id', 'nominas.nombre', DB::raw('ausentismo_tipo.nombre tipo'), 'ausentismos.fecha_inicio',
		'ausentismos.fecha_final', 'ausentismos.fecha_regreso_trabajar', 'ausentismos.updated_at', 'ausentismos.user')
		->get();
		$ausentismos = [];




		foreach ($results as $resultado) {
			$fecha1 = date_create($resultado->fecha_inicio);
			$fecha2 = date_create($resultado->fecha_final);
			$dias = date_diff($fecha1, $fecha2)->format('%R%a');
			$dias_final = str_replace("+", "", $dias);

			$documentaciones = AusentismoDocumentacion::where('id_ausentismo', $resultado->id)->get();

			$ausentismos[] = [
				'id' => $resultado->id,
				'cliente' => $resultado->cliente,
				'trabajador' => $resultado->nombre,
				'tipo' => $resultado->tipo,
				'fecha_inicio' => (isset($resultado->fecha_inicio) ? $resultado->fecha_inicio : '' ),
				'fecha_final'=> (isset($resultado->fecha_final) ? $resultado->fecha_final : '' ),
				'dias_ausente' => $dias_final,
				'documentaciones' => $documentaciones,
				'updated_at' => $resultado->updated_at,
				'user' => ($resultado->user != null) ? $resultado->user : '',
			];
		}
		return $ausentismos;
	}*/



	/* CONSULTAS MEDICAS/ENFERMERIA/NUTRICIONAL */
	public function reportes_consultas()
	{
		$clientes = Cliente::all();
		return view('admin.reportes.consultas',compact('clientes'));
	}
	public function consultas_medicas(Request $request)
	{

		$query = ConsultaMedica::select('consultas_medicas.*')
			->with('diagnostico')
			->with('trabajador.cliente')
			->whereHas('trabajador',function($query){
				$query->where('deleted_at',null);
			})
			->join('nominas','consultas_medicas.id_nomina', 'nominas.id')
			->join('clientes','nominas.id_cliente', 'clientes.id')
			->join('diagnostico_consulta','consultas_medicas.id_diagnostico_consulta', 'diagnostico_consulta.id');

		$total = $query->count();

		if($request->fecha_inicio){
			$fecha_inicio = Carbon::createFromFormat('d/m/Y',$request->fecha_inicio);
			$query->where('fecha','>=',$fecha_inicio);
		}
		if($request->fecha_final){
			$fecha_final = Carbon::createFromFormat('d/m/Y',$request->fecha_final);
			$query->where('fecha','<=',$fecha_final);
		}
		if($request->keywords){
			$query->whereHas('trabajador',function($query) use($request){
				$query->where('nombre','LIKE',"%{$request->keywords}%");
			});
		}
		if($request->cliente) $query->where('consultas_medicas.id_cliente',$request->cliente);

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
			'request'=>$request->all()
		];

	}
	public function consultas_enfermeria(Request $request)
	{

		$query = ConsultaEnfermeria::select('consultas_enfermerias.*')
			->with('diagnostico')
			->with('trabajador.cliente')
			->whereHas('trabajador',function($query){
				$query->where('deleted_at',null);
			})
			->join('nominas','consultas_enfermerias.id_nomina', 'nominas.id')
			->join('clientes','nominas.id_cliente', 'clientes.id')
			->join('diagnostico_consulta','consultas_enfermerias.id_diagnostico_consulta', 'diagnostico_consulta.id');

		$total = $query->count();

		if($request->fecha_inicio){
			$fecha_inicio = Carbon::createFromFormat('d/m/Y',$request->fecha_inicio);
			$query->where('fecha','>=',$fecha_inicio);
		}
		if($request->fecha_final){
			$fecha_final = Carbon::createFromFormat('d/m/Y',$request->fecha_final);
			$query->where('fecha','<=',$fecha_final);
		}

		if($request->cliente) $query->where('consultas_enfermerias.id_cliente',$request->cliente);

		if($request->keywords){
			$query->whereHas('trabajador',function($query) use($request){
				$query->where('nombre','LIKE',"%{$request->keywords}%");
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
			'request'=>$request->all()
		];

	}
	public function consultas_nutricionales(Request $request)
	{

		$query = ConsultaNutricional::select(
			'nominas.nombre',
			'consultas_nutricionales.id',
			'consultas_nutricionales.id_nomina',
			'consultas_nutricionales.fecha_atencion as fecha',
			DB::raw('NULL as derivacion_consulta'),
			DB::raw('NULL as diagnostico'),
			)
			->with('trabajador.cliente')
			->whereHas('trabajador',function($query){
				$query->where('deleted_at',null);
			})
			->join('nominas','consultas_nutricionales.id_nomina', 'nominas.id')
			->join('clientes','nominas.id_cliente', 'clientes.id');

		$total = $query->count();

		if($request->fecha_inicio){
			$fecha_inicio = Carbon::createFromFormat('d/m/Y',$request->fecha_inicio);
			$query->where('fecha','>=',$fecha_inicio);
		}
		if($request->fecha_final){
			$fecha_final = Carbon::createFromFormat('d/m/Y',$request->fecha_final);
			$query->where('fecha','<=',$fecha_final);
		}

		if($request->cliente) $query->where('consultas_nutricionales.id_cliente',$request->cliente);

		if($request->keywords){
			$query->whereHas('trabajador',function($query) use($request){
				$query->where('nombre','LIKE',"%{$request->keywords}%");
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
			'request'=>$request->all()
		];

	}


	public function descargar_documentacion($id)
	{
		$ausentismo_documentacion = AusentismoDocumentacion::find($id);
		$ruta = storage_path("app/documentacion_ausentismo/{$ausentismo_documentacion->id}/{$ausentismo_documentacion->hash_archivo}");
		return download_file($ruta);
		return back();
	}


	public function filtrarFichadasNuevas(Request $request)
	{

		if ($request->ajax()) {

		$fichadas = $this->fichadas_nuevas();
		$fichadas_filtradas = [];
		$fecha_inicio = new DateTime($request->input('fichadas_desde'));
		$fecha_final = new DateTime($request->input('fichadas_hasta'));

		foreach ($fichadas as $fichada) {
		$fichada_inicio = new DateTime($fichada['ingreso']);
		if ($fichada['egreso'] == null) {
			$fichada_final = false;
		}else {
			$fichada_final = new DateTime($fichada['egreso']);
		}
		if ($fichada_final != false) {
			if ($fichada_inicio >= $fecha_inicio && $fichada_inicio <= $fecha_final
			&& $fichada_final >= $fichada_inicio && $fichada_final <= $fecha_final) {
				$fichadas_filtradas[] = $fichada;
			}
		}else {
			if ($fichada_inicio >= $fecha_inicio && $fichada_inicio <= $fecha_final) {
				$fichadas_filtradas[] = $fichada;
			}
		}
		}

		return $fichadas_filtradas;
		}

	}


	/*public function filtrarAusentismos(Request $request)
	{
		if ($request->ajax()) {

		$ausentismos = $this->ausentismos();
		$ausentismos_filtrados = [];
		$fecha_inicio = new DateTime($request->input('ausentismos_desde'));
		$fecha_final = new DateTime($request->input('ausentismos_hasta'));

		foreach ($ausentismos as $ausentismo) {
			$ausentismo_fecha_inicio = new DateTime($ausentismo['fecha_inicio']);
		if (isset($ausentismo['fecha_final']) && !empty($ausentismo['fecha_final'])) {
			$ausentismo_fecha_final = new DateTime($ausentismo['fecha_final']);
			if ($ausentismo_fecha_inicio >= $fecha_inicio && $ausentismo_fecha_final <= $fecha_final) {
				$ausentismos_filtrados[] = $ausentismo;
			}
		}else {
			if ($ausentismo_fecha_inicio >= $fecha_inicio) {
				$ausentismos_filtrados[] = $ausentismo;
			}
		}
		}
		return $ausentismos_filtrados;
		}

	}*/


	public function descargar_archivo($id)
	{
		$archivo = AusentismoDocumentacionArchivos::where('ausentismo_documentacion_id',$id)->first();
		$ruta = storage_path("app/documentacion_ausentismo/{$archivo->ausentismo_documentacion_id}/{$archivo->hash_archivo}");
		////dd($ruta);
		return download_file($ruta);
		return back();
	}


	/*public function filtrarCertificaciones(Request $request)
	{

		if ($request->ajax()) {

		$certificaciones = $this->ausentismos();
		$certificaciones_filtrados = [];
		$fecha_inicio = new DateTime($request->input('certificaciones_desde'));
		$fecha_final = new DateTime($request->input('certificaciones_hasta'));

		foreach ($certificaciones as $certificacion) {
			$certificacion_fecha_inicio = new DateTime($certificacion['fecha_inicio']);
		if (isset($certificacion['fecha_final']) && !empty($certificacion['fecha_final'])) {
			$certificacion_fecha_final = new DateTime($certificacion['fecha_final']);
			if ($certificacion_fecha_inicio >= $fecha_inicio && $certificacion_fecha_final <= $fecha_final) {
				$certificaciones_filtrados[] = $certificacion;
			}
		}else {
			if ($certificacion_fecha_inicio >= $fecha_inicio) {
				$certificaciones_filtrados[] = $certificacion;
			}
		}
		}
		return $certificaciones_filtrados;

		}

	}*/



	public function filtrarConsultasMedicas(Request $request)
	{

		if ($request->ajax()) {

		$consultas_medicas = $this->consultas_medicas();
		$medicas_filtradas = [];
		$fecha_inicio = new DateTime($request->input('consultas_medicas_desde'));
		$fecha_final = new DateTime($request->input('consultas_medicas_hasta'));

		foreach ($consultas_medicas as $medicas) {
			$medicas_fecha = new DateTime($medicas['fecha']);
			if ($medicas_fecha >= $fecha_inicio && $medicas_fecha <= $fecha_final) {
				$medicas_filtradas[] = $medicas;
			}
		}
		return $medicas_filtradas;

		}

	}
	public function filtrarConsultasEnfermeria(Request $request)
	{

		if ($request->ajax()) {

		$consultas_enfermerias = $this->consultas_enfermeria();
		$enfermerias_filtradas = [];
		$fecha_inicio = new DateTime($request->input('consultas_enfermerias_desde'));
		$fecha_final = new DateTime($request->input('consultas_enfermerias_hasta'));

		foreach ($consultas_enfermerias as $enfermeria) {
			$enfermeria_fecha = new DateTime($enfermeria['fecha']);
			if ($enfermeria_fecha >= $fecha_inicio && $enfermeria_fecha <= $fecha_final) {
				$enfermerias_filtradas[] = $enfermeria;
			}
		}
		return $enfermerias_filtradas;

		}

	}
	public function FiltrarComunicaciones(Request $request)
	{

		if ($request->ajax()) {

		$comunicaciones = $this->comunicaciones();
		$comunicaciones_filtradas = [];
		$fecha_inicio = new DateTime($request->input('comunicaciones_desde'));
		$fecha_final = new DateTime($request->input('comunicaciones_hasta'));

		foreach ($comunicaciones as $comunicacion) {
			$comunicacion_fecha = new DateTime($comunicacion['created_at']);
			if ($comunicacion_fecha >= $fecha_inicio && $comunicacion_fecha <= $fecha_final) {
				$comunicaciones_filtradas[] = $comunicacion;
			}
		}
		return $comunicaciones_filtradas;

		}

	}


	// vista
	public function actividad_usuarios()
	{
		$clientes = Cliente::orderBy('nombre','asc')->get();
		$users = User::where('id_rol',2)->orderBy('nombre','asc')->get();
		return view('admin.reportes.actividad_usuarios',compact('clientes','users'));
	}
	// ajax/datatable
	public function search_actividad_usuarios(Request $request)
	{

		/*
		Consulta Medica
		Consulta enfermeria
		Ausentismo
		Comunicacion
		Documentacion

		Estudio complementario !

		Tareas livianas
		Tareas livianas > comunicación
		Tareas livianas > documentación
		*/

		$medicas = ConsultaMedica::select(
				'consultas_medicas.id_cliente',
				'consultas_medicas.id_nomina',
				'consultas_medicas.user',
				'consultas_medicas.created_at',
				DB::raw('"Consulta Médica" as actividad'),
				DB::raw('clientes.nombre as cliente_nombre'),
				DB::raw('nominas.nombre as trabajador_nombre'),
				'users.estado'
			)
			->join('clientes','clientes.id','consultas_medicas.id_cliente')
			->join('nominas','nominas.id','consultas_medicas.id_nomina')
			->leftJoin('users','users.nombre','consultas_medicas.user');


		$enfermerias = ConsultaEnfermeria::select(
			'consultas_enfermerias.id_cliente',
			'consultas_enfermerias.id_nomina',
			'consultas_enfermerias.user',
			'consultas_enfermerias.created_at',
			DB::raw('"Consulta Enfermería" as actividad'),
			DB::raw('clientes.nombre as cliente_nombre'),
			DB::raw('nominas.nombre as trabajador_nombre'),
			'users.estado'
		)
			->join('clientes','clientes.id','consultas_enfermerias.id_cliente')
			->join('nominas','nominas.id','consultas_enfermerias.id_nomina')
			->leftJoin('users','users.nombre','consultas_enfermerias.user');


		$ausentismos = Ausentismo::select(
			'ausentismos.id_cliente',
			'ausentismos.id_trabajador as id_nomina',
			'ausentismos.user',
			'ausentismos.created_at',
			DB::raw('"Ausentismo" as actividad'),
			DB::raw('clientes.nombre as cliente_nombre'),
			DB::raw('nominas.nombre as trabajador_nombre'),
			'users.estado'
		)
			->join('clientes','clientes.id','ausentismos.id_cliente')
			->join('nominas','nominas.id','ausentismos.id_trabajador')
			->leftJoin('users','users.nombre','ausentismos.user');

		$comunicaciones = Comunicacion::select(
			'ausentismos.id_cliente',
			'ausentismos.id_trabajador as id_nomina',
			DB::raw( 'IFNULL(comunicaciones.user,ausentismos.user) as user' ),
			'comunicaciones.created_at',
			DB::raw('"Comunicación Ausentismo" as actividad'),
			DB::raw('clientes.nombre as cliente_nombre'),
			DB::raw('nominas.nombre as trabajador_nombre'),
			'users.estado'
		)
			->join('ausentismos','ausentismos.id','comunicaciones.id_ausentismo')
			->join('clientes','clientes.id','ausentismos.id_cliente')
			->join('nominas','nominas.id','ausentismos.id_trabajador')
			->leftJoin('users','users.nombre','ausentismos.user');



		$documentaciones = AusentismoDocumentacion::select(
			'ausentismos.id_cliente',
			'ausentismos.id_trabajador as id_nomina',
			DB::raw( 'IFNULL(ausentismo_documentacion.user,ausentismos.user) as user' ),
			'ausentismo_documentacion.created_at',
			DB::raw('"Documentación Ausentismo" as actividad'),
			DB::raw('clientes.nombre as cliente_nombre'),
			DB::raw('nominas.nombre as trabajador_nombre'),
			'users.estado'
		)
			->join('ausentismos','ausentismos.id','ausentismo_documentacion.id_ausentismo')
			->join('clientes','clientes.id','ausentismos.id_cliente')
			->join('nominas','nominas.id','ausentismos.id_trabajador')
			->leftJoin('users','users.nombre','ausentismos.user');



		$preocupacionales = Preocupacional::select(
			'preocupacionales.id_cliente',
			'preocupacionales.id_nomina',
			'preocupacionales.user',
			'preocupacionales.created_at',
			DB::raw('"Estudio Complementario" as actividad'),
			DB::raw('clientes.nombre as cliente_nombre'),
			DB::raw('nominas.nombre as trabajador_nombre'),
			'users.estado'
		)
			->join('clientes','clientes.id','preocupacionales.id_cliente')
			->join('nominas','nominas.id','preocupacionales.id_nomina')
			->leftJoin('users','users.nombre','preocupacionales.user');




		$tareas_livianas = TareaLiviana::select(
			'tareas_livianas.id_cliente',
			'tareas_livianas.id_trabajador as id_nomina',
			'tareas_livianas.user',
			'tareas_livianas.created_at',
			DB::raw('"Tarea Adecuada" as actividad'),
			DB::raw('clientes.nombre as cliente_nombre'),
			DB::raw('nominas.nombre as trabajador_nombre'),
			'users.estado'
		)
			->join('clientes','clientes.id','tareas_livianas.id_cliente')
			->join('nominas','nominas.id','tareas_livianas.id_trabajador')
			->leftJoin('users','users.nombre','tareas_livianas.user');


		$comunicaciones_tareas_livianas = ComunicacionLiviana::select(
			'tareas_livianas.id_cliente',
			'tareas_livianas.id_trabajador as id_nomina',
			DB::raw( 'IFNULL(comunicaciones_livianas.user,tareas_livianas.user) as user' ),
			'comunicaciones_livianas.created_at',
			DB::raw('"Comunicación Tarea Adecuada" as actividad'),
			DB::raw('clientes.nombre as cliente_nombre'),
			DB::raw('nominas.nombre as trabajador_nombre'),
			'users.estado'
		)
			->join('tareas_livianas','tareas_livianas.id','comunicaciones_livianas.id_tarea_liviana')
			->join('clientes','clientes.id','tareas_livianas.id_cliente')
			->join('nominas','nominas.id','tareas_livianas.id_trabajador')
			->leftJoin('users','users.nombre','comunicaciones_livianas.user');


		$documentaciones_tareas_livianas = TareaLivianaDocumentacion::select(
			'tareas_livianas.id_cliente',
			'tareas_livianas.id_trabajador as id_nomina',
			DB::raw( 'IFNULL(tarea_liviana_documentacion.user,tareas_livianas.user) as user' ),
			'tarea_liviana_documentacion.created_at',
			DB::raw('"Documentación Tarea Adecuada" as actividad'),
			DB::raw('clientes.nombre as cliente_nombre'),
			DB::raw('nominas.nombre as trabajador_nombre'),
			'users.estado'
		)
			->join('tareas_livianas','tareas_livianas.id','tarea_liviana_documentacion.id_tarea_liviana')
			->join('clientes','clientes.id','tareas_livianas.id_cliente')
			->join('nominas','nominas.id','tareas_livianas.id_trabajador')
			->leftJoin('users','users.nombre','tarea_liviana_documentacion.user');


		DB::enableQueryLog();


		$query = DB::query()
			->fromSub(function($query) use(
				$medicas,
				$enfermerias,
				$ausentismos,
				$documentaciones,
				$comunicaciones,
				$preocupacionales,
				$tareas_livianas,
				$comunicaciones_tareas_livianas,
				$documentaciones_tareas_livianas
			){

				$query->select('*')
					->from($medicas)
					->union($enfermerias)
					->union($ausentismos)
					->union($documentaciones)
					->union($comunicaciones)
					->union($preocupacionales)
					->union($tareas_livianas)
					->union($comunicaciones_tareas_livianas)
					->union($documentaciones_tareas_livianas);

			},'uniones');


		/*$query = $medicas
			->union($enfermerias)
			->union($ausentismos)
			->union($comunicaciones)
			->union($documentaciones)
			->union($preocupacionales)
			->union($tareas_livianas)
			->union($comunicaciones_tareas_livianas)
			->union($documentaciones_tareas_livianas);*/



		$total_records = $query->count();


		if ($request->search) {
			$filtro = '%' . $request->search['value'] . '%';

			$query->where(function($query) use ($filtro){
				$query
					->where('actividad','like',$filtro)
					->orWhere('trabajador_nombre','like',$filtro);
			});
		}

		if($request->from_date) {
			$query->whereDate('created_at', '>=', Carbon::createFromFormat('d/m/Y', $request->from_date)->format('Y-m-d'));
		}
		if($request->to_date) {
			$query->whereDate('created_at', '<=', Carbon::createFromFormat('d/m/Y', $request->to_date)->format('Y-m-d'));
		}
		if($request->cliente){
			$query->where('id_cliente',$request->cliente);
		}
		if($request->user){
			$query->where('user',$request->user);
		}
		if($request->estado!==null){
			$query->where('estado',$request->estado);
		}

		$total_filtered = $query->count();


		if($request->order){
			$sort = $request->columns[$request->order[0]['column']]['name'];
			$dir  = $request->order[0]['dir'];
			$query->orderBy($sort,$dir);
		}else{
			$query->orderBy('fecha','desc');
		}



		$data = $query->skip($request->start)->take($request->length)->get()->toArray();
		foreach($data as $k=>$row){
			$data[$k]->created_at_formatted = Carbon::createFromFormat('Y-m-d H:i:s',$row->created_at)->format('d/m/Y H:i:s  \h\s.');
		}


		return [
			'draw'=>$request->draw,
			'recordsTotal'=>$total_records,
			'recordsFiltered'=>$total_filtered,
			'data'=>$data,
			'request'=>$request->all(),
			'query'=>DB::getQueryLog()
		];

	}
	public function exportar_actividad_usuarios(Request $request){

		$request->search = ['value' => null];
		$request->start = 0;
		$request->order = [
			[
				'dir'=>'desc',
				'column'=>2
			]
		];
		$request->columns = [
			['name'=>'user'],
			['name'=>'cliente_nombre'],
			['name'=>'created_at']
		];
		$request->length = 25000;


		$actividades = $this->search_actividad_usuarios($request)['data'];



		$now = Carbon::now();
		$file_name = 'actividades-'.$now->format('YmdHis').'.csv';

		$fp = fopen('php://memory', 'w');
		fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
		fputcsv($fp, [
				'Usuario',
				'Cliente',
				'Fecha',
				'Actividad',
				'Trabajador'
		], ';');

		foreach($actividades as $actividad){

			fputcsv($fp, [
				$actividad->user,
				$actividad->cliente_nombre,
				$actividad->created_at_formatted,
				$actividad->actividad,
				$actividad->trabajador_nombre
			], ';');
		}

		//dd($fp);
		fseek($fp, 0);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="'.$file_name.'";');
		fpassthru($fp);

		return;

	}



}
