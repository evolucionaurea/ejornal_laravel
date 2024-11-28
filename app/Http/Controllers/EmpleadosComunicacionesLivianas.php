<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ComunicacionLiviana;
use App\TipoComunicacionLiviana;
use App\Http\Traits\Clientes;
use Carbon\Carbon;
use App\Nomina;
use App\TareaLiviana;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class EmpleadosComunicacionesLivianas extends Controller
{
	use Clientes;

	public function index()
	{
		$clientes = $this->getClientesUser();
		return view('empleados.comunicaciones_livianas', compact('clientes'));
	}


	public function busqueda(Request $request)
	{

		$query = ComunicacionLiviana::select(
			'comunicaciones_livianas.id_tarea_liviana',
			'nominas.id',
			'nominas.nombre',
			'nominas.email',
			DB::raw('tipos_comunicaciones_livianas.nombre tipo'),
			'comunicaciones_livianas.created_at',
			'nominas.estado',
			'nominas.id_cliente as trabajador_cliente'
		)
		->join('tareas_livianas', 'comunicaciones_livianas.id_tarea_liviana', 'tareas_livianas.id')
		->join('nominas', 'tareas_livianas.id_trabajador', 'nominas.id')
		->join('tipos_comunicaciones_livianas', 'comunicaciones_livianas.id_tipo', 'tipos_comunicaciones_livianas.id')
		->with('tareaLiviana.trabajador')
		->where('tareas_livianas.id_cliente', auth()->user()->id_cliente_actual);

		if($request->from) $query->whereDate('comunicaciones_livianas.created_at','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		if($request->to) $query->whereDate('comunicaciones_livianas.created_at','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));


		return [
			'results'=>$query->get(),
			'fichada_user'=>auth()->user()->fichada,
			'fichar_user'=>auth()->user()->fichar,
			'request'=>$request->all()
		];

	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{

		$validatedData = $request->validate([
			'descripcion' => 'required'
		  ]);

		  //Guardar en base Comunicaciones
		  $comunicacion_liviana = new ComunicacionLiviana();
		  $comunicacion_liviana->id_tarea_liviana = $request->id_tarea_liviana;
		  $comunicacion_liviana->id_tipo = $request->id_tipo;
		  $comunicacion_liviana->descripcion = $request->descripcion;
		  $comunicacion_liviana->user = auth()->user()->nombre;
		  $comunicacion_liviana->save();

		  return redirect('empleados/comunicaciones_livianas/'.$request->id_tarea_liviana)->with('success', 'Comunicación liviana guardada con éxito');

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{

		$tarea_liviana = TareaLiviana::join('nominas', 'tareas_livianas.id_trabajador', 'nominas.id')
			->join('tareas_livianas_tipos', 'tareas_livianas.id_tipo', 'tareas_livianas_tipos.id')
			->where('tareas_livianas.id', $id)
			->where('tareas_livianas.id_cliente', auth()->user()->id_cliente_actual)
			->select(
				'nominas.nombre', 'nominas.email', 'nominas.estado', 'nominas.telefono',
				DB::raw('tareas_livianas_tipos.nombre nombre_tarea_liviana'), 'tareas_livianas.fecha_inicio', 'tareas_livianas.fecha_final',
				'tareas_livianas.fecha_regreso_trabajar', 'tareas_livianas.archivo', 'tareas_livianas.id'
			)
			->first();

	  $comunicacione_tarea_liviana = ComunicacionLiviana::join('tipos_comunicaciones_livianas', 'comunicaciones_livianas.id_tipo',
	  'tipos_comunicaciones_livianas.id')
		->where('id_tarea_liviana', $id)
		->select('comunicaciones_livianas.id', 'tipos_comunicaciones_livianas.nombre', 'comunicaciones_livianas.descripcion',
		'comunicaciones_livianas.updated_at')
		->get();

	  $tipos_comunicaciones_livianas = TipoComunicacionLiviana::orderBy('nombre', 'asc')->get();


	  $clientes = $this->getClientesUser();

	  return view('empleados.comunicaciones_livianas.show', compact('tarea_liviana', 'clientes', 'comunicacione_tarea_liviana',
	  'tipos_comunicaciones_livianas'));

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}


	public function tipo(Request $request)
	{

		$validatedData = $request->validate([
		  'nombre' => 'required|string'
		]);

		//Guardar en base
		$tipo_comunicacion = new TipoComunicacionLiviana();
		$tipo_comunicacion->nombre = $request->nombre;
		$tipo_comunicacion->save();

		return back()->with('success', 'Tipo de comunicación creado con éxito');
	}


	public function tipo_destroy($id_tipo)
	{

	  $comunicacion = ComunicacionLiviana::where('id_tipo', $id_tipo)->get();

	  if (!empty($comunicacion) && count($comunicacion) > 0) {
		return back()->with('error', 'Existen comunicaciones creadas con este tipo de comunicacion. No puedes eliminarla');
	  }

		//Eliminar en base
		$tipo_comunicacion = TipoComunicacionLiviana::find($id_tipo)->delete();
		return back()->with('success', 'Tipo de comunicacion eliminada correctamente');
	}


	public function getComunicacionLiviana($id)
	{
	  $comunicacion_de_tarea_liviana = ComunicacionLiviana::find($id);
	  return response()->json($comunicacion_de_tarea_liviana);
	}

}
