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
		$clientes = Cliente::all();
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
		//dd($mas_sumunistrado);

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
		/*$stocks = StockMedicamento::join('medicamentos', 'stock_medicamentos.id_medicamento', 'medicamentos.id')
			->join('clientes', 'stock_medicamentos.id_cliente', 'clientes.id')
			->select('medicamentos.nombre', DB::raw('clientes.nombre cliente'), 'stock_medicamentos.stock')
			->get();

		return response()->json($stocks);*/

		$query = StockMedicamento::select('stock_medicamentos.*')
			->with(['medicamento','cliente'])
			->join('medicamentos', 'stock_medicamentos.id_medicamento', 'medicamentos.id')
			->join('clientes', 'stock_medicamentos.id_cliente', 'clientes.id');
		$total = $query->count();


		// FILTROS
		if($request->medicamento){
			$query->where('stock_medicamentos.id_medicamento','=',$request->medicamento);
		}
		if($request->cliente){
			$query->where('stock_medicamentos.id_cliente','=',$request->cliente);
		}


		// BUSQUEDA
		if(isset($request->search)){
			$query->where(function($query) use($request) {
				$filtro = '%'.$request->search['value'].'%';
				$query->where('clientes.nombre','like',$filtro)
					->orWhere('medicamentos.nombre','like',$filtro);
			});
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


}
