<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Nomina;
use App\Cliente;

class ClientesNominasController extends Controller
{

    public function index()
    {
      $cliente = Cliente::where('id', auth()->user()->id_cliente_relacionar)
      ->select('clientes.nombre')
      ->first();
      $nominas = Nomina::where('id_cliente', auth()->user()->id_cliente_relacionar)->get();
      return view('clientes.nominas', compact('nominas', 'cliente'));
    }

}
