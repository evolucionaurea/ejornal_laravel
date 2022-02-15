<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\ClienteUser;

class EmpleadosLiquidacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $clientes = ClienteUser::join('clientes', 'cliente_user.id_cliente', 'clientes.id')
      ->where('cliente_user.id_user', '=', auth()->user()->id)
      ->select('clientes.nombre', 'clientes.id')
      ->get();
      
      return view('empleados.liquidacion', compact('clientes'));
    }


}
