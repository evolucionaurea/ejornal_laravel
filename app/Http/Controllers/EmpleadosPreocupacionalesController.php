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
		/*$query = Preocupacional::select(
			'nominas.nombre', 'nominas.email', 'nominas.telefono',
			'preocupacionales.fecha', 'preocupacionales.archivo', 'preocupacionales.id'
		)
		->join('nominas', 'preocupacionales.id_nomina', 'nominas.id')
		->where('nominas.id_cliente', auth()->user()->id_cliente_actual);*/

		$query = Preocupacional::with('trabajador')
			->select('preocupacionales.*')
			->join('nominas', 'preocupacionales.id_nomina', 'nominas.id')
			->whereHas('trabajador',function($query){
			$query->select('id')->where('id_cliente',auth()->user()->id_cliente_actual);
		});

		$total = $query->count();

		if($request->from) $query->whereDate('preocupacionales.fecha','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		if($request->to) $query->whereDate('preocupacionales.fecha','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));

		if(isset($request->search)){
			$query->where(function($query) use($request) {
				$filtro = '%'.$request->search['value'].'%';
				$query->where('nominas.nombre','like',$filtro)
					->orWhere('nominas.email','like',$filtro)
					->orWhere('nominas.dni','like',$filtro)
					->orWhere('nominas.telefono','like',$filtro);
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

			'data'=>$query->get(),
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
		$trabajadores = Nomina::where('id_cliente', auth()->user()->id_cliente_actual)->orderBy('nombre', 'asc')->get();
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
		if(!$request->hasFile('archivo')) {
			return back()->withInput()->with('error', 'Debes adjuntar un archivo');
		}


		//Guardar en base Preocupacional
		$preocupacional = new Preocupacional();
		$preocupacional->id_nomina = $request->trabajador;
		$preocupacional->fecha = $fecha;
		$preocupacional->observaciones = $request->observaciones;

		$archivo = $request->file('archivo');
		$nombre = $archivo->getClientOriginalName();
		$preocupacional->archivo = $nombre;

		if($request->tiene_vencimiento){
			$preocupacional->fecha_vencimiento = Carbon::createFromFormat('d/m/Y', $request->fecha_vencimiento);
			$preocupacional->completado = $request->completado;
		}
		///$preocupacional->save();
		// Completar en base el hash del archivo guardado
		//$preocupacional = Preocupacional::findOrFail($preocupacional->id);
		$preocupacional->hash_archivo = $archivo->hashName();
		$preocupacional->save();

		Storage::disk('local')->put('preocupacionales/trabajador/'.$preocupacional->id, $archivo);

		return redirect('empleados/preocupacionales')->with('success', 'Preocupacional guardado con éxito');

	}

	public function edit($id)
	{

		$preocupacional = Preocupacional::with('trabajador')->where('id',$id)->first();
		$clientes = $this->getClientesUser();

		return view('empleados.preocupacionales.edit', compact('preocupacional', 'clientes'));
	}

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
		if($request->tiene_vencimiento){
			$preocupacional->fecha_vencimiento = Carbon::createFromFormat('d/m/Y', $request->fecha_vencimiento);
			$preocupacional->completado = $request->completado;
		}
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
		return download_file($ruta);
		/*return response()->download($ruta);
		return back();*/

	}



}
