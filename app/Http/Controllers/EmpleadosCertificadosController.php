<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\ClienteUser;
use App\Http\Traits\Clientes;
use App\AusentismoDocumentacion;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmpleadosCertificadosController extends Controller
{

  use Clientes;

	public function listado()
	{
	  $clientes = $this->getClientesUser();

	  return view('empleados.ausentismos.certificados', compact('clientes'));
	}

	public function busqueda(Request $request)
	{
		$query = AusentismoDocumentacion::select(
			'nominas.nombre',
			DB::raw('ausentismo_tipo.nombre tipo'),
			'ausentismos.fecha_inicio', 'ausentismos.fecha_final', 'ausentismos.fecha_regreso_trabajar',
			'ausentismo_documentacion.medico', 'ausentismo_documentacion.matricula_nacional', 'ausentismo_documentacion.institucion'
		)
		->join('ausentismos', 'ausentismo_documentacion.id_ausentismo', 'ausentismos.id')
	  ->join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
	  ->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
	  ->where('nominas.id_cliente', auth()->user()->id_cliente_actual);


	  if($request->from) $query->whereDate('ausentismos.fecha_inicio','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		if($request->to) $query->whereDate('ausentismos.fecha_final','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));


		return [
			'results'=>$query->get(),
			'fichada'=>auth()->user()->fichada,
			'request'=>$request->all()
		];

	}


}
