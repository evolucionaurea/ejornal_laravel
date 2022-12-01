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

		$query = Ausentismo::
			whereHas('trabajador',function($query){
				return $query->where('id_cliente',auth()->user()->id_cliente_actual);
			})
			->join('nominas','nominas.id','=','ausentismos.id_trabajador')
			->join('ausentismo_tipo','ausentismo_tipo.id','=','ausentismos.id_tipo')
			->select(
				'ausentismos.*',
				'nominas.nombre as trabajador_nombre',
				'nominas.id_cliente',
				'ausentismo_tipo.nombre as ausentismo_tipo'
			);

		$query->where(function($query) use ($request) {
			$filtro = '%'.$request->search['value'].'%';
			$query->where('nominas.nombre','like',$filtro);
		});


		if($request->from) $query->whereDate('fecha_inicio','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		if($request->to) $query->whereDate('fecha_inicio','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
		if($request->tipo) $query->where('id_tipo',$request->tipo);


		if($request->order){
			$sort = $request->columns[$request->order[0]['column']]['name'];
			$dir  = $request->order[0]['dir'];
			$query->orderBy($sort,$dir);
		}

		///return $query->get();

	 /* $query = Ausentismo::select(
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
	  ->where('nominas.id_cliente', auth()->user()->id_cliente_actual);*/


		return [
			'draw'=>$request->draw,
			'recordsTotal'=>$query->count(),
			'recordsFiltered'=>$query->count(),
			'data'=>$query->skip($request->start)->take($request->length)->get(),
			'request'=>$request->all()
		];

	}

}
