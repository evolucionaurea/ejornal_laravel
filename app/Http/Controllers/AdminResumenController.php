<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Cliente;

class AdminResumenController extends Controller
{

  public function index()
  {
    $clientes = Cliente::all()->count();
    $enfermeros = User::where('id_especialidad', 2)->where('estado', 1)->count();
    $medicos = User::where('id_especialidad', 1)->where('estado', 1)->count();

    $enfermeros_trabajando = User::where('id_especialidad', 2)->where('estado', 1)->where('fichada', 1)->count();
    $medicos_trabajando = User::where('id_especialidad', 1)->where('estado', 1)->where('fichada', 1)->count();

    return view('admin.resumen', compact('clientes', 'enfermeros', 'medicos', 'enfermeros_trabajando', 'medicos_trabajando'));
  }


  public function create()
  {
      //
  }


  public function store(Request $request)
  {
      //
  }


  public function show($id)
  {
      //
  }


  public function edit($id)
  {
      //
  }


  public function update(Request $request, $id)
  {
      //
  }


  public function destroy($id)
  {
      //
  }


}
