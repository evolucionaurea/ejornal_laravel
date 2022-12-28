<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Cliente;
use App\AusentismoTipo;
use App\Http\Traits\Ausentismos;

class ClientesAusentismosController extends Controller
{

	use Ausentismos;

	public function index()
	{

		$cliente = Cliente::where('id', auth()->user()->id_cliente_relacionar)
		->select('clientes.nombre')
		->first();

		$tipos = AusentismoTipo::get();

		return view('clientes.ausentismos', compact('cliente','tipos'));
	}


	public function busqueda(Request $request)
	{

		$this->request = $request;

		//Traits > Ausentismos
		return $this->searchAusentismos(auth()->user()->id_cliente_relacionar);

	}

	public function exportar()
	{
		//Traits > Ausentismos
		return $this->exportAusentismos(auth()->user()->id_cliente_relacionar);
	}


}
