<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Grupo;
use App\Cliente;
use App\ClienteUser;
use Illuminate\Support\Facades\DB;

class AdminGruposController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

      $clientes = Cliente::where('id_grupo', '!=', null)
      ->select('id', 'nombre', 'direccion', 'id_grupo')
      ->get();

      $grupos = Grupo::select('id', 'nombre', 'direccion')->get();

      return view('admin.grupos', compact('grupos', 'clientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clientes = Cliente::all();
        return view('admin.grupos.create', compact('clientes'));
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
        'nombre' => 'required|string',
        'direccion' => 'required|string'
      ]);


      if (count($request->clientes) == 0) {
        return back()->with('error', 'Debes asociar clientes a este grupo empresario');
      }

      //Guardar en base
      $grupo = new Grupo();
      $grupo->nombre = $request->nombre;
      $grupo->direccion = $request->direccion;
      $grupo->save();

      foreach ($request->clientes as $value) {
        $cliente = Cliente::findOrFail($value);
        $cliente->id_grupo = $grupo->id;
        $cliente->save();
      }

      foreach ($request->clientes as $value) {
        $clientes = ClienteUser::where('id_cliente', $value)->get();
        foreach ($clientes as $valor) {
          $valor->id_grupo = $grupo->id;
          $valor->save();
        }
      }

      return redirect('admin/grupos')->with('success', 'Grupo guardado con éxito');
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
      $grupo = Grupo::findOrFail($id);

      return view('admin.grupos.edit', compact('grupo'));
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
        'nombre' => 'required|string',
        'direccion' => 'required|string'
      ]);

      //Actualizar en base
      $grupo = Grupo::findOrFail($id);
      $grupo->nombre = $request->nombre;
      $grupo->direccion = $request->direccion;
      $grupo->save();

      return redirect('admin/grupos')->with('success', 'Grupo actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      // Validar que no haya Clientes vinculados antes de Eliminar
      // $grupo = Grupo::find($id)->delete();
      // return redirect('admin/grupos')->with('success', 'Grupo eliminado correctamente');
    }
}
