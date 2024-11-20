<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FichadaNueva;
use Carbon\Carbon;
use App\User;
use Jenssegers\Agent\Agent;
use DateTime;

class EmpleadosFichadasNuevasController extends Controller
{


	public function clienteActual(Request $request)
	{
		if(!$cliente = $request->input('cliente')){
			return false;
		}
		$id = $request->input('id');

		$user = User::findOrFail($id);
		$user->id_cliente_actual = intval($cliente);
		$user->save();
		return response()->json($user->id_cliente_actual);
	}

	public function horarioUltimaFichada()
	{
		$ultima_fichada = FichadaNueva::where('id_user', auth()->user()->id)->latest()->get();
		return response($ultima_fichada);
	}



	public function store(Request $request)
	{

	  $agent = new Agent();
	  if (auth()->user()->fichada == 0) {
			$ingreso = Carbon::now();

			//Actualizar usuario para que figure que esta trabajando
			$user = User::findOrFail(auth()->user()->id);
			$user->fichada = 1;
			$user->save();

			//Guardar en base
			$fichada = new FichadaNueva();
			$fichada->ingreso = $ingreso;
			$fichada->id_user = $request->id_user;
			$fichada->id_cliente = auth()->user()->id_cliente_actual;
			$fichada->ip = \Request::ip();

			$fichada->sistema_operativo = $agent->platform();
			$fichada->browser = $agent->browser();

			// Determinar el tipo de dispositivo
			$fichada->dispositivo = $agent->deviceType();
	       /* if ($agent->isMobile()) {
	            $fichada->dispositivo = 'Móvil';
	        } elseif ($agent->isTablet()) {
	            $fichada->dispositivo = 'Tablet';
	        } elseif ($agent->isDesktop()) {
	            $fichada->dispositivo = 'Escritorio';
	        } else {
	            $fichada->dispositivo = 'Desconocido';
	        }*/

			$fichada->save();

	  }else {
			$egreso = Carbon::now();

			//Actualizar usuario para que figure que no está trabajando
			$user = User::findOrFail(auth()->user()->id);
			$user->fichada = 0;
			$user->save();


			//Actualizar en base
			$fichada = FichadaNueva::where('id_user', auth()->user()->id)->latest()->first();
			$fichada->egreso = $egreso;

			$f_ingreso = new DateTime($fichada->ingreso);
			$f_egreso = new DateTime();
			$time = $f_ingreso->diff($f_egreso);

			$fichada->id_user = $request->id_user;
			$fichada->id_cliente = auth()->user()->id_cliente_actual;
			$fichada->ip = \Request::ip();

			$fichada->sistema_operativo = $agent->platform();
			$fichada->browser = $agent->browser();

			// Determinar el tipo de dispositivo
			$fichada->dispositivo = $agent->deviceType();
	        /*if ($agent->isMobile()) {
	            $fichada->dispositivo = 'Móvil';
	        } elseif ($agent->isTablet()) {
	            $fichada->dispositivo = 'Tablet';
	        } elseif ($agent->isDesktop()) {
	            $fichada->dispositivo = 'Escritorio';
	        } else {
	            $fichada->dispositivo = 'Desconocido';
	        }*/

			$tiempo_dedicado = $time->days . ' días ' . $time->format('%H horas %i minutos %s segundos');
			$fichada->tiempo_dedicado = $tiempo_dedicado;
			$fichada->save();
		}

		return back();

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
