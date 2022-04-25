<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\ClienteUser;
use App\Http\Traits\Clientes;
use App\CovidTesteo;
use App\CovidTesteoTipo;
use App\Nomina;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EmpleadosCovidTesteoController extends Controller
{
	use Clientes;

	public function index(Request $request)
	{
		$clientes = $this->getClientesUser();

		return view('empleados.covid.testeos', compact('clientes'));
	}
	public function busqueda(Request $request)
	{
		$query = CovidTesteo::select(
				'nominas.nombre',
				DB::raw('covid_testeos_tipo.nombre tipo'),
				'covid_testeos.fecha',
				'covid_testeos.laboratorio',
				'covid_testeos.resultado',
				'covid_testeos.id'
			)
			->join('nominas', 'covid_testeos.id_nomina', 'nominas.id')
			->join('covid_testeos_tipo', 'covid_testeos.id_tipo', 'covid_testeos_tipo.id')
			->where('nominas.id_cliente', auth()->user()->id_cliente_actual);

		if($request->from) $query->whereDate('covid_testeos.fecha','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		if($request->to) $query->whereDate('covid_testeos.fecha','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));

		if($request->filtro=='positivos') $query->where('covid_testeos.resultado','positivo');

		return [
			'results'=>$query->get(),
			'fichada'=>auth()->user()->fichada,
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

		$covid_testeos_tipo = CovidTesteoTipo::orderBy('nombre')->get();
		$nominas = Nomina::where('id_cliente', auth()->user()->id_cliente_actual)->orderBy('nombre', 'asc')->get();

		return view('empleados.covid.testeos.create', compact('clientes', 'covid_testeos_tipo', 'nominas'));
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
			'nomina' => 'required',
			'tipo' => 'required',
			'fecha' => 'required',
			'resultado' => 'required|string',
			'laboratorio' => 'required|string'
		]);

		$fecha = Carbon::createFromFormat('d/m/Y', $request->fecha);

		//Guardar en base CovidTesteo
		$testeo = new CovidTesteo();
		$testeo->id_nomina = $request->nomina;
		$testeo->id_tipo = $request->tipo;
		$testeo->fecha = $fecha;
		$testeo->resultado = $request->resultado;
		$testeo->laboratorio = $request->laboratorio;
		$testeo->save();

		return redirect('empleados/covid/testeos')->with('success', 'Testeo de covid guardado con éxito');

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
			//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{

		$testeo = CovidTesteo::where('covid_testeos.id', $id)
		->join('nominas', 'covid_testeos.id_nomina', 'nominas.id')
		->join('covid_testeos_tipo', 'covid_testeos.id_tipo', 'covid_testeos_tipo.id')
		->select('nominas.nombre', DB::raw('covid_testeos_tipo.nombre tipo'), 'covid_testeos.fecha',
		'covid_testeos.laboratorio', 'covid_testeos.resultado', 'covid_testeos.id', 'covid_testeos.id_nomina', 'covid_testeos.id_tipo')
		->first();

		$clientes = $this->getClientesUser();

		$covid_testeos_tipo = CovidTesteoTipo::all();
		$nominas = Nomina::where('id_cliente', auth()->user()->id_cliente_actual)->get();

		return view('empleados.covid.testeos.edit', compact('testeo', 'clientes', 'covid_testeos_tipo', 'nominas'));

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

		$validatedData = $request->validate([
			'nomina' => 'required',
			'tipo' => 'required',
			'fecha' => 'required',
			'resultado' => 'required|string',
			'laboratorio' => 'required|string'
		]);

		$fecha = Carbon::createFromFormat('d/m/Y', $request->fecha);

		//Actualizar en base
		$testeo = CovidTesteo::findOrFail($id);
		$testeo->id_nomina = $request->nomina;
		$testeo->id_tipo = $request->tipo;
		$testeo->fecha = $fecha;
		$testeo->resultado = $request->resultado;
		$testeo->laboratorio = $request->laboratorio;
		$testeo->save();

		return redirect('empleados/covid/testeos')->with('success', 'Testeo de Covid actualizado con éxito');


	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{

		$testeo = CovidTesteo::findOrFail($id)->delete();
		return back()->with('success', 'Testeo de covid eliminado correctamente');

	}




	public function tipo_destroy($id_tipo)
	{

		$testeo_covid = CovidTesteo::where('id_tipo', $id_tipo)->get();

		if (!empty($testeo_covid) && count($testeo_covid) > 0) {
			return back()->with('error', 'Existen testeos de covid creados con este tipo de testeo. No puedes eliminarlo');
		}

		//Eliminar en base
		$tipo_testeo = CovidTesteoTipo::find($id_tipo)->delete();
		return back()->with('success', 'Tipo de testeo de covid eliminado correctamente');
	}



}
