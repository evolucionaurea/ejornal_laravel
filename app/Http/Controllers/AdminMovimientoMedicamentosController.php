<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\StockMedicamento;
use App\StockMedicamentoHistorial;
use Illuminate\Support\Facades\DB;

class AdminMovimientoMedicamentosController extends Controller
{

	public function index()
	{

		/*$stock_medicamentos = StockMedicamentoHistorial::join('stock_medicamentos', 'stock_medicamentos_historial.id_stock_medicamentos', 'stock_medicamentos.id')
		->join('medicamentos', 'stock_medicamentos.id_medicamento', 'medicamentos.id')
		->join('users', 'stock_medicamentos.id_user', 'users.id')
		->join('clientes', 'stock_medicamentos.id_cliente', 'clientes.id')
		->select(
			'medicamentos.nombre',
			DB::raw('clientes.nombre cliente'),
			DB::raw('users.nombre user'),
			'stock_medicamentos_historial.*'
		)
		->orderBy('stock_medicamentos_historial.created_at', 'DESC')
		->get();*/


		return view('admin.movimientos_medicamentos');

	}

	public function busqueda(Request $request){

		$query = StockMedicamentoHistorial::select('stock_medicamentos_historial.*')
			->with([
				'stock_medicamento.medicamento',
				'stock_medicamento.user',
				'stock_medicamento.cliente'
			])
			->join('stock_medicamentos','stock_medicamentos_historial.id_stock_medicamentos', 'stock_medicamentos.id')
			->join('medicamentos','stock_medicamentos.id_medicamento','medicamentos.id')
			->join('users','stock_medicamentos.id_user','users.id')
			->join('clientes','stock_medicamentos.id_cliente','clientes.id');

		$total_records = $query->count();



		if($request->cliente) $query->where('clientes.id',$request->cliente);
		if($request->medicamento) $query->where('medicamentos.id',$request->medicamento);
		if($request->from) $query->whereDate('stock_medicamentos_historial.created_at','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		if($request->to) $query->whereDate('stock_medicamentos_historial.created_at','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
		if($request->search) {
			$query->where(function($query) use($request) {
				$filtro = '%'.$request->search['value'].'%';
				$query->where('medicamentos.nombre','like',$filtro)
					->orWhere('users.nombre','like',$filtro)
					->orWhere('clientes.nombre','like',$filtro);
			});
		}



		// ORDER
		if($request->order){
			$sort = $request->columns[$request->order[0]['column']]['name'];
			$dir  = $request->order[0]['dir'];
			$query->orderBy($sort,$dir);
		}

		return [
			'draw'=>$request->draw,
			'recordsTotal'=>$total_records,
			'recordsFiltered'=>$query->count(),
			'data'=>$query->skip($request->start)->take($request->length)->get(),
			'request'=>$request->all()
		];
	}


}
