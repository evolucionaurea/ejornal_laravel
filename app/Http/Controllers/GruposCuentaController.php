<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Grupo;
use App\User;

class GruposCuentaController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//
		$grupo = Grupo::where('id',auth()->user()->id_grupo)->first();
		return view('grupos.cuenta', compact('grupo'));
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

		if(!$request->nombre) return back()->with('error', 'No puedes guardar campos vacíos');

		$user = User::findOrFail($request->id_user);
		$user->nombre = $request->nombre;
		$user->save();

		return back()->with('success', 'Datos cambiados correctamente');
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



	public function cambiar_pass(Request $request)
	{

		$caracteres = strlen($request->password);

		if (!$request->password || $caracteres < 6) {
			return back()->with('error', 'La contraseña no puede estar vacía ni tener menos de 6 caracteres');
		}

		if ($request->password != $request->cpassword) {
			return back()->with('error', 'No conciden la contraseñas');
		}

		$user = User::findOrFail($request->id_user);
		$user->password = bcrypt($request->password);
		$user->save();

		return back()->with('success', 'Contraseña cambiada correctamente');
	}
}
