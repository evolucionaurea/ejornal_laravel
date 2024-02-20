<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\StockMedicamento;
//use App\Cliente;
use App\Http\Traits\Clientes;
use App\Medicamento;
use App\StockMedicamentoHistorial;
use App\ClienteUser;
use Carbon\Carbon;

class EmpleadosStockMedicamentoController extends Controller
{
	use Clientes;

	public function index()
	{
		$clientes = $this->getClientesUser();
		return view('empleados.medicamentos', compact('clientes'));
	}
	public function busqueda(Request $request)
	{
		$query = StockMedicamento::select(
			'medicamentos.nombre',
			DB::raw('clientes.nombre cliente'),
			DB::raw('users.nombre user'),
			'stock_medicamentos.*'
		)
		->join('medicamentos', 'stock_medicamentos.id_medicamento', 'medicamentos.id')
		->join('users', 'stock_medicamentos.id_user', 'users.id')
		->join('clientes', 'stock_medicamentos.id_cliente', 'clientes.id')
		->where('id_cliente', auth()->user()->id_cliente_actual);

		if($request->from) $query->whereDate('stock_medicamentos.fecha_ingreso','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		if($request->to) $query->whereDate('stock_medicamentos.fecha_ingreso','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));


		return [
			'results'=>$query->get(),
			'fichada_user'=>auth()->user()->fichada,
			'fichar_user'=>auth()->user()->fichar,
			'request'=>$request->all()
		];

	}

	public function movimientos()
	{

	  $clientes = $this->getClientesUser();
	  return view('empleados.medicamentos_movimientos', compact('clientes'));

	}


	public function busquedaMovimientos(Request $request)
	{
		$query = StockMedicamentoHistorial::select(
			'medicamentos.nombre as medicamento',
			'stock_medicamentos_historial.ingreso',
			'stock_medicamentos_historial.stock',
			'stock_medicamentos_historial.suministrados',
			'stock_medicamentos_historial.motivo',
			'stock_medicamentos_historial.fecha_ingreso',
			'stock_medicamentos_historial.created_at',
			'stock_medicamentos_historial.updated_at',
			'users.nombre as user',
			'clientes.nombre as cliente',
			'nominas.nombre as trabajador',
			DB::raw('IF(stock_medicamentos_historial.id_consulta_enfermeria IS NOT NULL, "enfermeria", "medica") as tipo_consulta')
		)
		->join('stock_medicamentos', 'stock_medicamentos_historial.id_stock_medicamentos', 'stock_medicamentos.id')
		->join('medicamentos', 'stock_medicamentos.id_medicamento', 'medicamentos.id')
		->join('users', 'stock_medicamentos.id_user', 'users.id')
		->join('clientes', 'stock_medicamentos.id_cliente', 'clientes.id')
		->leftJoin('consultas_enfermerias', 'stock_medicamentos_historial.id_consulta_enfermeria', 'consultas_enfermerias.id')
		->leftJoin('consultas_medicas', 'stock_medicamentos_historial.id_consulta_medica', 'consultas_medicas.id')
		->leftJoin('nominas', function ($join) {
			$join->on('consultas_enfermerias.id_nomina', '=', 'nominas.id')
				->orOn('consultas_medicas.id_nomina', '=', 'nominas.id');
		})
		->where('stock_medicamentos.id_cliente', auth()->user()->id_cliente_actual)
		->orderBy('stock_medicamentos_historial.created_at', 'DESC');

		if ($request->from) {
			$query->whereDate('stock_medicamentos_historial.fecha_ingreso', '>=', Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		}

		if ($request->to) {
			$query->whereDate('stock_medicamentos_historial.fecha_ingreso', '<=', Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
		}

		return [
			'results' => $query->get(),
			'fichada_user' => auth()->user()->fichada,
			'fichar_user'=>auth()->user()->fichar,
			'request' => $request->all()
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

	  $medicamentos = Medicamento::orderBy('nombre', 'asc')->get();
	  return view('empleados.medicamentos.create', compact('clientes', 'medicamentos'));

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
		'medicamento' => 'required',
		'ingreso' => 'required|numeric',
		'fecha_ingreso' => 'required',
		'motivo' => 'required|string'
	  ]);

	  //dd(auth()->user()->id_cliente_actual);

	  $fecha_ingreso = Carbon::createFromFormat('d/m/Y', $request->fecha_ingreso);

	  $medicamento_existente = StockMedicamento::where('id_medicamento', $request->medicamento)
	  ->where('id_cliente', auth()->user()->id_cliente_actual)
	  ->first();

	  if (isset($medicamento_existente) && !empty($medicamento_existente) && !is_null($medicamento_existente)) {

			//Actualizar en base uno existente
			$medicamento_existente->ingreso = $medicamento_existente->ingreso + $request->ingreso;
			$medicamento_existente->stock = $medicamento_existente->stock + $request->ingreso;
			$medicamento_existente->save();

			//Actualizar en base el historial
			$stock_medicamentos_historial = new StockMedicamentoHistorial();
			$stock_medicamentos_historial->id_stock_medicamentos = $medicamento_existente->id;
			$stock_medicamentos_historial->ingreso = $request->ingreso;
			$stock_medicamentos_historial->fecha_ingreso = $fecha_ingreso;
			$stock_medicamentos_historial->motivo = $request->motivo;
			$stock_medicamentos_historial->save();

		  }else {
			//Guardar en base La primera vez que se crea
			$stock_medicamento = new StockMedicamento();
			$stock_medicamento->id_medicamento = $request->medicamento;
			$stock_medicamento->id_user = auth()->user()->id;
			$stock_medicamento->id_cliente = auth()->user()->id_cliente_actual;
			$stock_medicamento->ingreso = $request->ingreso;
			$stock_medicamento->stock = $request->ingreso;
			$stock_medicamento->suministrados = 0;
			$stock_medicamento->egreso = 0;
			$stock_medicamento->fecha_ingreso = $fecha_ingreso;
			$stock_medicamento->motivo = $request->motivo;
			$stock_medicamento->save();


			//Guardar en base
			$stock_medicamentos_historial = new StockMedicamentoHistorial();
			$stock_medicamentos_historial->id_stock_medicamentos = $stock_medicamento->id;
			$stock_medicamentos_historial->ingreso = $request->ingreso;
			$stock_medicamentos_historial->stock = $request->ingreso;
			$stock_medicamentos_historial->suministrados = 0;
			$stock_medicamentos_historial->egreso = 0;
			$stock_medicamentos_historial->fecha_ingreso = $fecha_ingreso;
			$stock_medicamentos_historial->motivo = $request->motivo;
			$stock_medicamentos_historial->save();
	  }


	  return redirect('empleados/medicamentos')->with('success', 'Medicamento cargado con éxito');

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

	  //Actualizar en base
	  $stock_medicamento = StockMedicamento::findOrFail($id);

	  if ($request->egreso > $stock_medicamento->ingreso) {
		return back()->withInput()->with('error', 'No pueden egresar mas unidades que las disponibles al momento del ingreso');
	  }

	  $stock_medicamento->egreso = $stock_medicamento->egreso + $request->egreso;
	  $stock_medicamento->stock = $stock_medicamento->stock - $request->egreso;
	  $stock_medicamento->save();

	  $stock_medicamento_historial = new StockMedicamentoHistorial();
	  $stock_medicamento_historial->id_stock_medicamentos = $id;
	  $stock_medicamento_historial->egreso = $stock_medicamento->egreso;
	  $stock_medicamento_historial->fecha_ingreso = $stock_medicamento->fecha_ingreso;
	  $stock_medicamento_historial->save();

	  return redirect('empleados/medicamentos')->with('success', 'Stock del medicamento actualizado correctamente');

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{

	  $stock_medicamento = StockMedicamento::find($id)->delete();
	  return redirect('empleados/medicamentos')->with('success', 'Medicamento eliminado correctamente');

	}







}
