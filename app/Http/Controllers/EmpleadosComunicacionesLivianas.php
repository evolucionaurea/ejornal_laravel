<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ComunicacionLiviana;
use App\TipoComunicacionLiviana;

class EmpleadosComunicacionesLivianas extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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


    public function tipo(Request $request)
	{

		$validatedData = $request->validate([
		  'nombre' => 'required|string'
		]);

		//Guardar en base
		$tipo_comunicacion = new TipoComunicacionLiviana();
		$tipo_comunicacion->nombre = $request->nombre;
		$tipo_comunicacion->save();

		return back()->with('success', 'Tipo de comunicación creado con éxito');
	}


    public function tipo_destroy($id_tipo)
	{

	  $comunicacion = ComunicacionLiviana::where('id_tipo', $id_tipo)->get();

	  if (!empty($comunicacion) && count($comunicacion) > 0) {
		return back()->with('error', 'Existen comunicaciones creadas con este tipo de comunicacion. No puedes eliminarla');
	  }

		//Eliminar en base
		$tipo_comunicacion = TipoComunicacionLiviana::find($id_tipo)->delete();
		return back()->with('success', 'Tipo de comunicacion eliminada correctamente');
	}

}
