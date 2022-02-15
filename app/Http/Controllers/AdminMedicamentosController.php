<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Medicamento;

class AdminMedicamentosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $medicamentos = Medicamento::all();
      return view('admin.medicamentos', compact('medicamentos'));
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
        'nombre' => 'required|string'
      ]);

      //Guardar en base
      $medicamento = new Medicamento();
      $medicamento->nombre = $request->nombre;
      $medicamento->save();

      return back()->with('success', 'Medicamento guardado con éxito');

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
      $medicamento = Medicamento::findOrFail($id);
      return view('admin.medicamentos.edit', compact('medicamento'));

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

      $validatedData = $request->validate([
        'nombre' => 'required|string'
      ]);

      //Actualizar en base
      $medicamento = Medicamento::findOrFail($id);
      $medicamento->nombre = $request->nombre;
      $medicamento->save();

      return redirect('admin/medicamentos')->with('success', 'Medicamento actualizado con éxito');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $medicamento = Medicamento::find($id)->delete();
      return back()->with('success', 'Medicamento eliminado correctamente');
    }
}
