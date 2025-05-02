<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Preocupacional;
///use App\Cliente;
///use App\ClienteUser;
use App\Http\Traits\Clientes;
use App\Http\Traits\Preocupacionales;
use App\Nomina;
use App\PreocupacionalTipoEstudio;
use App\PreocupacionalArchivo;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class EmpleadosPreocupacionalesController extends Controller
{

	use Clientes,Preocupacionales;

	public function index()
	{
		$clientes = $this->getClientesUser();
		$tipos = PreocupacionalTipoEstudio::all();
		return view('empleados.preocupacionales', compact(
			'clientes',
			'tipos'
		));

	}
	public function busqueda(Request $request)
	{

		$request->cliente_id = auth()->user()->id_cliente_actual;

		return $this->preocupacionalesAjax($request);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request)
	{
		$trabajadores = Nomina::where('id_cliente', auth()->user()->id_cliente_actual)
			->where('estado', '=', 1)
			->orderBy('nombre', 'asc')
			->get();
		$clientes = $this->getClientesUser();
		$tipos = PreocupacionalTipoEstudio::all();

		if($request->renovar){
			$preocupacional = Preocupacional::findOrFail($request->id);
			//dd($preocupacional->observaciones);
			/*$preocupacional->id_nomina = $request->trabajador;
			$preocupacional->id_cliente = auth()->user()->id_cliente_actual;
			$preocupacional->fecha = $fecha;
			*/

			session()->flash('trabajador',$preocupacional->id_nomina);
			session()->flash('fecha',$preocupacional->fecha->format('d/m/Y'));
			session()->flash('tipo_estudio_id',$preocupacional->tipo_estudio_id);
			session()->flash('resultado',$preocupacional->resultado);
			session()->flash('observaciones',$preocupacional->observaciones);

			session()->flash('tiene_vencimiento',!is_null($preocupacional->fecha_vencimiento) ? '1' : '');
			session()->flash('fecha_vencimiento',$preocupacional->fecha_vencimiento->format('d/m/Y'));
		}


		return view('empleados.preocupacionales.create', compact(
			'clientes',
			'trabajadores',
			'tipos'
		));

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


		if($request->id){
			$preocupacional = Preocupacional::findOrFail($request->id);
		}else{
			$preocupacional = new Preocupacional();
		}


		//Guardar en base Preocupacional
		$preocupacional->id_nomina = $request->trabajador;
		$preocupacional->id_cliente = auth()->user()->id_cliente_actual;
		$preocupacional->fecha = $fecha;
		$preocupacional->observaciones = $request->observaciones;
		$preocupacional->tipo_estudio_id  = $request->tipo_estudio_id;
		$preocupacional->resultado  = $request->resultado;
		$preocupacional->user = auth()->user()->nombre;

		///$archivo = $request->file('archivo');
		///$nombre = $archivo->getClientOriginalName();
		///$preocupacional->archivo = $nombre;

		if($request->tiene_vencimiento){
			$validatedData = $request->validate([
				'fecha_vencimiento' => 'required'
			]);

			$preocupacional->fecha_vencimiento = Carbon::createFromFormat('d/m/Y', $request->fecha_vencimiento);
			//$preocupacional->completado = 0;
			///$preocupacional->completado_comentarios  = $request->completado_comentarios;
		}
		///$preocupacional->save();
		// Completar en base el hash del archivo guardado
		//$preocupacional = Preocupacional::findOrFail($preocupacional->id);
		//$preocupacional->hash_archivo = $archivo->hashName();
		$preocupacional->save();


		///ARCHIVOS
		if($request->archivos){
			foreach($request->archivos as $file){
				$archivo = new PreocupacionalArchivo;
				$archivo->preocupacional_id = $preocupacional->id;
				$archivo->archivo = $file->getClientOriginalName();
				$archivo->hash_archivo = $file->hashName();
				$archivo->save();
				Storage::disk('local')->put('preocupacionales/trabajador/'.$preocupacional->id, $file);
			}
		}


		//Storage::disk('local')->put('preocupacionales/trabajador/'.$preocupacional->id, $archivo);

		return redirect('empleados/preocupacionales')->with('success', 'Preocupacional guardado con éxito');

	}

	public function edit($id)
	{

		$preocupacional = Preocupacional::with(['trabajador','archivos'])->where('id',$id)->first();
		$clientes = $this->getClientesUser();

		//dd($preocupacional);

		$tipos = PreocupacionalTipoEstudio::all();
		//dd($preocupacional->toArray());

		return view('empleados.preocupacionales.edit', compact(
			'preocupacional',
			'clientes',
			'tipos'
		));
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
			//$preocupacional->completado = $request->completado;
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

		if(file_exists($ruta_archivo)) unlink($ruta_archivo);
		if(is_dir($ruta)) rmdir($ruta);

		$preocupacional->delete();
		return back()->with('success', 'Preocupacional eliminado correctamente');

	}

	public function show($id)
	{

		$preocupacional = Preocupacional::with(['trabajador','tipo','archivos'])->where('id',$id)->first();
		$clientes = $this->getClientesUser();

		return view('empleados.preocupacionales.show',compact(
			'clientes',
			'preocupacional'
		));
	}



	public function descargar_archivo($id)
	{

		$archivo = PreocupacionalArchivo::find($id);
		$ruta = storage_path("app/preocupacionales/trabajador/{$archivo->preocupacional_id}/{$archivo->hash_archivo}");
		return download_file($ruta);

	}


	public function completar(Request $request){

		if(!$preocupacional = Preocupacional::find($request->id)){
			return false;
		}

		//dd($request->json());

		$preocupacional->completado = 1;
		$preocupacional->completado_comentarios = $request->comentarios;
		$preocupacional->save();

		return [
			'message'=>'El estudio se ha marcado como <b>Completado</b> correctamente.',
			'status'=>'ok'
		];
	}

	public function find($id){
		return Preocupacional::find($id);
	}



}
