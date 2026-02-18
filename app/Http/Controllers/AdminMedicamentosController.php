<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Medicamento;
use App\StockMedicamento;
use App\Cliente;

class AdminMedicamentosController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	

	public function index()
	{
		$medicamentos_filtro = Medicamento::orderBy('nombre')->get(['id','nombre']);

		$clientes = Cliente::withTrashed()
			->orderBy('nombre')
			->get(['id','nombre','deleted_at']);

		return view('admin.medicamentos', compact('medicamentos_filtro','clientes'));
	}


	public function busqueda(Request $request)
	{
		// ✅ Solo POST (aunque alguien intente entrar por GET)
		if (!$request->isMethod('post')) {
			abort(405);
		}

		$idMedicamento = $request->filled('medicamento') ? (int) $request->input('medicamento') : null;
		$idCliente     = $request->filled('id_cliente') ? (int) $request->input('id_cliente') : null;

		// viene como '1' / '0' desde el select (o vacío)
		$stockFiltro = ($request->has('stock') && $request->input('stock') !== '')
			? (string) $request->input('stock')
			: null;

		// ✅ cliente eliminado (si se filtró por cliente)
		$clienteEliminado = false;
		if ($idCliente) {
			$cliente = Cliente::withTrashed()->select('id', 'deleted_at')->find($idCliente);
			$clienteEliminado = $cliente ? !is_null($cliente->deleted_at) : false;
		}

		// ✅ Subquery agregado por medicamento (y opcionalmente por cliente)
		$agg = StockMedicamento::query()
			->select(
				'id_medicamento',
				DB::raw('COALESCE(SUM(ingreso),0) as ingreso_total'),
				DB::raw('COALESCE(SUM(egreso),0) as egreso_total'),
				DB::raw('COALESCE(SUM(suministrados),0) as suministrados_total'),
				DB::raw('(COALESCE(SUM(ingreso),0) - COALESCE(SUM(suministrados),0) - COALESCE(SUM(egreso),0)) as stock_total')
			)
			->when($idCliente, fn ($q) => $q->where('id_cliente', $idCliente))
			->groupBy('id_medicamento');

		// ✅ Query base de medicamentos
		$q = Medicamento::query()->from('medicamentos');

		if ($idMedicamento) {
			$q->where('medicamentos.id', $idMedicamento);
		}

		// Si filtran por cliente: INNER JOIN (solo meds con movimientos para ese cliente)
		if ($idCliente) {
			$q->joinSub($agg, 'sm', fn ($join) => $join->on('medicamentos.id', '=', 'sm.id_medicamento'));
		} else {
			$q->leftJoinSub($agg, 'sm', fn ($join) => $join->on('medicamentos.id', '=', 'sm.id_medicamento'));
		}

		$q->select(
			'medicamentos.id',
			'medicamentos.nombre',
			DB::raw('COALESCE(sm.stock_total,0) as stock_total'),
			DB::raw('COALESCE(sm.suministrados_total,0) as suministrados_total')
		);

		// ✅ Filtro C/S stock
		if ($stockFiltro === '1') {
			$q->whereRaw('COALESCE(sm.stock_total,0) > 0');
		} elseif ($stockFiltro === '0') {
			$q->whereRaw('COALESCE(sm.stock_total,0) <= 0');
		}

		$medicamentos = $q->orderBy('medicamentos.nombre')->get();

		// ✅ Flag para pintar (solo tiene sentido cuando filtran por cliente)
		$medicamentos->each(function ($m) use ($idCliente, $clienteEliminado) {
			$m->setAttribute('cliente_eliminado', $idCliente ? $clienteEliminado : false);
		});

		// ✅ Tablas.js espera "results"
		return response()->json([
			'results' => $medicamentos->values()->toArray(),
			'request' => $request->all(),
		]);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
			//
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
			'nombre' => 'required|string'
		]);

		//Guardar en base
		$medicamento = new Medicamento();
		$medicamento->nombre = $request->nombre;
		$medicamento->save();

		return back()->with('success', 'Medicamento guardado con éxito');

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
		$medicamento = Medicamento::findOrFail($id);
		return view('admin.medicamentos.edit', compact('medicamento'));

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
			'nombre' => 'required|string'
		]);

		//Actualizar en base
		$medicamento = Medicamento::findOrFail($id);
		$medicamento->nombre = $request->nombre;
		$medicamento->save();

		return redirect('admin/medicamentos')->with('success', 'Medicamento actualizado con éxito');

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$medicamento = Medicamento::find($id)->delete();
		return back()->with('success', 'Medicamento eliminado correctamente');
	}
}
