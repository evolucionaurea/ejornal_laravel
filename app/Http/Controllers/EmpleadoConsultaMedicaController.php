<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ConsultaMedica;
use App\DiagnosticoConsulta;
use Illuminate\Support\Facades\DB;
//use App\ClienteUser;
use App\Http\Traits\Clientes;
use App\Nomina;
use App\StockMedicamento;
use App\ConsultaMedicacion;
use App\StockMedicamentoHistorial;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Input;

class EmpleadoConsultaMedicaController extends Controller
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
		return view('empleados.consultas.medicas', compact('clientes','fecha_inicio','fecha_final'));
	}

	public function busqueda(Request $request)
	{

		$query = ConsultaMedica::select(
			'nominas.nombre',
			'consultas_medicas.*',
			'diagnostico_consulta.nombre as diagnostico'
		)
		->join('nominas', 'consultas_medicas.id_nomina', 'nominas.id')
		->join('diagnostico_consulta', 'consultas_medicas.id_diagnostico_consulta', 'diagnostico_consulta.id')
		->where('nominas.id_cliente', auth()->user()->id_cliente_actual);

		$total = $query->count();

		if($request->from) $query->whereDate('consultas_medicas.fecha','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		if($request->to) $query->whereDate('consultas_medicas.fecha','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));

		//dd($request->search['value']);

		if($request->search){
			$query->where(function($query) use($request){
				$filtro = '%'.$request->search['value'].'%';
				$query->where('nominas.nombre','like',$filtro)
					->orWhere('consultas_medicas.derivacion_consulta','like',$filtro)
					->orWhere('consultas_medicas.tratamiento','like',$filtro)
					->orWhere('consultas_medicas.observaciones','like',$filtro)
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

		return view('empleados.consultas.medicas.create', compact('clientes', 'nominas', 'diagnostico_consultas', 'stock_medicamentos'));

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
				'Amerita salida y Derivación consulta no pueden estar sin completar. Revisa los campos obligatorios (*)'
			);
		}

		// Si existen medicaciones se validan aqui
		$suministrados = [];
		if (isset($request->medicaciones) && !empty($request->medicaciones)) {
			foreach ($request->medicaciones as $key => $value) {
				$medicaciones = explode(",", $value);
				$suministrados[] = [
					'id_medicamento' => $medicaciones[0],
					'suministrados' => (int) $medicaciones[1]
				];
			}
		}

		if (!$request->tipo) {
			return back()->withInput($request->input())->with('error', 'Debes ingresar un diagnóstico');
		}

		if ($request->fecha) {
			$fecha = Carbon::createFromFormat('d/m/Y', $request->fecha)->startOfDay();
			$hoy = Carbon::today()->startOfDay();
			$hace30Dias = Carbon::today()->subDays(30);

			if ($fecha->lt($hace30Dias) || $fecha->gt($hoy)) {
				return back()->withInput($request->input())->with('error', 'La fecha debe ser igual a hoy o hasta 30 días hacia atrás.');
			}
		}else{
			return back()->withInput($request->input())->with('error', 'Debes ingresar una fecha');
		}


		if (!$request->tratamiento) {
			return back()->withInput($request->input())->with('error', 'Debes completar el campo tratamiento');
		}

		if (!$request->observaciones) {
			return back()->withInput($request->input())->with('error', 'Debes completar el campo observaciones');
		}

		if (isset($request->peso) && !empty($request->peso) && !isset($request->altura)) {
			if ($request->peso == 0 || $request->peso < 0) {
				return back()->withInput($request->input())->with('error', 'En el campo peso vemos un valor inválido');
			}else {
				return back()->withInput($request->input())->with('error', 'Si completas el campo Peso debes completar Altura');
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
				return back()->withInput($request->input())->with('error', 'No puedes suministrar más medicamentos que los disponibles en el stock');
			}
		}


		//Guardar en base una Nueva Consulta
		$consulta = new ConsultaMedica();
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

		if (isset($request->anamnesis) && !empty($request->anamnesis)) {
			$consulta->anamnesis = $request->anamnesis;
		}

		if (isset($request->tratamiento) && !empty($request->tratamiento)) {
			$consulta->tratamiento = $request->tratamiento;
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
				$consulta_medicacion->id_consulta_medica = $consulta->id;
				$consulta_medicacion->id_medicamento = $value['id_medicamento'];
				$consulta_medicacion->suministrados = intval($value['suministrados']);
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
				$historial_stock_medicamentos->id_consulta_medica = $consulta->id; // consulta medica
				if (isset($request->fecha)) {
					$historial_stock_medicamentos->fecha_ingreso = $fecha;
				}
				$historial_stock_medicamentos->save();

			}
		}

		if($request->amerita_salida=='1'){
			$consulta = $request->toArray();
			$consulta['consulta_tipo'] = 'Médica';
			$consulta['fecha_final'] = $fecha->addDays(1)->format('d/m/Y');
			return redirect('empleados/ausentismos/create')->with([
				'consulta'=>$consulta,
				'consulta_success'=>'La consulta médica fue guardada con éxito. Al indicar que amerita salida deberás crear el registro de ausentismo.'
			]);
		}

		return redirect('empleados/consultas/medicas')->with('success','Consulta médica guardada con éxito');


	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$consulta_medica = ConsultaMedica::join('nominas', 'consultas_medicas.id_nomina', 'nominas.id')
		->join('diagnostico_consulta', 'consultas_medicas.id_diagnostico_consulta', 'diagnostico_consulta.id')
		->where('consultas_medicas.id', $id)
		->where('nominas.id_cliente',auth()->user()->id_cliente_actual) //IMPORTANTE: comprobar que está consultando a trabajadores de la nómina del cliente actual
		->select('consultas_medicas.*', 'nominas.nombre', 'nominas.telefono', 'nominas.dni', 'nominas.estado',
		'nominas.email', DB::raw('diagnostico_consulta.nombre diagnostico'))
		->first();

		$clientes = $this->getClientesUser();

		return view('empleados.consultas.medicas.show', compact('consulta_medica', 'clientes'));
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

		$diagnostico_consulta = ConsultaMedica::where('id_diagnostico_consulta', $id_tipo)->get();

		if (!empty($diagnostico_consulta) && count($diagnostico_consulta) > 0) {
			return back()->with('error', 'Existen consultas medicas creadas con este tipo de diagnostico. No puedes eliminarlo');
		}

			//Eliminar en base
			$tipo_diagnostico_consulta = DiagnosticoConsulta::find($id_tipo)->delete();
			return back()->with('success', 'Tipo de diagnostico de consulta eliminado correctamente');
	}


	public function exportar(Request $request)
	{

		if (!auth()->user()->id_cliente_actual) {
			return back()->with('error', 'No se encontraron consultas');
		}

		$query = ConsultaMedica::select('consultas_medicas.*', 'nominas.nombre', 'nominas.email','diagnostico_consulta.nombre as diagnostico')
		->join('nominas','nominas.id','consultas_medicas.id_nomina')
		->join('diagnostico_consulta','diagnostico_consulta.id','consultas_medicas.id_diagnostico_consulta')
		->where('nominas.id_cliente',auth()->user()->id_cliente_actual)->orderBy('consultas_medicas.fecha', 'desc');

		if($request->from) $query->whereDate('consultas_medicas.fecha','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		if($request->to) $query->whereDate('consultas_medicas.fecha','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));

		$consultas = $query->get();

		if (!$consultas) {
			return back()->with('error', 'No se han encontrado consultas');
		}

		$hoy = Carbon::now();
		$file_name = 'consultas-medicas-'.$hoy->format('YmdHis').'.csv';

		//dd($consultas);

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
