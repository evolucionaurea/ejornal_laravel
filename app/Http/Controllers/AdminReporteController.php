<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\DB;
use App\Fichada;
use App\FichadaNueva;
use App\User;
use App\Nomina;
use App\AusentismoTipo;
use App\AusentismoDocumentacion;
use Carbon\Carbon;
use DateTime;
use App\Ausentismo;
use App\ConsultaMedica;
use App\ConsultaEnfermeria;
use App\Comunicacion;

class AdminReporteController extends Controller
{

	// public function reportes_fichadas()
	// {
	//   return view('admin.reportes.fichadas');
	// }

	public function reportes_fichadas_nuevas()
	{
		return view('admin.reportes.fichadas');
	}
	public function fichadas_ajax(Request $request)
	{

		$filtro = '%'.$request->search['value'].'%';
		$query = FichadaNueva::selectRaw('
				fichadas_nuevas.*,
				users.nombre user_nombre,
				clientes.nombre cliente_nombre
			')
			->join('users','users.id','fichadas_nuevas.id_user')
			->join('clientes','clientes.id','fichadas_nuevas.id_cliente')
			->where(function($query) use ($request) {
				$filtro = '%'.$request->search['value'].'%';
				$query
					->where('users.nombre','like',$filtro)
					->orWhere('clientes.nombre','like',$filtro);
			});

		if($request->from) $query->whereDate('ingreso','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		if($request->to) $query->whereDate('egreso','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));

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
			'request'=>$request->all()
		];

	}


	public function reportes_ausentismos()
	{
		$tipos = AusentismoTipo::get();
		return view('admin.reportes.ausentismos',compact('tipos'));
	}
	public function ausentismos_ajax(Request $request)
	{

		$filtro = '%'.$request->search['value'].'%';
		$query = Ausentismo::selectRaw(
				'ausentismos.*,
				nominas.nombre trabajador_nombre,
				clientes.nombre cliente_nombre,
				ausentismo_tipo.nombre ausentismo_tipo_nombre,
				(DATEDIFF(IFNULL(fecha_regreso_trabajar,DATE(NOW())),fecha_inicio)) as dias_ausente'
			)
			->join('nominas','nominas.id','ausentismos.id_trabajador')
			->join('ausentismo_tipo','ausentismo_tipo.id','ausentismos.id_tipo')
			->join('clientes','clientes.id','nominas.id_cliente')
			->where(function($query) use ($request) {
				$filtro = '%'.$request->search['value'].'%';
				$query
					->where('nominas.nombre','like',$filtro)
					->orWhere('clientes.nombre','like',$filtro);
			});

		if($request->from) $query->whereDate('fecha_inicio','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		if($request->to) $query->whereDate('fecha_final','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
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
			'request'=>$request->all()
		];

	}

	public function reportes_certificaciones()
	{
		return view('admin.reportes.certificaciones');
	}


	public function reportes_consultas()
	{
		return view('admin.reportes.consultas');
	}

	public function reportes_comunicaciones()
	{
		return view('admin.reportes.comunicaciones');
	}

	// public function fichadas()
	// {
	//
	//   $results =  Fichada::join('users', 'fichadas.id_user', 'users.id')
	//   ->join('clientes', 'fichadas.id_cliente', 'clientes.id')
	//   ->select('fichadas.*', DB::raw('clientes.nombre cliente'), DB::raw('users.nombre user'))
	//   ->orderBy('fichadas.id_user', 'desc')
	//   ->orderBy('fichadas.created_at', 'desc')
	//   ->get();
	//
	//   $fichadas = [];
	//
	//   $modelo = 'App\Fichada';
	//   foreach ($results as $resultado) {
	//     $audits_fichadas = DB::table('audits')->where('auditable_type', $modelo)->get();
	//       if (!empty($audits_fichadas) && count($audits_fichadas) > 0) {
	//       foreach ($audits_fichadas as $audit) {
	//         if ($resultado->id == json_decode($audit->new_values)->id) {
	//           $resultado['ip'] = $audit->ip_address;
	//         }
	//       }
	//     }
	//   }
	//
	//   foreach ($results as $key => $result) {
	//
	//       $egreso_hallado = null;
	//       $ingreso_hallago = null;
	//
	//       if ($result->horario_ingreso != null) {
	//         $ingreso_hallago = $result->created_at;
	//         if (isset($results[$key-1]->id_user) && $results[$key-1]->id_user == $result->id_user) {
	//           // Cargar el egreso
	//           $egreso_hallado = $results[$key-1]->created_at;
	//         }else {
	//           $egreso_hallado = null;
	//         }
	//
	//         $fecha_ingreso = Carbon::createFromFormat('Y-m-d H:i:s', $ingreso_hallago)->format('d-m-Y H:i:s');
	//
	//         if ($egreso_hallado != null) {
	//           $fecha_egreso = Carbon::createFromFormat('Y-m-d H:i:s', $egreso_hallado)->format('d-m-Y H:i:s');
	//           $f_ingreso = new DateTime($result->created_at);
	//           $f_egreso = new DateTime($egreso_hallado);
	//           $time = $f_ingreso->diff($f_egreso);
	//           $tiempo_dedicado = $time->days . ' dias ' . $time->format('%H horas %i minutos %s segundos');
	//         }
	//
	//         $fichadas[] = [
	//           'id' => $result->id,
	//           'fecha_actual' => $result->fecha_actual,
	//           'created_at' => $result->created_at,
	//           'cliente' => $result->cliente,
	//           'id_user' => $result->id_user,
	//           'user' => $result->user,
	//           'tiempo_dedicado' => (isset($tiempo_dedicado) && !empty($tiempo_dedicado)) ? $tiempo_dedicado : 'Aún trabajando',
	//           'fecha_ingreso' => $fecha_ingreso,
	//           'fecha_egreso' => ($egreso_hallado != null) ? $fecha_egreso : 'Aún trabajando',
	//           'ip' => $result->ip
	//         ];
	//
	//       }
	//
	//
	//     }
	//
	//   return $fichadas;
	// }



	public function fichadas_nuevas()
	{

		$fichadas =  FichadaNueva::join('users', 'fichadas_nuevas.id_user', 'users.id')
		->join('clientes', 'fichadas_nuevas.id_cliente', 'clientes.id')
		->select('fichadas_nuevas.*', DB::raw('clientes.nombre cliente'), DB::raw('users.nombre user'))
		->get();

		return $fichadas;
	}



	public function ausentismos()
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
	}

	public function certificaciones()
	{
		$certificaciones = $this->ausentismos();
		return $certificaciones;
	}



	public function consultas_medicas()
	{
		$medicas = ConsultaMedica::join('nominas', 'consultas_medicas.id_nomina', 'nominas.id')
		->join('diagnostico_consulta', 'consultas_medicas.id_diagnostico_consulta', 'diagnostico_consulta.id')
		->join('clientes', 'nominas.id_cliente', 'clientes.id')
		->select('nominas.nombre', 'consultas_medicas.fecha', 'consultas_medicas.peso', 'consultas_medicas.altura',
		'consultas_medicas.derivacion_consulta', 'consultas_medicas.temperatura_auxiliar', DB::raw('diagnostico_consulta.nombre diagnostico'),
		DB::raw('clientes.nombre cliente'))
		->orderBy('consultas_medicas.fecha', 'desc')
		->get();

		return $medicas;
	}

	public function consultas_enfermeria()
	{
		$enfermerias = ConsultaEnfermeria::join('nominas', 'consultas_enfermerias.id_nomina', 'nominas.id')
		->join('diagnostico_consulta', 'consultas_enfermerias.id_diagnostico_consulta', 'diagnostico_consulta.id')
		->join('clientes', 'nominas.id_cliente', 'clientes.id')
		->select('nominas.nombre', 'consultas_enfermerias.fecha', 'consultas_enfermerias.peso', 'consultas_enfermerias.altura',
		'consultas_enfermerias.derivacion_consulta', 'consultas_enfermerias.temperatura_auxiliar', DB::raw('diagnostico_consulta.nombre diagnostico'),
		DB::raw('clientes.nombre cliente'))
		->orderBy('consultas_enfermerias.fecha', 'desc')
		->get();

		return $enfermerias;
	}

	public function comunicaciones()
	{
		$comunicaciones = Comunicacion::join('ausentismos', 'comunicaciones.id_ausentismo', 'ausentismos.id')
		->join('tipo_comunicacion', 'comunicaciones.id_tipo', 'tipo_comunicacion.id')
		->join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
		->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
		->join('clientes', 'nominas.id_cliente', 'clientes.id')
		->select('nominas.nombre', DB::raw('ausentismo_tipo.nombre tipo_ausentismo'),
		DB::raw('tipo_comunicacion.nombre tipo_comunicacion'), 'comunicaciones.user', 'comunicaciones.descripcion',
		'comunicaciones.created_at', DB::raw('clientes.nombre cliente'))
		->orderBy('created_at', 'desc')
		->get();

		return $comunicaciones;
	}


	public function descargar_documentacion($id)
	{
		$ausentismo_documentacion = AusentismoDocumentacion::find($id);
		$ruta = storage_path("app/documentacion_ausentismo/{$ausentismo_documentacion->id}/{$ausentismo_documentacion->hash_archivo}");
		return response()->download($ruta);
		return back();
	}


	// public function filtrarFichadas(Request $request)
	// {
	//   if ($request->ajax()) {
	//
	//   $fichadas = $this->fichadas();
	//   $fichadas_filtradas = [];
	//   $fecha_inicio = new DateTime($request->input('fichadas_desde'));
	//   $fecha_final = new DateTime($request->input('fichadas_hasta'));
	//
	//   foreach ($fichadas as $fichada) {
	//   $fichada_inicio = new DateTime($fichada['fecha_ingreso']);
	//   if ($fichada['fecha_egreso'] == 'Aún trabajando') {
	//     $fichada_final = false;
	//   }else {
	//     $fichada_final = new DateTime($fichada['fecha_egreso']);
	//   }
	//   if ($fichada_final != false) {
	//     if ($fichada_inicio >= $fecha_inicio && $fichada_inicio <= $fecha_final
	//     && $fichada_final >= $fichada_inicio && $fichada_final <= $fecha_final) {
	//       $fichadas_filtradas[] = $fichada;
	//     }
	//   }else {
	//     if ($fichada_inicio >= $fecha_inicio && $fichada_inicio <= $fecha_final) {
	//       $fichadas_filtradas[] = $fichada;
	//     }
	//   }
	//   }
	//
	//   return $fichadas_filtradas;
	//   }
	//
	// }


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


	public function filtrarAusentismos(Request $request)
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

	}


	public function descargar_archivo($id)
	{
		$ausentismo_documentacion = AusentismoDocumentacion::find($id);
		$ruta = storage_path("app/documentacion_ausentismo/{$ausentismo_documentacion->id}/{$ausentismo_documentacion->hash_archivo}");
		return response()->download($ruta);
		return back();
	}


	public function filtrarCertificaciones(Request $request)
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

	}



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



}
