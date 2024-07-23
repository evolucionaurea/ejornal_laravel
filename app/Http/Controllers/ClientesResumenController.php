<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ClienteUser;
use App\Nomina;
use App\Cliente;
use App\Ausentismo;
use App\Http\Traits\Clientes;
use App\Http\Traits\Ausentismos;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use DateTime;
use App\AusentismoTipo;
use App\CovidVacuna;

class ClientesResumenController extends Controller
{

	use Clientes,Ausentismos;


	public function index()
	{

		$id_cliente = auth()->user()->id_cliente_relacionar;

		$cliente = Cliente::where('id', $id_cliente)
			->select('clientes.nombre')
			->first();

		//// Traits > Clientes
		$output = array_merge(['cliente'=>$cliente],$this->resumen($id_cliente));

		return view('clientes.resumen', $output);

	}
	public function index_ajax()
	{

		// Traits > Ausentismos
		return $this->ausentismosAjax(auth()->user()->id_cliente_relacionar);

	}

}
