<?php

namespace App\Http\Controllers;

use App\ConsultaNutricional;
use App\Patologia;
use Illuminate\Http\Request;
use App\Http\Traits\Clientes;
use App\Nomina;
use App\Cliente;
use Carbon\Carbon;
class EmpleadosConsultaNutricionalController extends Controller
{

	use Clientes;
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$clientes = $this->getClientesUser();
		$query = ConsultaNutricional::with(['nomina', 'cliente'])
			->where('id_cliente', auth()->user()->id_cliente_actual);

		// Filtros de fechas
		if ($request->filled('fecha_desde')) {
			$fechaDesde = Carbon::createFromFormat('d-m-Y', $request->fecha_desde)->format('Y-m-d');
			$query->whereDate('fecha_atencion', '>=', $fechaDesde);
		}

		if ($request->filled('fecha_hasta')) {
			$fechaHasta = Carbon::createFromFormat('d-m-Y', $request->fecha_hasta)->format('Y-m-d');
			$query->whereDate('fecha_atencion', '<=', $fechaHasta);
		}

		$paginatedNutricion = $query->orderBy('created_at', 'desc')->paginate(10);

		return view('empleados.consultas.nutricionales', compact('paginatedNutricion', 'clientes'));
	}




	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$clientes = $this->getClientesUser();
		$patologias = Patologia::all();

		// Filtrar por 'estado' y ordenar alfabéticamente por 'nombre'
		$nominas = Nomina::where('id_cliente', auth()->user()->id_cliente_actual)
			->where('estado', 1)  // Filtramos las nominas donde el estado es 1
			->orderBy('nombre', 'asc')  // Ordenamos alfabéticamente por 'nombre'
			->get();

		$cliente = Cliente::find(auth()->user()->id_cliente_actual);

		return view('empleados.consultas.nutricionales.create', compact('patologias', 'clientes', 'nominas', 'cliente'));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{


		$validatedData = $request->validate([
			'id_nomina' => 'required|exists:nominas,id',
			'id_cliente' => 'required|exists:clientes,id',
			'tipo' => 'required|in:inicial,seguimiento',
			'fecha_atencion' => 'required',
			// Campos opcionales
			'objetivos' => 'nullable|string',
			'gustos_alimentarios' => 'nullable|string',
			'comidas_diarias' => 'nullable|string',
			'descanso' => 'nullable|string',
			'intolerancias_digestivas' => 'nullable|string',
			'alergias_alimentarias' => 'nullable|string',
			'circunferencia_cintura' => 'nullable|numeric',
			'porcent_masa_grasa' => 'nullable|numeric',
			'porcent_masa_muscular' => 'nullable|numeric',
			'prox_cita' => 'nullable',
			'act_fisica' => 'nullable|string',
			'transito_intestinal' => 'nullable|string',
			'evolucion' => 'nullable|string',
			'medicaciones' => 'nullable|string',
		]);



		// Crear una nueva consulta nutricional
		$consultaNutricional = new ConsultaNutricional();

		// Datos Obligatorios
		$consultaNutricional->id_nomina = $validatedData['id_nomina'];
		$consultaNutricional->id_cliente = $validatedData['id_cliente'];
		$consultaNutricional->tipo = $validatedData['tipo'];
		$consultaNutricional->user = auth()->user()->nombre;
		$consultaNutricional->fecha_atencion = Carbon::createFromFormat('d/m/Y', $validatedData['fecha_atencion']);

		// Asignar los campos adicionales según el tipo de consulta
		switch ($validatedData['tipo']) {
			case 'inicial':
				$consultaNutricional->objetivos = $validatedData['objetivos'] ?? null;
				$consultaNutricional->gustos_alimentarios = $validatedData['gustos_alimentarios'] ?? null;
				$consultaNutricional->comidas_diarias = $validatedData['comidas_diarias'] ?? null;
				$consultaNutricional->descanso = $validatedData['descanso'] ?? null;
				$consultaNutricional->intolerancias_digestivas = $validatedData['intolerancias_digestivas'] ?? null;
				$consultaNutricional->alergias_alimentarias = $validatedData['alergias_alimentarias'] ?? null;
				break;

			case 'seguimiento':
				$consultaNutricional->circunferencia_cintura = $validatedData['circunferencia_cintura'] ?? null;
				$consultaNutricional->porcent_masa_grasa = $validatedData['porcent_masa_grasa'] ?? null;
				$consultaNutricional->porcent_masa_muscular = $validatedData['porcent_masa_muscular'] ?? null;
				$consultaNutricional->prox_cita = Carbon::createFromFormat('d/m/Y', $validatedData['prox_cita']) ?? null;
				$consultaNutricional->act_fisica = $validatedData['act_fisica'] ?? null;
				$consultaNutricional->transito_intestinal = $validatedData['transito_intestinal'] ?? null;
				$consultaNutricional->evolucion = $validatedData['evolucion'] ?? null;
				$consultaNutricional->medicaciones = $validatedData['medicaciones'] ?? null;
				break;

			default:
				// Si el tipo no es ni 'inicial' ni 'seguimiento', no asignar nada
				break;
		}


		// Guardar la consulta nutricional en la base de datos
		$consultaNutricional->save();

		// Redirigir a la lista de consultas nutricionales con un mensaje de éxito
		return redirect()->route('empleados.consultas.nutricionales')->with('success', 'Consulta nutricional creada exitosamente');


	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$clientes = $this->getClientesUser();
		$nutricional = ConsultaNutricional::with(['nomina', 'cliente'])->find($id);
		return view('empleados.consultas.nutricionales.show', compact('nutricional', 'clientes'));
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
}
