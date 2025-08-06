<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\StockMedicamentoHistorial;
use App\StockMedicamento;

trait StockMedicamentos {

	public function searchStock(Request $request){

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

		$total = $query->count();

		if($request->search){
			$query->where(function($query) use($request){
				$filtro = '%'.$request->search.'%';
				$query->where('medicamentos.nombre','like',$filtro);
			});
		}

		if($request->order){
			$sort = $request->columns[$request->order[0]['column']]['name'];
			///dd($sort);
			$dir  = $request->order[0]['dir'];
			$query->orderBy($sort,$dir);
		}

		$records_filtered = $query->count();
		if($request->length) $query->skip($request->start)->take($request->length);
		$medicamentos = $query->get();

		return [
			'draw'=>$request->draw,
			'recordsTotal'=>$total,
			'recordsFiltered'=>$records_filtered,
			'data'=>$medicamentos,
			'fichada_user'=>auth()->user()->fichada,
			'fichar_user'=>auth()->user()->fichar,
			'request'=>$request->all()
		];

	}

	public function searchHistorial(Request $request){

		DB::enableQueryLog();

		/*$query = StockMedicamentoHistorial::select(
			'medicamentos.nombre as medicamento',
			'stock_medicamentos_historial.*',

			'stock_medicamentos.motivo as motivo_stock',

			DB::raw('IF(stock_medicamentos_historial.user_id IS NULL,users.nombre,users_2.nombre) as user'),

			'consultas_medicas.user as user_consulta_medica',
			'consultas_enfermerias.user as user_consulta_enfermeria',

			'clientes.nombre as cliente',
			'nominas.nombre as trabajador',
			DB::raw('IF(stock_medicamentos_historial.id_consulta_enfermeria IS NOT NULL, "Enfermería", "Médica") as tipo_consulta')
		)
		->join('stock_medicamentos', 'stock_medicamentos_historial.id_stock_medicamentos', 'stock_medicamentos.id')
		->join('medicamentos', 'stock_medicamentos.id_medicamento', 'medicamentos.id')

		->leftJoin('users', 'stock_medicamentos.id_user','users.id')
		->leftJoin('users as users_2', 'stock_medicamentos_historial.user_id','users_2.id')

		->join('clientes', 'stock_medicamentos.id_cliente', 'clientes.id')

		->leftJoin('consultas_enfermerias', 'stock_medicamentos_historial.id_consulta_enfermeria', 'consultas_enfermerias.id')
		->leftJoin('consultas_medicas', 'stock_medicamentos_historial.id_consulta_medica', 'consultas_medicas.id')
		->leftJoin('nominas', function ($join) {
			$join
				->on('consultas_enfermerias.id_nomina', '=', 'nominas.id')
				->orOn('consultas_medicas.id_nomina', '=', 'nominas.id');
		})
		->where('stock_medicamentos.id_cliente', auth()->user()->id_cliente_actual);*/

		$query = StockMedicamentoHistorial::select('stock_medicamentos_historial.*')
			->join('stock_medicamentos', 'stock_medicamentos_historial.id_stock_medicamentos', 'stock_medicamentos.id')
			->join('medicamentos', 'stock_medicamentos.id_medicamento', 'medicamentos.id')
			->with('consulta_medica.trabajador')
			->with('consulta_enfermeria.trabajador')
			->with([
				'stock_medicamento.medicamento',
				'stock_medicamento.user',
				'stock_medicamento.cliente'
			])
			->with('user')
			->whereHas('stock_medicamento', function($query){
				$query->where('id_cliente',auth()->user()->id_cliente_actual);
			});


		$total = $query->count();


		if($request->order){
			$sort = $request->columns[$request->order[0]['column']]['name'];
			$dir  = $request->order[0]['dir'];
			$query->orderBy($sort,$dir);
		}


		if($request->search){
			$query->where(function($query) use($request){
				$filtro = '%'.$request->search.'%';
				/*$query
					->where('medicamentos.nombre','like',$filtro)
					->orWhere('nominas.nombre','like',$filtro)
					->orWhere('users.nombre','like',$filtro)
					->orWhere('clientes.nombre','like',$filtro);*/
				$query->whereHas('stock_medicamento',function($query) use($filtro){
					$query
						->whereHas('medicamento',function($query) use($filtro){
							$query->where('nombre','like',$filtro);
						})
						->orWhereHas('user',function($query) use($filtro){
							$query->where('nombre','like',$filtro);
						})
						->orWhereHas('cliente',function($query) use($filtro){
							$query->where('nombre','like',$filtro);
						});

				});


			});
		}
		if($request->tipo=='enfermeria'){
			$query->whereNotNull('id_consulta_enfermeria');
		}
		if($request->tipo=='medica'){
			$query->whereNotNull('id_consulta_medica');
		}

		if ($request->from) {
			$query->whereDate('stock_medicamentos_historial.created_at', '>=', Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		}
		if ($request->to) {
			$query->whereDate('stock_medicamentos_historial.created_at', '<=', Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
		}


		$records_filtered = $query->count();
		if($request->length) $query->skip($request->start)->take($request->length);

		$movimientos = $query->get();

		return [

			'draw'=>$request->draw,
			'recordsTotal'=>$total,
			'recordsFiltered'=>$records_filtered,
			'data'=>$movimientos,
			///'sort'=>$sort.','.$dir,
			'fichada_user' => auth()->user()->fichada,
			'fichar_user'=>auth()->user()->fichar,
			'request' => $request->all(),
			'queries'=>DB::getQueryLog(),

			'sort'=>$request->columns[$request->order[0]['column']]['name'].','.$request->order[0]['dir']
		];


	}

}