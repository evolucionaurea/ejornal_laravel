<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Grupo;
use App\User;
use App\Nomina;
use App\NominaHistorial;
use App\Http\Traits\ClientesGrupo;
use App\Http\Traits\Nominas;

class GruposNominasController extends Controller
{
	use ClientesGrupo,Nominas;
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return view('grupos.nominas', $this->getClientesGrupo());
	}

	public function busqueda(Request $request)
	{
		//Traits > Nominas
		return $this->searchNomina(auth()->user()->id_cliente_actual,$request);
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
		//
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


	public function exportar(Request $request)
  {

  	//Traits > Nominas
    return $this->exportNomina(auth()->user()->id_cliente_actual,$request);
  }


  public function historial()
	{
		//dd($this->getClientesGrupo());
		return view('grupos.nominas_historial', $this->getClientesGrupo());

	}
	public function historial_listado(Request $request){

		$query = NominaHistorial::select()
			->where('cliente_id',auth()->user()->id_cliente_actual);

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


}
