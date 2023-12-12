<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Grupo;
use App\Cliente;
use App\ClienteUser;
use App\User;
use App\ClienteGrupo;
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

    // Query simplificada para traer grupos y los clientes asociados
    $grupos = Grupo::with('clientes')->get();

    /*foreach ($grupos as $grupo) {
      $busqueda = ClienteGrupo::where('id_grupo', $grupo->id)->get();
      $array = array();
      foreach ($busqueda as $value) {
        $cliente = Cliente::find($value->id_cliente);
        array_push($array, $cliente->nombre);
      }
      $grupo['clientes'] = $array;
    }*/

    return view('admin.grupos', compact('grupos'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
      $clientes = Cliente::orderBy('nombre', 'asc')->get();
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


    if (!$request->clientes || count($request->clientes) == 0) {
      return back()->with('error', 'Debes asociar clientes a este grupo empresario');
    }

    //Guardar en base
    $grupo = new Grupo();
    $grupo->nombre = $request->nombre;
    $grupo->direccion = $request->direccion;
    $grupo->save();

    foreach ($request->clientes as $value) {
      $cliente_grupo = new ClienteGrupo();
      $cliente_grupo->id_cliente = $value;
      $cliente_grupo->id_grupo = $grupo->id;
      $cliente_grupo->save();
    }

    return redirect('admin/grupos')->with('success', 'Grupo guardado con éxito');
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
      $busqueda = ClienteGrupo::where('id_grupo', $id)->get();
      $clientes_seleccionados = array_column($busqueda->toArray(), 'id_cliente');
      $clientes = Cliente::orderBy('nombre', 'asc')->get();

      return view('admin.grupos.edit', compact('grupo', 'clientes', 'clientes_seleccionados'));
    }




    public function update(Request $request, $id)
    {

      $validatedData = $request->validate([
        'nombre' => 'required|string',
        'direccion' => 'required|string'
      ]);

      if (!$request->clientes || count($request->clientes) == 0) {
        return back()->with('error', 'Debes asociar clientes a este grupo empresario');
      }

      //Actualizar en base
      $grupo = Grupo::findOrFail($id);
      $grupo->nombre = $request->nombre;
      $grupo->direccion = $request->direccion;
      $grupo->save();

      ClienteGrupo::where('id_grupo',$id)->delete();

      foreach ($request->clientes as $value) {
        $cliente_grupo = new ClienteGrupo();
        $cliente_grupo->id_cliente = $value;
        $cliente_grupo->id_grupo = $grupo->id;
        $cliente_grupo->save();
      }


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
    //De momento no contemplamos eliminar
    // $grupo = Grupo::find($id)->delete();
    // return redirect('admin/grupos')->with('success', 'Grupo eliminado correctamente');
  }
}
