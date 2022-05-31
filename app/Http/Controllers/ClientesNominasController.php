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
      ///$nominas = Nomina::where('id_cliente', auth()->user()->id_cliente_relacionar)->get();
      return view('clientes.nominas', compact('cliente'));
    }

    public function busqueda(Request $request)
    {

    	$query = Nomina::where('id_cliente', auth()->user()->id_cliente_relacionar);

    	if(!is_null($request->estado)) $query->where('estado',$request->estado);

    	return [
				'results'=>$query->get(),
				'fichada'=>auth()->user()->fichada,
				'request'=>$request->all()
			];

    }

}
