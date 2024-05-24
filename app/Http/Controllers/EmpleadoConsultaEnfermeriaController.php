<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ConsultaEnfermeria;
use App\DiagnosticoConsulta;
use Illuminate\Support\Facades\DB;
///use App\ClienteUser;
use App\Http\Traits\Clientes;
use App\Nomina;
use App\StockMedicamento;
use App\ConsultaMedicacion;
use App\StockMedicamentoHistorial;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class EmpleadoConsultaEnfermeriaController extends Controller
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
		return view('empleados.consultas.enfermeria', compact('clientes','fecha_inicio','fecha_final'));
	}

	public function busqueda(Request $request)
	{
		$query = ConsultaEnfermeria::select(
			'nominas.nombre',
			'consultas_enfermerias.*',
			'diagnostico_consulta.nombre as diagnostico'
		)
		->join('nominas', 'consultas_enfermerias.id_nomina', 'nominas.id')
		->join('diagnostico_consulta', 'consultas_enfermerias.id_diagnostico_consulta', 'diagnostico_consulta.id')
		->where('nominas.id_cliente', auth()->user()->id_cliente_actual);

		$total = $query->count();

		/// Revisar

		if($request->from) $query->whereDate('consultas_enfermerias.fecha','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		if($request->to) $query->whereDate('consultas_enfermerias.fecha','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));

		if($request->search){
			$query->where(function($query) use($request){
				$filtro = '%'.$request->search['value'].'%';
				$query->where('nominas.nombre','like',$filtro)
					->orWhere('consultas_enfermerias.derivacion_consulta','like',$filtro)
					->orWhere('consultas_enfermerias.observaciones','like',$filtro)
					->orWhere('diagnostico_consulta.nombre','like',$filtro);
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
			'fichada_user'=>auth()->user()->fichada,
			'fichar_user'=>auth()->user()->fichar,
			'request'=>$request->all()
		];
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{

		$clientes = $this->getClientesUser();

		$diagnostico_consultas = DiagnosticoConsulta::orderBy('nombre', 'asc')->get();

		$stock_medicamentos = StockMedicamento::join('medicamentos', 'stock_medicamentos.id_medicamento', 'medicamentos.id')
		->join('clientes', 'stock_medicamentos.id_cliente', 'clientes.id')
		->where('id_cliente', auth()->user()->id_cliente_actual)
		->select('medicamentos.nombre', 'stock_medicamentos.stock', 'stock_medicamentos.id')
		->get();

		$nominas = Nomina::where('id_cliente', auth()->user()->id_cliente_actual)
		->where('estado', '=', 1)
		->orderBy('nombre', 'asc')
		->get();

		return view('empleados.consultas.enfermeria.create', compact('clientes', 'nominas', 'diagnostico_consultas', 'stock_medicamentos'));

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{

		if ($request->amerita_salida == null || $request->derivacion_consulta == null) {
			return back()->withInput($request->input())->with(
				'error',
				'Amerita salida y Derivacion consulta no pueden estar sin completar. Revisa los campos obligatorios (*)'
			);
		}

		// Si existen medicaciones se validan aqui
		$suministrados = [];
		if (isset($request->medicaciones) && !empty($request->medicaciones)) {
			foreach ($request->medicaciones as $key => $value) {
				$medicaciones = explode(",", $value);
				if ($medicaciones[1] !== '') {
					$suministrados[] = [
						'id_medicamento' => $medicaciones[0],
						'suministrados' => $medicaciones[1]
					];
				}
			}
		}

		if (!isset($request->tipo) || empty($request->tipo) || $request->tipo == '' || $request->tipo == null) {
			return back()->withInput($request->input())->with('error', 'Debes ingresar un diagnostico');
		}


		if (isset($request->fecha)) {
			$fecha = Carbon::createFromFormat('d/m/Y', $request->fecha);
		}

		if (!isset($fecha) || empty($fecha) || $fecha == '' || $fecha == null) {
			return back()->withInput($request->input())->with('error', 'Debes ingresar una fecha');
		}

		if (!isset($request->observaciones) || empty($request->observaciones) || $request->observaciones == '' || $request->observaciones == null) {
			return back()->withInput($request->input())->with('error', 'Debes completar el campo observaciones');
		}

		if (isset($request->peso) && !empty($request->peso) && !isset($request->altura)) {
			if ($request->peso == 0 || $request->peso < 0) {
				return back()->withInput($request->input())->with('error', 'En el campo peso vemos un valor inválido');
			}else {
				return back()->withInput($request->input())->with('error', 'Si completas el campo Peso debes complatar Altura');
			}
		}

		if (isset($request->altura) && !empty($request->altura) && !isset($request->peso)) {
			if ($request->altura == 0 || $request->altura < 0) {
				return back()->withInput($request->input())->with('error', 'En el campo altura vemos un valor inválido');
			}else {
				return back()->withInput($request->input())->with('error', 'Si completas el campo Altura debes complatar Peso');
			}
		}


		if (isset($suministrados) && !empty($suministrados)) {
			$todos_los_stocks_disponibles = 0;
			foreach ($suministrados as $value) {
				$stock_medicacion = StockMedicamento::where('id', intval($value['id_medicamento']))
				->where('id_cliente', auth()->user()->id_cliente_actual)
				->first();

				$stock_disponible = $stock_medicacion->stock - intval($value['suministrados']);

				if ($stock_disponible > 0) {
					$todos_los_stocks_disponibles++;
				}

			}
			if (count($suministrados) == $todos_los_stocks_disponibles) {
				$todos_los_stocks_disponibles = true;
			}else {
				$todos_los_stocks_disponibles = false;
				return back()->withInput($request->input())->with('error', 'No puedes suministrar mas medicamentos que los disponibles en el stock');
			}
		}



		//Guardar en base una Nueva Consulta
		$consulta = new ConsultaEnfermeria();
		$consulta->id_nomina = $request->nomina;

		if (isset($request->temperatura_auxiliar) && $request->temperatura_auxiliar != null) {
			$consulta->temperatura_auxiliar = $request->temperatura_auxiliar;
		}

		if (isset($request->fecha)) {
		$consulta->fecha = $fecha;
		}

		if (isset($request->peso) && !empty($request->peso) && isset($request->altura) && !empty($request->altura)) {
			$consulta->peso = $request->peso;
			$consulta->altura = $request->altura;
			$consulta->imc = $request->imc;
		}

		if (isset($request->tipo) && !empty($request->tipo)) {
			$consulta->id_diagnostico_consulta = $request->tipo;
		}

		if (isset($request->glucemia) && !empty($request->glucemia)) {
			$consulta->glucemia = $request->glucemia;
		}

		if (isset($request->saturacion_oxigeno) && !empty($request->saturacion_oxigeno)) {
			$consulta->saturacion_oxigeno = $request->saturacion_oxigeno;
		}

		if (isset($request->tension_arterial) && !empty($request->tension_arterial)) {
			$consulta->tension_arterial = $request->tension_arterial;
		}

		if (isset($request->frec_cardiaca) && !empty($request->frec_cardiaca)) {
			$consulta->frec_cardiaca = $request->frec_cardiaca;
		}

		$consulta->derivacion_consulta = $request->derivacion_consulta;
		$consulta->amerita_salida = $request->amerita_salida;
		$consulta->observaciones = $request->observaciones;
		$consulta->user = auth()->user()->nombre;
		$consulta->id_user = auth()->user()->id;
		$consulta->save();

		if (isset($todos_los_stocks_disponibles) && $todos_los_stocks_disponibles == true) {
			foreach ($suministrados as $value) {

				//Guardar en base
				$consulta_medicacion = new ConsultaMedicacion();
				$consulta_medicacion->id_consulta_enfermeria = $consulta->id;
				$consulta_medicacion->id_medicamento = $value['id_medicamento'];
				$consulta_medicacion->suministrados = $value['suministrados'];
				$consulta_medicacion->id_cliente = auth()->user()->id_cliente_actual;
				$consulta_medicacion->save();


				//Actualizar el Stock
				$stock_medicacion = StockMedicamento::where('id', $value['id_medicamento'])
				->where('id_cliente', auth()->user()->id_cliente_actual)
				->first();

				$stock_medicacion->suministrados = $stock_medicacion->suministrados + $value['suministrados'];
				$stock_medicacion->stock = $stock_medicacion->stock - $value['suministrados'];
				$stock_medicacion->save();


				// Actualizar la tabla Historial
				$historial_stock_medicamentos = new StockMedicamentoHistorial();
				$historial_stock_medicamentos->id_stock_medicamentos = $stock_medicacion->id;
				$historial_stock_medicamentos->suministrados = $value['suministrados'];
				$historial_stock_medicamentos->id_consulta_enfermeria = $consulta->id; // consulta de enfermeria
				if (isset($request->fecha)) {
					$historial_stock_medicamentos->fecha_ingreso = $fecha;
				}
				$historial_stock_medicamentos->save();

			}
		}

		return redirect('empleados/consultas/enfermeria')->with('success', 'Consulta de enfermería guardada con éxito');


	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{

		$consulta_enfermeria = ConsultaEnfermeria::join('nominas', 'consultas_enfermerias.id_nomina', 'nominas.id')
		->join('diagnostico_consulta', 'consultas_enfermerias.id_diagnostico_consulta', 'diagnostico_consulta.id')
		->where('consultas_enfermerias.id', $id)
		->where('nominas.id_cliente',auth()->user()->id_cliente_actual) //IMPORTANTE: comprobar que está consultando a trabajadores de la nómina del cliente actual
		->select('consultas_enfermerias.*', 'nominas.nombre', 'nominas.telefono', 'nominas.dni', 'nominas.estado', 'nominas.email', DB::raw('diagnostico_consulta.nombre diagnostico'))
		->first();

		$clientes = $this->getClientesUser();

		return view('empleados.consultas.enfermeria.show', compact('consulta_enfermeria', 'clientes'));

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
			//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
			//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
			//
	}

	public function clientes()
	{
		return ClienteUser::join('clientes', 'cliente_user.id_cliente', 'clientes.id')
			->where('cliente_user.id_user', '=', auth()->user()->id)
			->select('clientes.nombre', 'clientes.id')
			->get();
	}



	public function tipo(Request $request)
	{
			$validatedData = $request->validate([
				'nombre' => 'required|string'
			]);

			//Guardar en base
			$diagnostico = new DiagnosticoConsulta();
			$diagnostico->nombre = $request->nombre;
			$diagnostico->save();

			return back()->with('success', 'Tipo de diagnóstico creado con éxito');
	}


	public function tipo_destroy($id_tipo)
	{

		$diagnostico_consulta = ConsultaEnfermeria::where('id_diagnostico_consulta', $id_tipo)->get();

		if (!empty($diagnostico_consulta) && count($diagnostico_consulta) > 0) {
			return back()->with('error', 'Existen consultas de enfermería creadas con este tipo de diagnostico. No puedes eliminarlo');
		}

			//Eliminar en base
			$tipo_diagnostico_consulta = DiagnosticoConsulta::find($id_tipo)->delete();
			return back()->with('success', 'Tipo de diagnostico de consulta eliminado correctamente');
	}


	public function exportar(Request $request){


		if (!auth()->user()->id_cliente_actual) {
			return back()->with('error', 'Debes fichar para utilizar esta funcionalidad.');
		}

		$query = ConsultaEnfermeria::select('consultas_enfermerias.*', 'nominas.nombre', 'nominas.email','diagnostico_consulta.nombre as diagnostico')
		->join('nominas','nominas.id','consultas_enfermerias.id_nomina')
		->join('diagnostico_consulta','diagnostico_consulta.id','consultas_enfermerias.id_diagnostico_consulta')
		->where('nominas.id_cliente',auth()->user()->id_cliente_actual)->orderBy('consultas_enfermerias.fecha', 'desc');

		if($request->from) $query->whereDate('consultas_enfermerias.fecha','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		if($request->to) $query->whereDate('consultas_enfermerias.fecha','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));

		$consultas = $query->get();

		if (!$consultas) {
			return back()->with('error', 'No se han encontrado consultas.');
		}

		$hoy = Carbon::now();
		$file_name = 'consultas-medicas-'.$hoy->format('YmdHis').'.csv';

		///dd($consultas);

		$fp = fopen('php://memory', 'w');
		fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
		fputcsv($fp,[
			'Trabajador',
			'Email',
			'Fecha',
			'Diagnóstico',
			'Derivación',
			'Amerita Salida',
			'Peso',
			'Altura',
			'IMC',
			'Glucemia',
			'Saturación Oxígeno',
			'Tensión Arterial',
			'Frec. Cardíaca',
			'Anamnesis',
			'Tratamiento',
			'Observaciones',
		],';');

		foreach($consultas as $consulta){

			fputcsv($fp,[
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
				$consulta->anamnesis,
				$consulta->tratamiento,
				$consulta->observaciones
			],';');
		}
		fseek($fp, 0);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="'.$file_name.'";');
		fpassthru($fp);


		return;

	}



}
