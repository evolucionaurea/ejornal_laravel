<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Cliente;
use App\StockMedicamento;
use App\Medicamento;
use Illuminate\Support\Facades\DB;

class AdminResumenController extends Controller
{

	public function index()
	{
		$clientes = Cliente::orderBy('nombre')->get();
		$enfermeros = User::where('id_especialidad', 2)->where('estado', 1)->count();
		$medicos = User::where('id_especialidad', 1)->where('estado', 1)->count();

		$enfermeros_trabajando = User::where('id_especialidad', 2)->where('estado', 1)->where('fichada', 1)->count();
		$medicos_trabajando = User::where('id_especialidad', 1)->where('estado', 1)->where('fichada', 1)->count();

		/*$busqueda = StockMedicamento::max('stock_medicamentos.suministrados');
		$mas_sumunistrado = StockMedicamento::join('medicamentos', 'stock_medicamentos.id_medicamento', 'medicamentos.id')
		->join('clientes', 'stock_medicamentos.id_cliente', 'clientes.id')
		->where('stock_medicamentos.suministrados', $busqueda)
		->select('medicamentos.id','medicamentos.nombre', 'stock_medicamentos.suministrados')
		->first();*/

		$mas_sumunistrado = StockMedicamento::selectRaw('sum(stock_medicamentos.suministrados) as suministrados, medicamentos.id, medicamentos.nombre')
					->join('medicamentos','stock_medicamentos.id_medicamento','medicamentos.id')
					->groupBy('stock_medicamentos.id_medicamento')
					->orderByDesc('suministrados')
					->first();

		$medicamentos = Medicamento::orderBy('nombre')->get();

		return view('admin.resumen', compact(
			'clientes',
			'enfermeros',
			'medicos',
			'enfermeros_trabajando',
			'medicos_trabajando',
			'mas_sumunistrado',
			'medicamentos'
		));
	}


	public function create()
	{
			//
	}


	public function store(Request $request)
	{
			//
	}


	public function show($id)
	{
			//
	}


	public function edit($id)
	{
			//
	}


	public function update(Request $request, $id)
	{
			//
	}


	public function destroy($id)
	{
			//
	}

	public function getMedicamentos(Request $request)
	{
		$base = StockMedicamento::select('stock_medicamentos.*')
			->with([
				'medicamento',
				// incluir clientes eliminados
				'cliente' => function ($q) {
					$q->withTrashed();
				},
			])
			// Uso LEFT JOIN para no descartar filas si falta la contraparte
			->leftJoin('medicamentos', 'stock_medicamentos.id_medicamento', '=', 'medicamentos.id')
			->leftJoin('clientes', 'stock_medicamentos.id_cliente', '=', 'clientes.id');

		// total sin filtros de búsqueda
		$total = (clone $base)->count();

		// FILTROS
		if ($request->filled('medicamento')) {
			$base->where('stock_medicamentos.id_medicamento', $request->medicamento);
		}
		if ($request->filled('cliente')) {
			$base->where('stock_medicamentos.id_cliente', $request->cliente);
		}

		if ($request->filled('disponibilidad')) {
			if ($request->disponibilidad === 'con') {
				$base->where('stock_medicamentos.stock', '>', 0);
			} elseif ($request->disponibilidad === 'sin') {
				$base->where('stock_medicamentos.stock', '<=', 0);
			}
    	}

		// BUSQUEDA (en nombre de cliente o de medicamento)
		if (isset($request->search)) {
			$filtro = '%' . $request->search['value'] . '%';
			$base->where(function ($q) use ($filtro) {
				$q->where('clientes.nombre', 'like', $filtro)
				->orWhere('medicamentos.nombre', 'like', $filtro);
			});
		}

		$total_filtered = (clone $base)->count();

		$data = $base
			->skip((int) $request->start)
			->take((int) $request->length)
			->get();

		return [
			'draw' => (int) $request->draw,
			'recordsTotal' => $total,
			'recordsFiltered' => $total_filtered,
			'data' => $data,
			'request' => $request->all(),
		];
	}



}
