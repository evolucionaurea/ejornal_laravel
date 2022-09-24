<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Grupo;
use App\User;
use App\ClienteGrupo;
use App\Http\Traits\ClientesGrupo;
use Carbon\Carbon;

class GruposResumenController extends Controller
{
	use ClientesGrupo;
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		setlocale(LC_TIME, 'Spanish');
		// el metodo getClientesGrupo heredada de App\Http\Traits\ClientesGrupo
		$clientes_grupo = $this->getClientesGrupo();

		//dd($today->endOfMonth());


		$clientes_nominas = $clientes_grupo['grupo']->fresh(['clientes'=>function($query){
			$query->select('clientes.id','clientes.nombre')->withCount('nominas');
		}]);
		$clientes_ausentismos = $clientes_grupo['grupo']->fresh(['clientes'=>function($query){
			$query->select('clientes.id','clientes.nombre')->withCount(['ausentismos'=>function($query){
				$today = Carbon::now();
				$query->where('fecha_regreso_trabajar',null)->orWhere('fecha_regreso_trabajar','>=',$today);
			}]);
		}]);
		//dd( $clientes_ausentismos->clientes[1] );


		$output = array_merge($clientes_grupo,[
			'clientes_nominas'=>$clientes_nominas,
			'clientes_ausentismos'=>$clientes_ausentismos
		]);

		return view('grupos.resumen',$output);
	}

	public function clienteActual(Request $request)
	{
		$user = User::findOrFail(auth()->user()->id);
		$user->id_cliente_actual = intval($request->id_cliente);
		$user->save();
		return ['status'=>'ok'];
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
}
