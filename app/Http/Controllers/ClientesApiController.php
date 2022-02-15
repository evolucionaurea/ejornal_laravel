<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;

class ClientesApiController extends Controller
{

    public function index()
    {
        $cliente = Cliente::where('id', auth()->user()->id_cliente_relacionar)
        ->select('clientes.nombre', 'clientes.token')
        ->first();

        return view('clientes.api', compact('cliente'));
    }

}
