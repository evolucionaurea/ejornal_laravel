<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Preocupacional;
///use App\Cliente;
///use App\ClienteUser;
use App\Http\Traits\Clientes;
use App\Nomina;
use App\PreocupacionalTipoEstudio;
use App\PreocupacionalArchivo;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Storage;

class EmpleadosPreocupacionalesController extends Controller
{

	use Clientes;

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
		/*$query = Preocupacional::select(
			'nominas.nombre', 'nominas.email', 'nominas.telefono',
			'preocupacionales.fecha', 'preocupacionales.archivo', 'preocupacionales.id'
		)
		->join('nominas', 'preocupacionales.id_nomina', 'nominas.id')
		->where('nominas.id_cliente', auth()->user()->id_cliente_actual);*/

		$now = CarbonImmutable::now();

		$query = Preocupacional::with(['trabajador','tipo'])
			->select('preocupacionales.*')
			->join('nominas', 'preocupacionales.id_nomina', 'nominas.id')
			->join('preocupacionales_tipos_estudio', 'preocupacionales_tipos_estudio.id', 'preocupacionales.tipo_estudio_id')
			->with('archivos')
			->where('preocupacionales.id_cliente',auth()->user()->id_cliente_actual);
			/*->whereHas('trabajador',function($query){
				$query->select('id')->where('id_cliente',auth()->user()->id_cliente_actual);
			});*/

		$total = $query->count();

		// FILTROS
		if($request->from){
			$query->whereDate('preocupacionales.fecha','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		}
		if($request->to){
			$query->whereDate('preocupacionales.fecha','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
		}
		/*if($request->vencimiento_from){
			$query
				->whereDate('fecha_vencimiento','<=',$now->addMonth())
				->where('completado',0);
		}*/
		if($request->tipo){
			$query->where('tipo_estudio_id','=',$request->tipo);
		}
		if(!is_null($request->vencimiento)){
			if($request->vencimiento==='1'){
				$query->whereNotNull('fecha_vencimiento');
			}
			if($request->vencimiento==='0'){
				$query->whereNull('fecha_vencimiento');
			}
		}
		if(!is_null($request->vencimiento_estado)){
			if($request->vencimiento_estado==='1'){
				$query->whereDate('fecha_vencimiento','<',$now);
			}
			if($request->vencimiento_estado==='0'){
				$query->whereDate('fecha_vencimiento','>=',$now);
			}
		}
		if(!is_null($request->completado)){
			$query->where('completado','=',$request->completado);
		}



		// BUSQUEDA
		if(isset($request->search)){
			$query->where(function($query) use($request) {
				$filtro = '%'.$request->search['value'].'%';
				$query->where('nominas.nombre','like',$filtro)
					->orWhere('nominas.email','like',$filtro)
					->orWhere('nominas.dni','like',$filtro)
					->orWhere('nominas.telefono','like',$filtro)
					->orWhere('preocupacionales_tipos_estudio.name','like',$filtro);
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
		$clientes = $this->getClientesUser();

		$tipos = PreocupacionalTipoEstudio::all();

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

		///$archivo = $request->file('archivo');
		///$nombre = $archivo->getClientOriginalName();
		///$preocupacional->archivo = $nombre;

		if($request->tiene_vencimiento){
			$preocupacional->fecha_vencimiento = Carbon::createFromFormat('d/m/Y', $request->fecha_vencimiento);
			$preocupacional->completado = $request->completado;
			$preocupacional->completado_comentarios  = $request->completado_comentarios;
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

		$preocupacional = Preocupacional::with('trabajador')->with('archivos')->where('id',$id)->first();
		$clientes = $this->getClientesUser();

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

		$archivo = PreocupacionalArchivo::find($id);
		$ruta = storage_path("app/preocupacionales/trabajador/{$archivo->preocupacional_id}/{$archivo->hash_archivo}");
		return download_file($ruta);

	}


	public function completar(Request $request){

		if(!$preocupacional = Preocupacional::find($request->id)){
			return false;
		}

		$preocupacional->completado = 1;
		$preocupacional->completado_comentarios = $request->comentarios;
		$preocupacional->save();

		return [
			'message'=>'El estudio se ha marcado como <b>Completado </b>correctamente.',
			'status'=>'ok'
		];
	}



}
