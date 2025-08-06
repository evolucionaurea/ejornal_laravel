<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Nomina;
use App\Cliente;
use App\NominaHistorial;
use App\NominaClienteHistorial;
use App\Http\Traits\Nominas;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;


class ClientesNominasController extends Controller
{

	use Nominas;

	public function index()
	{
		$cliente = Cliente::where('id', auth()->user()->id_cliente_relacionar)
		->select('clientes.nombre')
		->first();
		///$nominas = Nomina::where('id_cliente', auth()->user()->id_cliente_relacionar)->get();
		return view('clientes.nominas', compact('cliente'));
	}

	public function busqueda(Request $request)
	{
		//Traits > Nominas
		return $this->searchNomina(auth()->user()->id_cliente_relacionar,$request);
	}

	public function exportar(Request $request)
	{

		//Traits > Nominas
		return $this->exportNomina(auth()->user()->id_cliente_relacionar,$request);
	}

	public function historial()
	{

		$cliente = Cliente::where('id', auth()->user()->id_cliente_relacionar)
			->select('clientes.nombre')
			->first();

		return view('clientes.nominas_historial', compact('cliente'));

	}
	public function historial_listado(Request $request){

		$query = NominaHistorial::select()
			->where('cliente_id',auth()->user()->id_cliente_relacionar);

		$total = $query->count();

		if($request->order){
			$sort = $request->columns[$request->order[0]['column']]['data'];
			$dir  = $request->order[0]['dir'];
			switch ($sort) {
				case 'year':
					$sort = 'year_month';
					break;
			}
			$query->orderBy($sort,$dir);
		}


		$records_filtered = $query->count();
		$historial = $query->skip($request->start)->take($request->length)->get();

		foreach($historial as $k=>$hist){
			if($k===count($historial)-1){
				$hist->dif_mes_anterior = 0;
			}else{
				$hist->dif_mes_anterior = $hist->cantidad-$historial[$k+1]->cantidad;
			}
		}
		return [
			'draw'=>$request->draw,
			'recordsTotal'=>$total,
			'recordsFiltered'=>$records_filtered,
			'data'=>$historial,
			'request'=>$request->all()
		];

	}



	// VISTA
	public function movimientos()
	{

		$cliente = Cliente::where('id', auth()->user()->id_cliente_relacionar)
			->select('clientes.nombre')
			->first();
		$clientes = false;

		return view('clientes.nominas_movimientos', compact('cliente','clientes'));
	}
	//DATOS AJAX
	public function movimientos_listado(Request $request)
	{
		return $this->movimientosListado([auth()->user()->id_cliente_relacionar],$request);
	}

}
