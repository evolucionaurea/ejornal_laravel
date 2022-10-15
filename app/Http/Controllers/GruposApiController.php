<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Http\Traits\ClientesGrupo;

class GruposApiController extends Controller
{
  use ClientesGrupo;

  public function index()
  {
      $cliente = Cliente::where('id', auth()->user()->id_cliente_actual)
      ->select('clientes.nombre', 'clientes.token')
      ->first();

      $output = array_merge($this->getClientesGrupo(),[
  			'cliente'=>$cliente
  		]);

  		return view('grupos.api',$output);
  }

}
