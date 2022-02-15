<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ausentismo;
use Illuminate\Support\Facades\DB;
use App\Cliente;

class ClientesAusentismosController extends Controller
{

  public function index()
  {

    $cliente = Cliente::where('id', auth()->user()->id_cliente_relacionar)
    ->select('clientes.nombre')
    ->first();

    $ausentismos = Ausentismo::join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
    ->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
    ->where('nominas.id_cliente', auth()->user()->id_cliente_relacionar)
    ->select('ausentismos.*', 'nominas.nombre', 'nominas.email', 'nominas.telefono', 'nominas.dni', 'nominas.estado', DB::raw('ausentismo_tipo.nombre nombre_ausentismo'))
    ->get();

    return view('clientes.ausentismos', compact('ausentismos', 'cliente'));
  }


}
