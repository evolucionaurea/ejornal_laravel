<?php

namespace App\Http\Controllers;

use App\ComunicacionLiviana;
use Illuminate\Http\Request;
use App\Http\Traits\Clientes;
use App\Http\Traits\TareasLivianas;
use App\TareaLivianaTipo;
use App\TareaLiviana;
use Carbon\Carbon;
use App\TipoComunicacionLiviana;
use App\Nomina;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmpleadoTareasLivianasController extends Controller
{

	use Clientes,TareasLivianas;

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//$fecha_actual = Carbon::now();
		$clientes = $this->getClientesUser();
		$tipos = TareaLivianaTipo::orderBy('nombre', 'asc')->get();
		$tipos_comunicacion = TipoComunicacionLiviana::orderBy('nombre', 'asc')->get();
		return view('empleados.tareas_livianas', compact('clientes','tipos', 'tipos_comunicacion'));
	}


	public function busqueda(Request $request)
	{

		$request->merge(['idcliente' => auth()->user()->id_cliente_actual]);
		return $this->searchTareasLivianas($request);


		$query = TareaLiviana::select(
			'tareas_livianas.*',
			'nominas.nombre',
			'nominas.legajo',
			'nominas.id_cliente as trabajador_cliente',
			'nominas.email',
			'nominas.telefono',
			'nominas.dni',
			'nominas.estado',
			'nominas.sector',
			'tareas_livianas_tipos.nombre as nombre_tarea_liviana'
		)
		->join('nominas', 'tareas_livianas.id_trabajador', 'nominas.id')
		->join('tareas_livianas_tipos', 'tareas_livianas.id_tipo', 'tareas_livianas_tipos.id')
		->where('tareas_livianas.id_cliente', auth()->user()->id_cliente_actual)
		->with('trabajador');

		$query->where(function($query) use ($request) {
			$filtro = '%'.$request->search.'%';
			$query->where('nominas.nombre','like',$filtro)
				->orWhere('nominas.legajo','like',$filtro)
				->orWhere('nominas.email','like',$filtro)
				->orWhere('nominas.dni','like',$filtro)
				->orWhere('nominas.telefono','like',$filtro)
				->orWhere('nominas.sector','like',$filtro);
		});

		if($request->from) $query->whereDate('tareas_livianas.fecha_inicio','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		if($request->to) $query->whereDate('tareas_livianas.fecha_final','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
		if($request->tipo) $query->where('id_tipo',$request->tipo);

		if($request->order){
			$sort = $request->columns[$request->order[0]['column']]['name'];
			$dir  = $request->order[0]['dir'];
			$query->orderBy($sort,$dir);
		}

		return [
			'draw'=>$request->draw,
			'recordsTotal'=>$query->count(),
			'recordsFiltered'=>$query->count(),
			'data'=>$query->skip($request->start)->take($request->length)->get(),
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
		$trabajadores = Nomina::where('id_cliente', auth()->user()->id_cliente_actual)
		->where('estado', '=', 1)
		->orderBy('nombre', 'asc')
		->get();
		$tareas_livianas_tipo = TareaLivianaTipo::orderBy('nombre', 'asc')->get();
		$clientes = $this->getClientesUser();
		$tipo_comunicacion_liviana = TipoComunicacionLiviana::orderBy('nombre', 'asc')->get();

		return view('empleados.tareas_livianas.create', compact('trabajadores', 'tareas_livianas_tipo', 'clientes', 'tipo_comunicacion_liviana'));
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
			'trabajador' => 'required',
			'tipo' => 'required',
			'fecha_inicio' => 'required',
			'tipo_comunicacion' => 'required',
			'descripcion' => 'required|string'
		]);
		$fecha_actual = Carbon::now();
		$fecha_inicio = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio);

		$buscar_tarea_liviana = TareaLiviana::where('id_trabajador', $request->trabajador)->where('fecha_regreso_trabajar', null)->get();
		if (count($buscar_tarea_liviana) != 0) {
			return back()->withInput()->with('error', 'No puedes crear una tarea liviana nueva mientras exista una sin terminar');
		}

			$dos_dias_adelante = Carbon::now()->addDays(2);
			$un_anio_atras = Carbon::now()->subYear(1);
			if ($fecha_inicio > $dos_dias_adelante) {
				return back()->withInput()->with('error', 'La fecha de inicio no puede ser superior a dos días adelante de la fecha actual');
			}
			if ($fecha_inicio->lessThan($un_anio_atras)) {
				return back()->withInput()->with('error', 'La fecha de inicio puede ser hasta un año atrás, no más');
			}

		if (isset($request->fecha_final) && !empty($request->fecha_final) && !is_null($request->fecha_final)) {
			$fecha_final = Carbon::createFromFormat('d/m/Y', $request->fecha_final);

			if ($fecha_inicio->greaterThan($fecha_final)) {
				return back()->withInput()->with('error', 'La fecha de inicio no puede ser superior a la fecha final o quizás lo dejó vacío');
			}
		}

		if (isset($request->fecha_regreso_trabajar) && !empty($request->fecha_regreso_trabajar) && !is_null($request->fecha_regreso_trabajar)) {
			$fecha_regreso_trabajar = Carbon::createFromFormat('d/m/Y', $request->fecha_regreso_trabajar);

			if (!isset($request->fecha_final) && empty($request->fecha_final) && !is_null($request->fecha_final)) {
					return back()->withInput()->with('error', 'No puedes ingresar una fecha de regreso al trabajo sin cargar una fecha final');
				}

			if ($fecha_final->greaterThan($fecha_regreso_trabajar)) {
				return back()->withInput()->with('error', 'La fecha final no puede ser mayor que la fecha de regreso al trabajo');
			}
		}


		//Guardar en base Tarea Liviana
		$tarea_liviana = new TareaLiviana();
		$tarea_liviana->id_trabajador = $request->trabajador;
		$tarea_liviana->id_tipo = $request->tipo;
		$tarea_liviana->fecha_inicio = $fecha_inicio;

		$tarea_liviana->id_cliente = auth()->user()->id_cliente_actual;

		if (isset($request->fecha_final) && !empty($request->fecha_final)) {
			$tarea_liviana->fecha_final = $fecha_final;
		}else {
			$tarea_liviana->fecha_final = null;
		}
		if (isset($request->fecha_regreso_trabajar) && !empty($request->fecha_regreso_trabajar)) {
			$tarea_liviana->fecha_regreso_trabajar = $fecha_regreso_trabajar;
		}else {
			$tarea_liviana->fecha_regreso_trabajar = null;
		}

		// Si hay un archivo adjunto
		if ($request->hasFile('archivo') && $request->file('archivo') > 0) {

			$archivo = $request->file('archivo');
			$nombre = $archivo->getClientOriginalName();
			$tarea_liviana->archivo = $nombre;

		}
		$tarea_liviana->user = auth()->user()->nombre;
		$tarea_liviana->save();

		// Si hay un archivo adjunto
		if ($request->hasFile('archivo') && $request->file('archivo') > 0) {

		Storage::disk('local')->put('tareas_livianas/trabajador/'.$tarea_liviana->id, $archivo);

		// Completar el base el hasg del archivo guardado
		$tarea_liviana = TareaLiviana::findOrFail($tarea_liviana->id);
		$tarea_liviana->hash_archivo = $archivo->hashName();
		$tarea_liviana->save();

		}


		//Guardar en base Comunicacion
		$comunicacion_liviana = new ComunicacionLiviana();
		$comunicacion_liviana->id_tarea_liviana = $tarea_liviana->id;
		$comunicacion_liviana->id_tipo = $request->tipo_comunicacion;
		$comunicacion_liviana->user = auth()->user()->nombre;
		$comunicacion_liviana->descripcion = $request->descripcion;
		$comunicacion_liviana->save();


		return redirect('empleados/tareas_livianas')->with('success', 'Tarea adecuada y Comunicación guardados con éxito');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		// Aqui veremos el historial de tareas livianas
		$tareas_livianas = TareaLiviana::join('nominas', 'tareas_livianas.id_trabajador', 'nominas.id')
		->join('tareas_livianas_tipos', 'tareas_livianas.id_tipo', 'tareas_livianas_tipos.id')
		->join('comunicaciones_livianas', 'tareas_livianas.id', 'comunicaciones_livianas.id_tarea_liviana')
		->join('tipos_comunicaciones_livianas', 'comunicaciones_livianas.id_tipo', 'tipos_comunicaciones_livianas.id')
		->where('tareas_livianas.id_trabajador', $id)
		->where('tareas_livianas.id_cliente', auth()->user()->id_cliente_actual)
		->select(
			'nominas.nombre',
			'nominas.email',
			'nominas.estado',
			'nominas.telefono',
			'nominas.id_cliente as trabajador_cliente',
			DB::raw('tareas_livianas_tipos.nombre nombre_tarea_liviana'),
			'tareas_livianas.fecha_inicio',
			'tareas_livianas.fecha_final',
			'tareas_livianas.fecha_regreso_trabajar',
			'tareas_livianas.archivo',
			'tareas_livianas.id',
			DB::raw('tareas_livianas.user user'),
			DB::raw('comunicaciones_livianas.descripcion descripcion_comunicacion_liviana'),
			DB::raw('tipos_comunicaciones_livianas.nombre tipo_comunicacion_liviana')
		)
		->get();

		$clientes = $this->getClientesUser();

		return view('empleados.tareas_livianas.show', compact('tareas_livianas', 'clientes'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		/*$tarea_liviana = TareaLiviana::join('nominas', 'tareas_livianas.id_trabajador', 'nominas.id')
				->join('tareas_livianas_tipos', 'tareas_livianas.id_tipo', 'tareas_livianas_tipos.id')
				->where('nominas.id_cliente', auth()->user()->id_cliente_actual)
				->where('tareas_livianas.id', $id)
				->select('tareas_livianas.*', 'nominas.nombre', 'nominas.email', 'nominas.telefono', 'nominas.dni', 'nominas.estado', DB::raw('tareas_livianas_tipos.nombre nombre_tarea_liviana'))
				->first();*/

		$tarea_liviana = TareaLiviana::select('tareas_livianas.*')
			->with('comunicacion')
			->with('tipo')
			->with(['trabajador'=>function($query){
				$query->select('id','nombre','email','estado','dni','telefono');
			}])
			/*->whereHas('trabajador',function($query){
				$query->where('id_cliente',auth()->user()->id_cliente_actual);
			})*/
			->where('tareas_livianas.id_cliente',auth()->user()->id_cliente_actual)
			->where('tareas_livianas.id', $id)
			->first();

		$clientes = $this->getClientesUser();
		$tareas_livianas_tipos = TareaLivianaTipo::orderBy('nombre', 'asc')->get();
		$tipo_comunicaciones = TipoComunicacionLiviana::orderBy('nombre', 'asc')->get();

		return view('empleados.tareas_livianas.edit', compact('tarea_liviana', 'clientes', 'tareas_livianas_tipos', 'tipo_comunicaciones'));
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
		$validatedData = $request->validate([
			'fecha_inicio' => 'required'
		]);

		$fecha_actual = Carbon::now();
		$fecha_inicio = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio);

		if (isset($request->fecha_final) && !empty($request->fecha_final) && !is_null($request->fecha_final)) {
			$fecha_final = Carbon::createFromFormat('d/m/Y', $request->fecha_final);

			if ($fecha_inicio->greaterThan($fecha_final)) {
				return back()->withInput()->with('error', 'La fecha de inicio no puede ser superior a la fecha final o quizás lo dejó vacío');
			}
		}

		if (isset($request->fecha_regreso_trabajar) && !empty($request->fecha_regreso_trabajar) && !is_null($request->fecha_regreso_trabajar)) {
			$fecha_regreso_trabajar = Carbon::createFromFormat('d/m/Y', $request->fecha_regreso_trabajar);

			if (!isset($request->fecha_final) && empty($request->fecha_final) && !is_null($request->fecha_final)) {
					return back()->withInput()->with('error', 'No puedes ingresar una fecha de regreso al trabajo sin cargar una fecha final');
				}

			if ($fecha_final->greaterThan($fecha_regreso_trabajar)) {
				return back()->withInput()->with('error', 'La fecha final no puede ser mayor que la fecha de regreso al trabajo');
			}
		}

		//Actualizar en base
		$tarea_liviana = TareaLiviana::findOrFail($id);
		$tarea_liviana->fecha_inicio = $fecha_inicio;
		$tarea_liviana->id_tipo = $request->tipo;

		//Guardar en base Comunicacion
		$tarea_liviana->comunicacion->id_tipo = $request->tipo_comunicacion;
		$tarea_liviana->comunicacion->descripcion = $request->descripcion;
		$tarea_liviana->comunicacion->save();

		if (isset($request->fecha_final) && !empty($request->fecha_final)) {
			$tarea_liviana->fecha_final = $fecha_final;
		}else {
			$tarea_liviana->fecha_final = null;
		}
		if (isset($request->fecha_regreso_trabajar) && !empty($request->fecha_regreso_trabajar)) {
			$tarea_liviana->fecha_regreso_trabajar = $fecha_regreso_trabajar;
		}else {
			$tarea_liviana->fecha_regreso_trabajar = $fecha_final;
		}
		$tarea_liviana->user = auth()->user()->nombre;
		$tarea_liviana->save();

		return redirect('empleados/tareas_livianas')->with('success', 'Tarea adecuada actualizada con éxito');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$tarea_liviana = TareaLiviana::find($id);

		if ($tarea_liviana->archivo != null) {
		$ruta = storage_path("app/tareas_livianas/trabajador/{$tarea_liviana->id}");
		$ruta_archivo = storage_path("app/tareas_livianas/trabajador/{$tarea_liviana->id}/{$tarea_liviana->hash_archivo}");
		unlink($ruta_archivo);
		rmdir($ruta);
		}
		$comunicacion = ComunicacionLiviana::where('id_tarea_liviana', $id)->delete();
		$tarea_liviana->delete();
		return back()->with('success', 'Tarea adecuada y Comunicación asociada eliminados correctamente');

	}



	public function tipo(Request $request)
	{

		$validatedData = $request->validate([
			'nombre' => 'required|string'
		]);

		//Guardar en base
		$tipo_tarea_liviana = new TareaLivianaTipo();
		$tipo_tarea_liviana->nombre = $request->nombre;
		$tipo_tarea_liviana->save();

		return back()->with('success', 'Tipo de tarea adecuada creado con éxito');
	}


	public function tipo_destroy($id_tipo)
	{

		$tareas_livianas = TareaLiviana::where('id_tipo', $id_tipo)->get();

		if (!empty($tareas_livianas) && count($tareas_livianas) > 0) {
		return back()->with('error', 'Existen tareas adecuada creadas con este tipo de tarea liviana. No puedes eliminarla');
		}

		//Eliminar en base
		$tipo_tarea_liviana = TareaLivianaTipo::find($id_tipo)->delete();
		return back()->with('success', 'Tipo de tarea adecuada eliminado correctamente');
	}


	public function descargar_archivo($id)
	{

		$tarea_liviana = TareaLiviana::find($id);
		$ruta = storage_path("app/tareas_livianas/trabajador/{$tarea_liviana->id}/{$tarea_liviana->hash_archivo}");
		return download_file($ruta);

		//return response()->download($ruta);
		//return back();

	}


	public function exportar(Request $request)
	{
		return $this->exportTareasLivianas($request);
	}


	public function extensionComunicacion(Request $request)
	{
		$validatedData = $request->validate([
			'fecha_final' => 'required',
			'descripcion' => 'required'
		]);

		$tarea_liviana = TareaLiviana::findOrFail($request->id_tarea_liviana);
		$fecha_final = Carbon::createFromFormat('d/m/Y', $request->fecha_final);
		if ($fecha_final->lessThan($tarea_liviana->fecha_inicio)) {
			return back()->with('error', 'La fecha final es menor a la de inicio');
		}
		$tarea_liviana->fecha_final = $fecha_final;
		$tarea_liviana->fecha_regreso_trabajar = $fecha_final;
		$tarea_liviana->save();

		$comunicacion = new ComunicacionLiviana();
	  	$comunicacion->id_tarea_liviana = $request->id_tarea_liviana;
	  	$comunicacion->id_tipo = $request->id_tipo;
	  	$comunicacion->descripcion = $request->descripcion;
	  	$comunicacion->user = auth()->user()->nombre;
	  	$comunicacion->save();

	  	return redirect('empleados/comunicaciones_livianas/'.$request->id_tarea_liviana)->with('success', 'Comunicación adecuada guardada con éxito');

	}


}
