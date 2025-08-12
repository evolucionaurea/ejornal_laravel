<?php

namespace App\Http\Controllers;

use App\AgendaMotivo;
use Illuminate\Http\Request;

class AdminAgendaMotivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $motivos = AgendaMotivo::all();
        return view('admin.agenda_motivos', compact('motivos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.agenda_motivos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $motivo = new AgendaMotivo();
        $motivo->nombre = $request->input('nombre');
        $motivo->save();

        return redirect()->route('/admin/agenda_motivos');
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
        $motivo = AgendaMotivo::findOrFail($id);
        return view('admin.agenda_motivos.edit', compact('motivo'));
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
        $motivo = AgendaMotivo::findOrFail($id);
        $motivo->nombre = $request->input('nombre');
        $motivo->save();

        return redirect()->route('/admin/agenda_motivos');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Hay que validar si el motivo está en alguna agenda antes de eliminarlo
        return redirect()->back()->withErrors(['error' => 'Se debe terminar aun. No se puede eliminar el motivo si está asociado a una agenda.']);
        $motivo = AgendaMotivo::findOrFail($id);
        $motivo->delete();

        return redirect()->route('/admin/agenda_motivos');
    }
}
