<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ausentismo;
//use App\Cliente;
//use App\ClienteUser;
use App\Http\Traits\Clientes;
use App\Http\Traits\Ausentismos;
use App\Nomina;
use App\AusentismoTipo;
use App\TipoComunicacion;
use App\Comunicacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use DateTime;

class EmpleadosAusentismosController extends Controller
{

	use Clientes,Ausentismos;

	public function index()
	{

		//// Traits > Clientes
		$clientes = $this->getClientesUser();

		$tipos = AusentismoTipo::get();

		return view('empleados.ausentismos', compact('clientes','tipos'));
	}
	public function busqueda(Request $request)
	{

		///$this->request = $request;
		//Traits > Ausentismos
		$resultados = $this->searchAusentismos(auth()->user()->id_cliente_actual,$request);

		return array_merge($resultados,['fichada_user'=>auth()->user()->fichada]);



	  /*$query = Ausentismo::select(
	  	'ausentismos.*',
	  	'nominas.nombre',
	  	'nominas.email',
	  	'nominas.telefono',
	  	'nominas.dni',
	  	'nominas.estado',
	  	'nominas.sector',
	  	'ausentismo_tipo.nombre as nombre_ausentismo'
	  )
	  ->join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
	  ->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
	  ->where('nominas.id_cliente', auth()->user()->id_cliente_actual);

	  $query->where(function($query) use ($request) {
			$filtro = '%'.$request->search['value'].'%';
			$query->where('nominas.nombre','like',$filtro)
				->orWhere('nominas.email','like',$filtro)
				->orWhere('nominas.dni','like',$filtro)
				->orWhere('nominas.telefono','like',$filtro);
		});

		if($request->from) $query->whereDate('ausentismos.fecha_inicio','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		if($request->to) $query->whereDate('ausentismos.fecha_final','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
		if($request->tipo) $query->where('id_tipo',$request->tipo);

		if($request->ausentes=='hoy'){
			$query->where(function($query){
				$now = Carbon::now();
				$query
					->where('ausentismos.fecha_regreso_trabajar',null)
					->orWhere('ausentismos.fecha_regreso_trabajar','>',$now);
			});
		}

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
			'request'=>$request->all()
		];*/

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$trabajadores = Nomina::where('id_cliente', auth()->user()->id_cliente_actual)->orderBy('nombre', 'asc')->get();
		$ausentismo_tipos = AusentismoTipo::orderBy('nombre', 'asc')->get();
		$clientes = $this->getClientesUser();
		$tipo_comunicacion = TipoComunicacion::orderBy('nombre', 'asc')->get();

		return view('empleados.ausentismos.create', compact('trabajadores', 'ausentismo_tipos', 'clientes', 'tipo_comunicacion'));
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


		$dos_dias_adelante = Carbon::now()->addDays(2);
		$un_anio_atras = Carbon::now()->subYear(1);
		if ($fecha_inicio > $dos_dias_adelante) {
		  return back()->withInput()->with('error', 'La fecha de inicio no puede ser superior a dos dias adelante de la fecha actual');
		}
		if ($fecha_inicio->lessThan($un_anio_atras)) {
		  return back()->withInput()->with('error', 'La fecha de inicio puede ser hasta un año atrás, no mas');
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

	  $buscar_ausentismos_trabajador = Ausentismo::where('id_trabajador', $request->trabajador)
	  ->where('fecha_regreso_trabajar', null)
	  ->count();

	  if ($buscar_ausentismos_trabajador > 0) {
		return back()->withInput()->with('error', 'Este trabajador tiene ausentismos sin fecha de regreso cargada. No podras
		cargar nuevos ausentismos hasta en tanto dicha fecha sea cargada');
	  }


		//Guardar en base Ausentismo
		$ausentismo = new Ausentismo();
		$ausentismo->id_trabajador = $request->trabajador;
		$ausentismo->id_tipo = $request->tipo;
		$ausentismo->fecha_inicio = $fecha_inicio;
		if (isset($request->fecha_final) && !empty($request->fecha_final)) {
		  $ausentismo->fecha_final = $fecha_final;
		}else {
		  $ausentismo->fecha_final = null;
		}
		if (isset($request->fecha_regreso_trabajar) && !empty($request->fecha_regreso_trabajar)) {
		  $ausentismo->fecha_regreso_trabajar = $fecha_regreso_trabajar;
		}else {
		  $ausentismo->fecha_regreso_trabajar = null;
		}

		// Si hay un archivo adjunto
		if ($request->hasFile('archivo') && $request->file('archivo') > 0) {

		  $archivo = $request->file('archivo');
		  $nombre = $archivo->getClientOriginalName();
		  $ausentismo->archivo = $nombre;

		}
		$ausentismo->user = auth()->user()->nombre;
		$ausentismo->save();

		// Si hay un archivo adjunto
		if ($request->hasFile('archivo') && $request->file('archivo') > 0) {

		Storage::disk('local')->put('ausentismos/trabajador/'.$ausentismo->id, $archivo);

		// Completar el base el hasg del archivo guardado
		$ausentismo = Ausentismo::findOrFail($ausentismo->id);
		$ausentismo->hash_archivo = $archivo->hashName();
		$ausentismo->save();

		}


		//Guardar en base Comunicacion
		$comunicacion = new Comunicacion();
		$comunicacion->id_ausentismo = $ausentismo->id;
		$comunicacion->id_tipo = $request->tipo_comunicacion;
		$comunicacion->descripcion = $request->descripcion;
		$comunicacion->save();


	  return redirect('empleados/ausentismos')->with('success', 'Ausentismo y Comunicación guardados con éxito');

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		// Aqui veremos el historial de ausencias
		$ausencias = Ausentismo::join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
		->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
		->where('ausentismos.id_trabajador', $id)
		->where('nominas.id_cliente', auth()->user()->id_cliente_actual)
		->select('nominas.nombre', 'nominas.email', 'nominas.estado', 'nominas.telefono',
		DB::raw('ausentismo_tipo.nombre nombre_ausentismo'), 'ausentismos.fecha_inicio',
		'ausentismos.fecha_final', 'ausentismos.fecha_regreso_trabajar', 'ausentismos.archivo',
		'ausentismos.id', DB::raw('ausentismos.user user'))
		->orderBy('ausentismos.fecha_inicio', 'desc')
		->get();

		$clientes = $this->getClientesUser();

		return view('empleados.ausentismos.show', compact('ausencias', 'clientes'));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{

	  $ausentismo = Ausentismo::join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
	  ->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
	  ->where('nominas.id_cliente', auth()->user()->id_cliente_actual)
	  ->where('ausentismos.id', $id)
	  ->select('ausentismos.*', 'nominas.nombre', 'nominas.email', 'nominas.telefono', 'nominas.dni', 'nominas.estado', DB::raw('ausentismo_tipo.nombre nombre_ausentismo'))
	  ->first();

	  $clientes = $this->getClientesUser();

	  return view('empleados.ausentismos.edit', compact('ausentismo', 'clientes'));
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
	  $ausentismo = Ausentismo::findOrFail($id);
	  $ausentismo->fecha_inicio = $fecha_inicio;
	  if (isset($request->fecha_final) && !empty($request->fecha_final)) {
			$ausentismo->fecha_final = $fecha_final;
	  }else {
			$ausentismo->fecha_final = null;
	  }
	  if (isset($request->fecha_regreso_trabajar) && !empty($request->fecha_regreso_trabajar)) {
			$ausentismo->fecha_regreso_trabajar = $fecha_regreso_trabajar;
	  }else {
			$ausentismo->fecha_regreso_trabajar = null;
	  }
	  $ausentismo->user = auth()->user()->nombre;
	  $ausentismo->save();

	  return redirect('empleados/ausentismos?'.$_SERVER['QUERY_STRING'])->with('success', 'Ausentismo actualizado con éxito');


	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
	  $ausentismo = Ausentismo::find($id);

	  if ($ausentismo->archivo != null) {
		$ruta = storage_path("app/ausentismos/trabajador/{$ausentismo->id}");
		$ruta_archivo = storage_path("app/ausentismos/trabajador/{$ausentismo->id}/{$ausentismo->hash_archivo}");
		unlink($ruta_archivo);
		rmdir($ruta);
	  }
	  $comunicacion = Comunicacion::where('id_ausentismo', $id)->delete();
	  $ausentismo->delete();
	  return back()->with('success', 'Ausentismo y Comunicación asociada eliminados correctamente');
	}


	public function tipo(Request $request)
	{

		$validatedData = $request->validate([
		  'nombre' => 'required|string'
		]);

		//Guardar en base
		$tipo_ausentismo = new AusentismoTipo();
		$tipo_ausentismo->nombre = $request->nombre;
		$tipo_ausentismo->save();

		return back()->with('success', 'Tipo de ausentismo creado con éxito');
	}


	public function tipo_destroy($id_tipo)
	{

	  $ausentismos = Ausentismo::where('id_tipo', $id_tipo)->get();

	  if (!empty($ausentismos) && count($ausentismos) > 0) {
		return back()->with('error', 'Existen ausencias creadas con este tipo de ausencia. No puedes eliminarla');
	  }

		//Eliminar en base
		$tipo_ausentismo = AusentismoTipo::find($id_tipo)->delete();
		return back()->with('success', 'Tipo de ausentismo eliminado correctamente');
	}


	public function descargar_archivo($id)
	{

	  $ausentismo = Ausentismo::find($id);
	  $ruta = storage_path("app/ausentismos/trabajador/{$ausentismo->id}/{$ausentismo->hash_archivo}");
	  return response()->download($ruta);
	  return back();

	}


	public function exportar(Request $request)
	{
		//Traits > Ausentismos
		return $this->exportAusentismos(auth()->user()->id_cliente_actual,$request);
	}





}
