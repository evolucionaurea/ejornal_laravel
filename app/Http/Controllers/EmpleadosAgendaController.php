<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\Clientes;
use App\User;

class EmpleadosAgendaController extends Controller
{

	use Clientes;

  public function index(){

  	$clientes = $this->getClientesUser();
  	return view('empleados.agenda',compact('clientes'));
  }
}
