<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Preocupacional;
///use App\Cliente;
///use App\ClienteUser;
use App\Http\Traits\Clientes;
use App\Nomina;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class EmpleadosPreocupacionalesController extends Controller
{

	use Clientes;

	public function index()
	{
		$clientes = $this->getClientesUser();
		return view('empleados.preocupacionales', compact('clientes'));

	}
	public function busqueda(Request $request)
	{
		$query = Preocupacional::select(
			'nominas.nombre', 'nominas.email', 'nominas.telefono',
			'preocupacionales.fecha', 'preocupacionales.archivo', 'preocupacionales.id'
		)
		->join('nominas', 'preocupacionales.id_nomina', 'nominas.id')
		->where('nominas.id_cliente', auth()->user()->id_cliente_actual);

		if($request->from) $query->whereDate('preocupacionales.fecha','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		if($request->to) $query->whereDate('preocupacionales.fecha','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));


		return [
			'results'=>$query->get(),
			'fichada_user'=>auth()->user()->fichada,
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
		$trabajadores = Nomina::where('id_cliente', auth()->user()->id_cliente_actual)->get();
		$clientes = $this->getClientesUser();
		return view('empleados.preocupacionales.create', compact('clientes', 'trabajadores'));

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
			'fecha' => 'required',
			'observaciones' => 'required|string'
		]);

		$fecha = Carbon::createFromFormat('d/m/Y', $request->fecha);

		// Si hay un archivo adjunto se va a guardar todo
		if ($request->hasFile('archivo') && $request->file('archivo') > 0) {

			//Guardar en base Preocupacional
			$preocupacional = new Preocupacional();
			$preocupacional->id_nomina = $request->trabajador;
			$preocupacional->fecha = $fecha;
			$preocupacional->observaciones = $request->observaciones;

			$archivo = $request->file('archivo');
			$nombre = $archivo->getClientOriginalName();
			$preocupacional->archivo = $nombre;

			$preocupacional->save();

			Storage::disk('local')->put('preocupacionales/trabajador/'.$preocupacional->id, $archivo);


			// Completar en base el hash del archivo guardado
			$preocupacional = Preocupacional::findOrFail($preocupacional->id);
			$preocupacional->hash_archivo = $archivo->hashName();
			$preocupacional->save();


		}else {
			return back()->withInput()->with('error', 'Debes adjuntar un archivo');
		}

		return redirect('empleados/preocupacionales')->with('success', 'Preocupacional guardado con éxito');

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{

		$preocupacional = Preocupacional::join('nominas', 'preocupacionales.id_nomina', 'nominas.id')
		->where('nominas.id_cliente', auth()->user()->id_cliente_actual)
		->where('preocupacionales.id', $id)
		->select('preocupacionales.id', 'preocupacionales.fecha', 'preocupacionales.observaciones', 'preocupacionales.archivo', 'preocupacionales.hash_archivo', 'nominas.nombre')
		->first();

		$clientes = $this->getClientesUser();

		return view('empleados.preocupacionales.edit', compact('preocupacional', 'clientes'));

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
		'fecha' => 'required',
		'observaciones' => 'required'
		]);

		$fecha = Carbon::createFromFormat('d/m/Y', $request->fecha);

		//Actualizar en base
		$preocupacional = Preocupacional::findOrFail($id);
		$preocupacional->fecha = $fecha;
		$preocupacional->observaciones = $request->observaciones;
		$preocupacional->save();

		return redirect('empleados/preocupacionales')->with('success', 'Preocupacional actualizado con éxito');

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{

		$preocupacional = Preocupacional::find($id);
		$ruta = storage_path("app/preocupacionales/trabajador/{$preocupacional->id}");
		$ruta_archivo = storage_path("app/preocupacionales/trabajador/{$preocupacional->id}/{$preocupacional->hash_archivo}");
		unlink($ruta_archivo);
		rmdir($ruta);
		$preocupacional->delete();
		return back()->with('success', 'Preocupacional eliminado correctamente');

	}



	public function descargar_archivo($id)
	{

		$preocupacional = Preocupacional::find($id);
		$ruta = storage_path("app/preocupacionales/trabajador/{$preocupacional->id}/{$preocupacional->hash_archivo}");
		return response()->download($ruta);
		return back();

	}



}
