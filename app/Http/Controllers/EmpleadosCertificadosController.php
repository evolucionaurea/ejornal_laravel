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
		$query = AusentismoDocumentacion::select('ausentismo_documentacion.*')
			->with(['ausentismo'=>function($query){
				$query
					->select('id','fecha_inicio','fecha_final','fecha_regreso_trabajar','id_trabajador','id_tipo')
					->with(['trabajador'=>function($query){
						$query->select('id','nombre');
					}])
					->with(['tipo'=>function($query){
						$query->select('id','nombre');
					}]);
			}])
			->whereHas('ausentismo',function($query){
				$query->whereHas('trabajador',function($query){
					$query->where('id_cliente',auth()->user()->id_cliente_actual);
				});
			})
			->join('ausentismos', 'ausentismo_documentacion.id_ausentismo', 'ausentismos.id')
			->join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
			->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id');

		$total = $query->count();


		if($request->from) {
			//$query->whereDate('ausentismos.fecha_inicio','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
			$query->whereHas('ausentismo',function($query){
				$query->where('fecha_inicio','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
			});
		}

		if($request->to){
			//$query->whereDate('ausentismos.fecha_final','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
			$query->whereHas('ausentismo',function($query){
				$query->where('fecha_final','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
			});
		}


		if($request->order){
			$sort = $request->columns[$request->order[0]['column']]['name'];
			$dir  = $request->order[0]['dir'];
			$query->orderBy($sort,$dir);
		}

		$total_filtered = $query->count();


		return [
			'draw'=>$request->draw,
			'recordsTotal'=>$total,
			'recordsFiltered'=>$total_filtered,
			'data'=>$query->skip($request->start)->take($request->length)->get(),
			'request'=>$request->all(),
			'fichada_user'=>auth()->user()->fichada,
		];


		/*return [
			'results'=>$query->get(),
			'fichada_user'=>auth()->user()->fichada,
			'request'=>$request->all()
		];*/

	}


}
