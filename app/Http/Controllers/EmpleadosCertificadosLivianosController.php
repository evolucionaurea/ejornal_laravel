<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\Clientes;
use App\TareaLivianaDocumentacion;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmpleadosCertificadosLivianosController extends Controller
{
    use Clientes;

    public function listado()
	{
	  $clientes = $this->getClientesUser();

	  return view('empleados.tareas_livianas.certificados', compact('clientes'));
	}


    public function busqueda(Request $request)
	{
		$query = TareaLivianaDocumentacion::select(
			'tarea_liviana_documentacion.id_tarea_liviana',
			'nominas.nombre',
			DB::raw('tareas_livianas_tipos.nombre tipo'),
			'tareas_livianas.fecha_inicio',
			'tareas_livianas.fecha_final',
			'tareas_livianas.fecha_regreso_trabajar',
			'tarea_liviana_documentacion.medico',
			'tarea_liviana_documentacion.matricula_nacional',
			'tarea_liviana_documentacion.institucion',
			'nominas.id_cliente as trabajador_cliente',
			'tareas_livianas.id_cliente'
		)
		->join('tareas_livianas', 'tarea_liviana_documentacion.id_tarea_liviana', 'tareas_livianas.id')
	  ->join('nominas', 'tareas_livianas.id_trabajador', 'nominas.id')
	  ->join('tareas_livianas_tipos', 'tareas_livianas.id_tipo', 'tareas_livianas_tipos.id')
	  ->with('tareaLiviana.trabajador')
	  ->where('tareas_livianas.id_cliente', auth()->user()->id_cliente_actual);


	  if($request->from) $query->whereDate('tareas_livianas.fecha_inicio','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		if($request->to) $query->whereDate('tareas_livianas.fecha_final','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));


		return [
			'results'=>$query->get(),
			'fichada_user'=>auth()->user()->fichada,
			'fichar_user'=>auth()->user()->fichar,
			'request'=>$request->all()
		];

	}

}
