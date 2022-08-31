<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ausentismo;
use App\AusentismoTipo;
use App\Http\Traits\ClientesGrupo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GruposAusentismosController extends Controller
{
	use ClientesGrupo;

	public function index()
	{
		$tipos = AusentismoTipo::get();
		return view('grupos.ausentismos', array_merge($this->getClientesGrupo(),['tipos'=>$tipos]));
	}


	public function busqueda(Request $request)
	{

	  $query = Ausentismo::select(
	  	'ausentismos.*',
	  	'nominas.nombre',
	  	'nominas.email',
	  	'nominas.telefono',
	  	'nominas.dni',
	  	'nominas.estado',
	  	DB::raw('ausentismo_tipo.nombre nombre_ausentismo'),
	  	'nominas.sector'
	  )
	  ->join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
	  ->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
	  ->where('nominas.id_cliente', auth()->user()->id_cliente_actual);

		if($request->from) $query->whereDate('ausentismos.fecha_inicio','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		if($request->to) $query->whereDate('ausentismos.fecha_final','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
		if($request->tipo) $query->where('ausentismos.id_tipo',$request->tipo);

		return [
			'results'=>$query->get(),
			'request'=>$request->all()
		];

	}

}
