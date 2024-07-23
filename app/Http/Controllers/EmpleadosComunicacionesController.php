<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comunicacion;
use App\Http\Traits\Clientes;
//use App\ClienteUser;
use App\Ausentismo;
use App\AusentismoDocumentacion;
use App\TipoComunicacion;
use Carbon\Carbon;
use App\Nomina;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class EmpleadosComunicacionesController extends Controller
{
	use Clientes;

	public function index()
	{
		$clientes = $this->getClientesUser();
		return view('empleados.comunicaciones', compact('clientes'));
	}
	public function busqueda(Request $request)
	{

		$query = Comunicacion::select(
			'nominas.id',
			'nominas.nombre',
			'nominas.email',
			DB::raw('tipo_comunicacion.nombre tipo'),
			'comunicaciones.created_at',
			'comunicaciones.descripcion',

			DB::raw('IF(comunicaciones.user IS NOT NULL,comunicaciones.user,ausentismos.user) as user'),

			'nominas.estado'
		)
		->join('ausentismos', 'comunicaciones.id_ausentismo', 'ausentismos.id')
		->join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
		->join('tipo_comunicacion', 'comunicaciones.id_tipo', 'tipo_comunicacion.id')
		->where('nominas.id_cliente', auth()->user()->id_cliente_actual);


		$total = $query->count();

		if($request->from) $query->whereDate('comunicaciones.created_at','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		if($request->to) $query->whereDate('comunicaciones.created_at','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));


		if($request->order){
			$sort = $request->columns[$request->order[0]['column']]['name'];
			$dir  = $request->order[0]['dir'];
			$query->orderBy($sort,$dir);
		}
		if($request->search){
			$filtro = '%'.$request->search['value'].'%';

			$query->where(function($query) use($filtro){
				$query
					->where('nominas.nombre','LIKE',$filtro)
					->orWhere('tipo_comunicacion.nombre','LIKE',$filtro)
					->orWhere('ausentismos.user','LIKE',$filtro)
					->orWhere('comunicaciones.user','LIKE',$filtro)
					->orWhere('comunicaciones.descripcion','LIKE',$filtro);
			});

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
		$comunicacion = new Comunicacion();
		$comunicacion->id_ausentismo = $request->id_ausentismo;
		$comunicacion->id_tipo = $request->id_tipo;
		$comunicacion->descripcion = $request->descripcion;
		$comunicacion->user = auth()->user()->nombre;
		$comunicacion->save();

		return redirect('empleados/comunicaciones/'.$request->id_ausentismo)->with('success', 'Comunicación guardada con éxito');

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{

		$ausencia = Ausentismo::where('id',$id)->with('trabajador')->with('tipo')->first();

		$comunicaciones_ausentismo = Comunicacion::select(
				'comunicaciones.*',
				DB::raw('IF(comunicaciones.user IS NOT NULL,comunicaciones.user,ausentismos.user) as user')
			)
			->join('ausentismos','comunicaciones.id_ausentismo','ausentismos.id')
			->where('id_ausentismo', $id)
			->with('tipo')
			->orderBy('comunicaciones.created_at', 'desc')
			->get();

		$tipo_comunicaciones = TipoComunicacion::orderBy('nombre', 'asc')->get();
		//dd($tipo_comunicaciones);

		$clientes = $this->getClientesUser();

		return view('empleados.comunicaciones.show', compact(
			'ausencia',
			'clientes',
			'comunicaciones_ausentismo',
			'tipo_comunicaciones'
		));

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
		$tipo_comunicacion = new TipoComunicacion();
		$tipo_comunicacion->nombre = $request->nombre;
		$tipo_comunicacion->save();

		return back()->with('success', 'Tipo de comunicación creado con éxito');
	}


	public function tipo_destroy($id_tipo)
	{

		$comunicacion = Comunicacion::where('id_tipo', $id_tipo)->get();

		if (!empty($comunicacion) && count($comunicacion) > 0) {
		return back()->with('error', 'Existen comunicaciones creadas con este tipo de comunicacion. No puedes eliminarla');
		}

		//Eliminar en base
		$tipo_comunicacion = TipoComunicacion::find($id_tipo)->delete();
		return back()->with('success', 'Tipo de comunicacion eliminada correctamente');
	}



	public function getComunicacion($id)
	{
		$comunicacion_de_ausentismo = Comunicacion::find($id);
		return response()->json($comunicacion_de_ausentismo);
	}

}
