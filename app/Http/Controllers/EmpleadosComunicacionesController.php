<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comunicacion;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
//use App\ClienteUser;
use App\Ausentismo;
use App\AusentismoDocumentacion;
use App\TipoComunicacion;
use App\Nomina;
use App\Cliente;
use App\ComunicacionArchivo;
use App\Http\Traits\Clientes;

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
		// Se modifica la consulta para evitar duplicados y agrupar los archivos
		$query = Comunicacion::select(
			'comunicaciones.id_ausentismo',
			'comunicaciones.id',
			'comunicaciones.descripcion',
			'comunicaciones.fecha',
			'comunicaciones.created_at',
			DB::raw('IF(comunicaciones.user IS NOT NULL, comunicaciones.user, ausentismos.user) as user'),
			DB::raw('tipo_comunicacion.nombre as tipo'),
			'nominas.nombre',
			'nominas.email',
			'nominas.estado',
			'nominas.id_cliente as trabajador_cliente',
			'ausentismos.id_cliente'
		)
		->join('ausentismos', 'comunicaciones.id_ausentismo', '=', 'ausentismos.id')
		->join('nominas', 'ausentismos.id_trabajador', '=', 'nominas.id')
		->join('tipo_comunicacion', 'comunicaciones.id_tipo', '=', 'tipo_comunicacion.id')
		->leftJoin('comunicaciones_archivos', 'comunicaciones.id', '=', 'comunicaciones_archivos.id_comunicacion') // Unimos los archivos
		->where('ausentismos.id_cliente', auth()->user()->id_cliente_actual)
		->with(['archivos','ausentismo.trabajador']);
		///->groupBy('comunicaciones.id');

		$total = $query->count();




		// Filtros
		if ($request->from) {
			$query->whereDate('comunicaciones.fecha', '>=', Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		}
		if ($request->to) {
			$query->whereDate('comunicaciones.fecha', '<=', Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
		}
		if ($request->estado && $request->estado != 'todos') {
			$query->where('nominas.estado', '=', $request->estado == 'activos' ? '1' : '0');
		}

		if ($request->search) {
			$filtro = '%' . $request->search . '%';
			$query->where(function ($query) use ($filtro) {
				$query
					->where('nominas.nombre', 'LIKE', $filtro)
					->orWhere('nominas.dni', 'LIKE', $filtro)
					->orWhere('tipo_comunicacion.nombre', 'LIKE', $filtro)
					->orWhere('ausentismos.user', 'LIKE', $filtro)
					->orWhere('comunicaciones.user', 'LIKE', $filtro)
					->orWhere('comunicaciones.descripcion', 'LIKE', $filtro);
			});
		}



		if ($request->order) {
			$sort = $request->columns[$request->order[0]['column']]['name'];
			$dir  = $request->order[0]['dir'];
			$query->orderBy($sort, $dir);
		}


		$total_filtered = $query->count();

		return [
			'draw' => $request->draw,
			'recordsTotal' => $total,
			'recordsFiltered' => $total_filtered,
			'data' => $query->skip($request->start)->take($request->length)->get(),
			'request' => $request->all(),
			'fichada_user' => auth()->user()->fichada,
			'fichar_user' => auth()->user()->fichar
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
			'descripcion' => 'required',
			'id_tipo' => 'required',
			'id_ausentismo' => 'required',
			'fecha' => 'nullable|date_format:d/m/Y',
		]);

		$errorMessage = $this->validarArchivos($request);
		if ($errorMessage) {
			return back()->withErrors(['archivo' => $errorMessage])->withInput();
		}

		// Guardar en base Comunicaciones
		$comunicacion = new Comunicacion();
		$comunicacion->id_ausentismo = $request->id_ausentismo;
		$comunicacion->id_tipo = $request->id_tipo;
		$comunicacion->descripcion = $request->descripcion;
		$comunicacion->fecha = $request->fecha ? Carbon::createFromFormat('d/m/Y', $request->fecha) : Carbon::today();
		$comunicacion->user = auth()->user()->nombre;
		$comunicacion->save();

		$this->guardarArchivos($request, $comunicacion);

		return redirect('empleados/ausentismo/'.$request->id_ausentismo)->with('success', 'Comunicación guardada con éxito');
		//return redirect('empleados/comunicaciones/'.$request->id_ausentismo)->with('success', 'Comunicación guardada con éxito');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{

		$ausencia = Ausentismo::where('id',$id)
			->with(['trabajador','tipo','comunicaciones'=>function($query){
				$query
					->with(['archivos','tipo'])
					->orderBy('fecha','desc')
					->orderBy('created_at','desc');
			}])
			->first();

		/*$comunicaciones_ausentismo = Comunicacion::select(
				'comunicaciones.*',
				DB::raw('IF(comunicaciones.user IS NOT NULL,comunicaciones.user,ausentismos.user) as user')
			)
			->join('ausentismos','comunicaciones.id_ausentismo','ausentismos.id')
			->where('id_ausentismo', $id)
			->with(['tipo', 'archivos'])
			->orderBy('comunicaciones.created_at', 'desc')
			->get();*/
		$tipo_comunicaciones = TipoComunicacion::orderBy('nombre', 'asc')->get();
		//dd($tipo_comunicaciones);

		$clientes = $this->getClientesUser();

		// Solo se editan/eliminan comunicaciones de ausentismos del cliente actual y de trabajadores no transferidos
		$editar_comunicaciones = $ausencia->id_cliente == $ausencia->trabajador->id_cliente && $ausencia->id_cliente == auth()->user()->id_cliente_actual;

		return view('empleados.comunicaciones.show', compact(
			'ausencia',
			'clientes',
			'editar_comunicaciones',
			///'comunicaciones_ausentismo',
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
		$validatedData = $request->validate([
			'descripcion' => 'required',
			'id_tipo' => 'required',
			'fecha' => 'required|date_format:d/m/Y',
		]);

		$comunicacion = $this->getComunicacionCliente($id);
		if (!$comunicacion) {
			return back()->with('error', 'No se encontró la comunicación');
		}

		$errorMessage = $this->validarArchivos($request);
		if ($errorMessage) {
			return back()->withErrors(['archivo' => $errorMessage])->withInput();
		}

		$comunicacion->id_tipo = $request->id_tipo;
		$comunicacion->descripcion = $request->descripcion;
		$comunicacion->fecha = Carbon::createFromFormat('d/m/Y', $request->fecha);
		$comunicacion->save();

		// Quitar los archivos que se marcaron para eliminar
		if ($request->archivos_eliminar) {
			$archivos = $comunicacion->archivos()->whereIn('id', $request->archivos_eliminar)->get();
			foreach ($archivos as $archivo) {
				Storage::disk('local')->delete('comunicaciones/'.$comunicacion->id.'/'.$archivo->hash_archivo);
				$archivo->delete();
			}
		}

		$this->guardarArchivos($request, $comunicacion);

		return back()->with('success', 'Comunicación actualizada con éxito');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$comunicacion = $this->getComunicacionCliente($id);
		if (!$comunicacion) {
			return back()->with('error', 'No se encontró la comunicación');
		}

		// El ausentismo siempre debe conservar al menos una comunicación
		if (Comunicacion::where('id_ausentismo', $comunicacion->id_ausentismo)->count() <= 1) {
			return back()->with('error', 'No se puede eliminar la única comunicación del ausentismo');
		}

		$comunicacion->archivos()->delete();
		Storage::disk('local')->deleteDirectory('comunicaciones/'.$comunicacion->id);
		$comunicacion->delete();

		return back()->with('success', 'Comunicación eliminada correctamente');
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


	public function exportar(Request $request){

		if(!auth()->user()->id_cliente_actual) dd('debes seleccionar un cliente');
		$cliente = Cliente::where('id',auth()->user()->id_cliente_actual)->first();

		$request->start = 0;
		$request->length = 5000;

		$comunicaciones = $this->busqueda($request);
		//dd($comunicaciones['data']->toArray());

		$now = Carbon::now();
		$file_name = 'comunicados-'.Str::slug($cliente->nombre).'-'.$now->format('YmdHis').'.csv';


		$fp = fopen('php://memory', 'w');
		fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
		fputcsv($fp,[
			'Trabajador',
			'Tipo de Comunicación',
			'Usuario que Registró',
			'Fecha',
			'Fecha de Carga',
			'Estado',
			'Descripción'
		],';');

		foreach($comunicaciones['data'] as $row){

			$values =

			fputcsv($fp,[
				$row->nombre,
				$row->tipo,
				$row->user,
				$row->fecha ? $row->fecha->format('d/m/Y') : '',
				$row->created_at,
				$row->estado ? 'activo' : 'inactivo',
				$row->descripcion
			],';');
		}

		fseek($fp, 0);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="'.$file_name.'";');
		fpassthru($fp);

		return;


	}



	// Comunicación solo si su ausentismo pertenece al cliente actual del usuario
	private function getComunicacionCliente($id)
	{
		return Comunicacion::where('id', $id)
			->whereHas('ausentismo', function ($query) {
				$query->where('id_cliente', auth()->user()->id_cliente_actual);
			})
			->first();
	}


	private function validarArchivos(Request $request)
	{
		if (!$request->hasFile('archivos')) return null;

		foreach ($request->file('archivos') as $archivo) {
			$errorMessage = $this->validarArchivo($archivo);
			if ($errorMessage) return $errorMessage;
		}

		return null;
	}


	private function guardarArchivos(Request $request, Comunicacion $comunicacion)
	{
		if (!$request->hasFile('archivos')) return;

		foreach ($request->file('archivos') as $archivo) {
			// Guardar el archivo con el nombre hasheado en el disco (carpeta comunicaciones/{id})
			$hashedFilename = $archivo->hashName();
			$archivo->storeAs('comunicaciones/'.$comunicacion->id, $hashedFilename, 'local');

			// Crear un nuevo registro en la tabla comunicaciones_archivos
			$comunicacionArchivo = new ComunicacionArchivo();
			$comunicacionArchivo->id_comunicacion = $comunicacion->id;
			$comunicacionArchivo->archivo = $archivo->getClientOriginalName(); // Nombre original para referencia
			$comunicacionArchivo->hash_archivo = $hashedFilename; // Nombre del archivo hasheado
			$comunicacionArchivo->save();
		}
	}


	private function validarArchivo($archivo, $maxSize = 2048, $formatosPermitidos = ['jpeg', 'png', 'jpg', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx'])
	{
		// Verificar el tamaño del archivo (el tamaño está en KB, 2048KB = 2MB)
		if ($archivo->getSize() / 1024 > $maxSize) {
			return "El archivo {$archivo->getClientOriginalName()} excede el tamaño máximo permitido de {$maxSize}KB.";
		}

		// Verificar el formato del archivo
		if (!in_array($archivo->getClientOriginalExtension(), $formatosPermitidos)) {
			return "El archivo {$archivo->getClientOriginalName()} no tiene un formato permitido. Formatos permitidos: " . implode(', ', $formatosPermitidos);
		}

		// Si pasa todas las validaciones, retorna null (lo que significa que está ok)
		return null;
	}



	public function verArchivo($id, $hash)
	{
		$rutaArchivo = storage_path('app/comunicaciones/'.$id . '/' . $hash);

		return download_file($rutaArchivo);

		if (file_exists($rutaArchivo)) {
			return response()->download($rutaArchivo);
		} else {
			return redirect()->back()->withErrors(['El archivo no existe.']);
		}
	}


}
