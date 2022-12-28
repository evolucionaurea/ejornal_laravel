<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Nomina;
use App\Cliente;
use App\Http\Traits\Nominas;

class ClientesNominasController extends Controller
{

  use Nominas;

  public function index()
  {
    $cliente = Cliente::where('id', auth()->user()->id_cliente_relacionar)
    ->select('clientes.nombre')
    ->first();
    ///$nominas = Nomina::where('id_cliente', auth()->user()->id_cliente_relacionar)->get();
    return view('clientes.nominas', compact('cliente'));
  }

  public function busqueda(Request $request)
  {

    $this->request = $request;

    //Traits > Nominas
    return $this->searchNomina(auth()->user()->id_cliente_relacionar);


  }

  public function exportar()
  {

    //Traits > Nominas
    return $this->exportNomina(auth()->user()->id_cliente_relacionar);
  }

}
