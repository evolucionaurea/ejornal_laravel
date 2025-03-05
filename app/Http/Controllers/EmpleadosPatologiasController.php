<?php

namespace App\Http\Controllers;

use App\Caratula;
use App\Patologia;
use Illuminate\Http\Request;

class EmpleadosPatologiasController extends Controller
{
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
        $validatedData = $request->validate([
			'nombre' => 'required'
		]);

        $patologia = new Patologia();
        $patologia->nombre = $request->nombre;
        $patologia->user = auth()->user()->nombre;
        $patologia->save();
        return back()->with('success', 'Guardado exitosamente');
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
        $patologia = Patologia::find($id);
        $patologia->nombre = $request->nombre;
        $patologia->save();
        return back()->with('success', 'Guardado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $caratulas = Caratula::where('id_patologia', $id)->count();
        if ($caratulas > 0) {
            return back()->with(
                'error', 
                'No puedes eliminar esta patologia porque es utilizada por algunas caratulas. Deberia cambiar todas las caratulas donde
                se vea esta patologia para poder eliminarla.'
            );
        }
        $patologia = Patologia::find($id);
        $patologia->delete();
        return back()->with('success', 'Eliminado exitosamente');
    }
}
