<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
//use App\ClienteUser;
use App\Http\Traits\Clientes;
use App\AusentismoDocumentacion;
use App\Cliente;

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
			->with('archivos')
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
			$query->whereHas('ausentismo',function($query){
				$query->where('fecha_inicio','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
			});
		}
		if($request->to){
			$query->whereHas('ausentismo',function($query){
				$query->where('fecha_final','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
			});
		}
		if($request->search){
			$filtro = '%'.$request->search.'%';

			$query->where(function($query) use($filtro){
				$query
					->whereHas('ausentismo',function($query) use($filtro){
						$query->whereHas('trabajador',function($query) use($filtro){
							$query->where('nombre','LIKE',$filtro);
						});
					})
					->orWhere('institucion','LIKE',$filtro)
					->orWhere('medico','LIKE',$filtro);
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
			'fichar_user'=>auth()->user()->fichar
		];


	}

	public function exportar(Request $request)
	{

		if(!auth()->user()->id_cliente_actual) dd('debes seleccionar un cliente');

		$cliente = Cliente::where('id',auth()->user()->id_cliente_actual)->first();

		$request->start = 0;
		$request->length = 5000;
		$results = $this->busqueda($request);

		$now = Carbon::now();
		$file_name = 'certificados-'.Str::slug($cliente->nombre).'-'.$now->format('YmdHis').'.csv';
		//dd($results['data']);


		$fp = fopen('php://memory', 'w');
		fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
		fputcsv($fp,[
			'Trabajador',
			'Médico',
			'Matricula Nacional',
			'Matricula Provincial',
			'Institución',
			'Fecha Documento',
			'Fecha de Carga'
		],';');

		foreach($results['data'] as $row){

			$values =

			fputcsv($fp,[
				$row->ausentismo->trabajador->nombre,
				$row->medico,
				$row->matricula_nacional,
				$row->matricula_provincial,
				$row->institucion,

				$row->fecha_documento->format('d/m/Y'),
				$row->created_at->format('d/m/Y')
			],';');
		}

		fseek($fp, 0);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="'.$file_name.'";');
		fpassthru($fp);

		return;

	}


}
