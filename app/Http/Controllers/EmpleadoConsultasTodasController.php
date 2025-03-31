<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\Clientes;
use App\ConsultaMedica;
use App\ConsultaEnfermeria;
use App\ConsultaNutricional;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class EmpleadoConsultasTodasController extends Controller
{
	use Clientes;


	public function index(Request $request)
	{

		$ahora = Carbon::now();

		switch ($request->filtro) {
			case 'mes':
				$fecha_inicio = $ahora->format('01/m/Y');
				$fecha_final = $ahora->format('d/m/Y');
				break;

			case 'hoy':
				$fecha_inicio = $ahora->format('d/m/Y');
				$fecha_final = $ahora->format('d/m/Y');
				break;

			default:
				$fecha_inicio = false;
				$fecha_final = false;
				break;
		}

		$clientes = $this->getClientesUser();
		return view('empleados.consultas.todas', compact('clientes','fecha_inicio','fecha_final'));
	}


	public function busqueda(Request $request,$extended=false)
	{

		// Médicas
		$medicas = ConsultaMedica::select(
			'nominas.nombre',
			'consultas_medicas.id',
			'consultas_medicas.id_nomina',
			'consultas_medicas.id_diagnostico_consulta',
			'consultas_medicas.fecha',
			'consultas_medicas.derivacion_consulta',
			'consultas_medicas.user',
			'diagnostico_consulta.nombre as diagnostico',
			DB::raw('"Médica" as tipo') // Agregamos un campo tipo para identificar consultas médicas
		)
		->with('trabajador')
		->join('nominas', 'consultas_medicas.id_nomina', 'nominas.id')
		->join('diagnostico_consulta', 'consultas_medicas.id_diagnostico_consulta', 'diagnostico_consulta.id')
		->where('consultas_medicas.id_cliente', auth()->user()->id_cliente_actual);

		if($extended){
			$medicas->addSelect(
				'consultas_medicas.amerita_salida',
				'consultas_medicas.tratamiento',
				'consultas_medicas.observaciones',
				'consultas_medicas.peso',
				'consultas_medicas.altura',
				'consultas_medicas.imc',
				'consultas_medicas.glucemia',
				'consultas_medicas.saturacion_oxigeno',
				'consultas_medicas.tension_arterial',
				'consultas_medicas.frec_cardiaca'
			);
		}

		// Enfermerías
		$enfermerias = ConsultaEnfermeria::select(
			'nominas.nombre',
			'consultas_enfermerias.id',
			'consultas_enfermerias.id_nomina',
			'consultas_enfermerias.id_diagnostico_consulta',
			'consultas_enfermerias.fecha',
			'consultas_enfermerias.derivacion_consulta',
			'consultas_enfermerias.user',
			'diagnostico_consulta.nombre as diagnostico',
			DB::raw('"Enfermería" as tipo') // Agregamos un campo tipo para identificar consultas de enfermería
		)
		->with('trabajador')
		->join('nominas', 'consultas_enfermerias.id_nomina', 'nominas.id')
		->join('diagnostico_consulta', 'consultas_enfermerias.id_diagnostico_consulta', 'diagnostico_consulta.id')
		->where('consultas_enfermerias.id_cliente', auth()->user()->id_cliente_actual);

		if($extended){
			$enfermerias->addSelect(
				'consultas_enfermerias.amerita_salida',
				DB::raw('NULL as tratamiento'),
				'consultas_enfermerias.observaciones',
				'consultas_enfermerias.peso',
				'consultas_enfermerias.altura',
				'consultas_enfermerias.imc',
				'consultas_enfermerias.glucemia',
				'consultas_enfermerias.saturacion_oxigeno',
				'consultas_enfermerias.tension_arterial',
				'consultas_enfermerias.frec_cardiaca'
			);
		}

		// Nutricional
		$nutricionales = ConsultaNutricional::select(
			'nominas.nombre',
			'consultas_nutricionales.id',
			'consultas_nutricionales.id_nomina',
			DB::raw('NULL as id_diagnostico_consulta'), // Ajuste para que coincida con otras consultas
			'consultas_nutricionales.fecha_atencion as fecha',
			DB::raw('NULL as derivacion_consulta'),
			DB::raw('NULL as user'),
			DB::raw('NULL as diagnostico'),
			DB::raw('"Nutricional" as tipo'),
		)
		->with('trabajador')
		->join('nominas', 'consultas_nutricionales.id_nomina', 'nominas.id')
		->where('consultas_nutricionales.id_cliente', auth()->user()->id_cliente_actual);

		if($extended){
			$nutricionales->addSelect(
				DB::raw('NULL as amerita_salida'),
				DB::raw('NULL as tratamiento'),
				DB::raw('NULL as observaciones'),
				DB::raw('NULL as peso'),
				DB::raw('NULL as altura'),
				DB::raw('NULL as imc'),
				DB::raw('NULL as glucemia'),
				DB::raw('NULL as saturacion_oxigeno'),
				DB::raw('NULL as tension_arterial'),
				DB::raw('NULL as frec_cardiaca')
			);
		}



		if ($request->search) {
			$filtro = '%' . $request->search . '%';

			$medicas->where(function ($query) use ($filtro) {
				$query->where('nominas.nombre', 'like', $filtro)
					->orWhere('nominas.dni', 'like', $filtro)
					->orWhere('consultas_medicas.derivacion_consulta', 'like', $filtro)
					->orWhere('consultas_medicas.tratamiento', 'like', $filtro)
					->orWhere('consultas_medicas.observaciones', 'like', $filtro)
					->orWhere('diagnostico_consulta.nombre', 'like', $filtro);
			});

			$enfermerias->where(function ($query) use ($filtro) {
				$query->where('nominas.nombre', 'like', $filtro)
					->orWhere('consultas_enfermerias.derivacion_consulta', 'like', $filtro)
					->orWhere('diagnostico_consulta.nombre', 'like', $filtro);
			});

			$nutricionales->where(function ($query) use ($filtro) {
				$query->where('nominas.nombre', 'like', $filtro);
			});

		}

		if ($request->from) {
			$medicas->whereDate('consultas_medicas.fecha', '>=', Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
			$enfermerias->whereDate('consultas_enfermerias.fecha', '>=', Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
			$nutricionales->whereDate('consultas_nutricionales.fecha_atencion', '>=', Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		}

		if ($request->to) {
			$medicas->whereDate('consultas_medicas.fecha', '<=', Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
			$enfermerias->whereDate('consultas_enfermerias.fecha', '<=', Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
			$nutricionales->whereDate('consultas_nutricionales.fecha_atencion', '<=', Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
		}


		if($request->estado!=''){
			$medicas->whereHas('trabajador',function($query) use ($request){
				$query->where('estado',$request->estado);
			});
			$enfermerias->whereHas('trabajador',function($query) use ($request){
				$query->where('estado',$request->estado);
			});
			$nutricionales->whereHas('trabajador',function($query) use ($request){
				$query->where('estado',$request->estado);
			});
		}
		if($request->dni){
			$medicas->whereHas('trabajador',function($query) use ($request){
				$query->where('dni',$request->dni);
			});
			$enfermerias->whereHas('trabajador',function($query) use ($request){
				$query->where('dni',$request->dni);
			});
			$nutricionales->whereHas('trabajador',function($query) use ($request){
				$query->where('dni',$request->dni);
			});
		}

		$query = $medicas->union($enfermerias)->union($nutricionales);
		$total = $query->count();

		if($request->order){
			$sort = $request->columns[$request->order[0]['column']]['name'];
			$dir  = $request->order[0]['dir'];
			$query->orderBy($sort,$dir);
		}else{
			$query->orderBy('fecha','desc');
		}




		/*if ($request->from) {
			$enfermerias->whereDate('consultas_enfermerias.fecha', '>=', Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		}
		if ($request->to) {
			$enfermerias->whereDate('consultas_enfermerias.fecha', '<=', Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
		}*/

		/*if ($request->search) {
			$filtro = '%' . $request->search['value'] . '%';
			$enfermerias->where(function ($query) use ($filtro) {
				$query->where('nominas.nombre', 'like', $filtro)
					->orWhere('consultas_enfermerias.derivacion_consulta', 'like', $filtro)
					->orWhere('diagnostico_consulta.nombre', 'like', $filtro);
			});
		}

		if($request->order){
			$sort = $request->columns[$request->order[0]['column']]['name'];
			$dir  = $request->order[0]['dir'];
			$enfermerias->orderBy($sort,$dir);
		}*/


		//$totalMedicas = $medicas->count();
		//$totalEnfermerias = $enfermerias->count();
		//$total = $totalMedicas + $totalEnfermerias;

		$total_filtered = $query->count();

		//$dataMedicas = $medicas->skip($request->start)->take($request->length)->get();
		//$dataEnfermerias = $enfermerias->skip($request->start)->take($request->length)->get();

		//$data = $dataMedicas->concat($dataEnfermerias);


		return [
			'draw' => $request->draw,
			'recordsTotal' => $total,
			'recordsFiltered' => $total_filtered,
			'data' => $query->skip($request->start)->take($request->length)->get(),
			'fichada_user' => auth()->user()->fichada,
			'fichar_user' => auth()->user()->fichar,
			'request' => $request->all()
		];
	}


	public function exportar(Request $request) {
		if (!auth()->user()->id_cliente_actual) {
			return back()->with('error', 'Debes trabajar para algun cliente para utilizar esta funcionalidad');
		}

		$request->draw = 1;
		$request->start = 0;
		$request->length = 5000;
		$request->merge([
			'draw' => 1,
			'start' => 0,
			'length' => 5000
		]);


		$response = $this->busqueda($request,true);

		/*$queryMedicas = ConsultaMedica::select(
				'nominas.nombre',
				'nominas.email',
				'consultas_medicas.id',
				'consultas_medicas.id_nomina',
				'consultas_medicas.id_diagnostico_consulta',
				'consultas_medicas.fecha',
				'consultas_medicas.derivacion_consulta',

				'consultas_medicas.amerita_salida',
				'consultas_medicas.peso',
				'consultas_medicas.altura',
				'consultas_medicas.imc',
				'consultas_medicas.glucemia',
				'consultas_medicas.saturacion_oxigeno',
				'consultas_medicas.tension_arterial',
				'consultas_medicas.frec_cardiaca',
				'consultas_medicas.tratamiento',
				'consultas_medicas.observaciones',

				'diagnostico_consulta.nombre as diagnóstico',
				DB::raw('"Médica" as tipo') // Agregamos un campo tipo para identificar consultas médicas
			)
			->join('nominas', 'nominas.id', 'consultas_medicas.id_nomina')
			->join('diagnostico_consulta', 'diagnostico_consulta.id', 'consultas_medicas.id_diagnostico_consulta')
			->where('nominas.id_cliente', auth()->user()->id_cliente_actual);

		$queryEnfermerias = ConsultaEnfermeria::select(
			'nominas.nombre',
			'nominas.email',
			'consultas_enfermerias.id',
			'consultas_enfermerias.id_nomina',
			'consultas_enfermerias.id_diagnostico_consulta',
			'consultas_enfermerias.fecha',
			'consultas_enfermerias.derivacion_consulta',

			'consultas_enfermerias.amerita_salida',
			'consultas_enfermerias.peso',
			'consultas_enfermerias.altura',
			'consultas_enfermerias.imc',
			'consultas_enfermerias.glucemia',
			'consultas_enfermerias.saturacion_oxigeno',
			'consultas_enfermerias.tension_arterial',
			'consultas_enfermerias.frec_cardiaca',
			'consultas_enfermerias.observaciones',
			DB::raw('"" as tratamiento'),

			'diagnostico_consulta.nombre as diagnóstico',
			DB::raw('"Enfermería" as tipo') // Agregamos un campo tipo para identificar consultas de enfermería
		)
			->join('nominas', 'nominas.id', 'consultas_enfermerias.id_nomina')
			->join('diagnostico_consulta', 'diagnostico_consulta.id', 'consultas_enfermerias.id_diagnostico_consulta')
			->where('nominas.id_cliente', auth()->user()->id_cliente_actual);

		if ($request->from) {
			$queryMedicas->whereDate('consultas_medicas.fecha', '>=', Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
			$queryEnfermerias->whereDate('consultas_enfermerias.fecha', '>=', Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		}
		if ($request->to) {
			$queryMedicas->whereDate('consultas_medicas.fecha', '<=', Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
			$queryEnfermerias->whereDate('consultas_enfermerias.fecha', '<=', Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
		}

		$query = $queryMedicas->union($queryEnfermerias);
		$total = $query->count();

		$query->orderBy('fecha','desc');

		$consultas = $query->get();*/
		if( !$response['data'] ) {
			return back()->with('error', 'No se encontraron consultas');
		}

		//dd($response['data'][0]);

		$hoy = Carbon::now();
		$file_name = 'consultas-' . $hoy->format('YmdHis') . '.csv';

		$fp = fopen('php://memory', 'w');
		fprintf($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
		fputcsv($fp, [
			'Tipo',
			'Trabajador',
			'CUIL',
			'Fecha',
			'Diagnóstico',
			'Derivación',

			'Amerita Salida',
			'Tratamiento',
			'Observaciones',
			'Peso',
			'Altura',
			'IMC',
			'Glucemia',
			'Saturación Oxígeno',
			'Tensión Arterial',
			'Frec. Cardíaca',
		], ';');

		foreach ($response['data'] as $consulta) {

			//$fecha = Carbon::createFromFormat('d/m/Y',$consulta->fecha);

			fputcsv($fp, [
				$consulta->tipo,
				$consulta->nombre,
				$consulta->trabajador->email,
				$consulta->fecha->format('d/m/Y'),
				$consulta->diagnostico,
				$consulta->derivacion_consulta,

				($consulta->amerita_salida ? 'Si' : 'No'),
				str_replace(["\r", "\n"],' ',$consulta->tratamiento), //X
				str_replace(["\r", "\n"],' ',$consulta->observaciones),
				$consulta->peso,
				$consulta->altura,
				$consulta->imc,
				$consulta->glucemia,
				$consulta->saturacion_oxigeno,
				$consulta->tension_arterial,
				$consulta->frec_cardiaca

			], ';');
		}

		/*foreach ($consultasEnfermerias as $consulta) {
			fputcsv($fp, [
				'Enfermeria',
				$consulta->nombre,
				$consulta->email,
				$consulta->fecha,
				$consulta->diagnostico,
				$consulta->derivacion_consulta,
				($consulta->amerita_salida ? 'Si' : 'No'),
				$consulta->peso,
				$consulta->altura,
				$consulta->imc,
				$consulta->glucemia,
				$consulta->saturacion_oxigeno,
				$consulta->tension_arterial,
				$consulta->frec_cardiaca,
				$consulta->tratamiento,
				$consulta->observaciones
			], ';');
		}*/

		fseek($fp, 0);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="' . $file_name . '";');
		fpassthru($fp);

		return;
	}


}
