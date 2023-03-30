<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ausentismo;
use App\AusentismoTipo;
use App\Http\Traits\ClientesGrupo;
use App\Http\Traits\Ausentismos;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GruposAusentismosController extends Controller
{
	use ClientesGrupo,Ausentismos;

	public function index()
	{
		$tipos = AusentismoTipo::get();
		return view('grupos.ausentismos', array_merge($this->getClientesGrupo(),['tipos'=>$tipos]));
	}


	public function busqueda(Request $request)
	{
		//Traits > Ausentismos
		return $this->searchAusentismos(auth()->user()->id_cliente_actual,$request);
	}

	public function exportar()
	{
		//Traits > Ausentismos
		return $this->exportAusentismos(auth()->user()->id_cliente_actual);
	}

}
