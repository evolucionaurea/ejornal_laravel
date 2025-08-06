<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ausentismo;
//use App\Cliente;
//use App\ClienteUser;
use App\Http\Traits\Clientes;
use App\Http\Traits\Ausentismos;
use App\Http\Traits\Nominas;
use App\Nomina;
use App\AusentismoTipo;
use App\TipoComunicacion;
use App\Comunicacion;
use App\ComunicacionArchivo;
use App\AusentismoDocumentacion;
use App\AusentismoDocumentacionArchivos;
use App\ConsultaMedica;
use App\ConsultaEnfermeria;
use App\CovidVacuna;
use App\CovidTesteo;
use App\Preocupacional;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use DateTime;


/*use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;*/

class EmpleadosAusentismosController extends Controller
{

	use Clientes,Ausentismos,Nominas;

	public function index()
	{

		/*Artisan::call('db:seed', ['--class' => 'AusentismoDocumentacionArchivosSeeder']);
		$output = Artisan::output();
		dd($output);*/

		//// Traits > Clientes
		$clientes = $this->getClientesUser();

		$tipos = AusentismoTipo::get();

		$tipo_comunicaciones = TipoComunicacion::all();

		return view('empleados.ausentismos', compact('clientes','tipos', 'tipo_comunicaciones'));
	}
	public function busqueda(Request $request)
	{

		//Traits > Ausentismos
		$resultados = $this->searchAusentismos(auth()->user()->id_cliente_actual,$request);

		return array_merge($resultados,[
			'fichada_user'=>auth()->user()->fichada,
			'fichar_user'=>auth()->user()->fichar
		]);

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{

		//dd(\Session::get('consulta'));

		$trabajadores = Nomina::where('id_cliente', auth()->user()->id_cliente_actual)
		->where('estado', '=', 1)
		->orderBy('nombre', 'asc')
		->get();
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

		//dd($request->cert_archivo);
		/*foreach($request->cert_archivo as $file){
			dd($file->hashName());
		}*/

		///dd($request);

		$validatedData = $request->validate([
			'trabajador' => 'required',
			'tipo' => 'required',
			'fecha_inicio' => 'required',
			'fecha_final' => 'required',
			'tipo_comunicacion' => 'required',
			'descripcion' => 'required|string'
		]);

		if($request->incluir_certificado=='on'){
				$validatedData = $request->validate([
				'cert_institucion' => 'required',
				'cert_medico' => 'required',
				'cert_diagnostico' => 'required',
				'cert_fecha_documento' => 'required',
				//'cert_archivos' => 'required'
			]);
			if(!$request->archivos_certificado){
				return back()->withInput()->with('error', 'Debes incluir al menos 1 archivo para el certificado.');
			}
		}


		$fecha_actual = Carbon::now();
		$fecha_inicio = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio);
		$fecha_final = Carbon::createFromFormat('d/m/Y', $request->fecha_final);

		$dos_dias_adelante = Carbon::now()->addDays(2);
		$un_anio_atras = Carbon::now()->subYear(1);

		if ($fecha_inicio > $dos_dias_adelante) {
			return back()->withInput()->with('error', 'La fecha de inicio no puede ser superior a dos días adelante de la fecha actual.');
		}
		if ($fecha_inicio->lessThan($un_anio_atras)) {
			return back()->withInput()->with('error', 'La fecha de inicio puede ser hasta un año atrás, no más.');
		}

		if ($fecha_inicio->greaterThan($fecha_final)) {
			return back()->withInput()->with('error', 'La fecha de inicio no puede ser superior a la fecha final o quizás lo dejó vacío.');
		}


		if (isset($request->fecha_regreso_trabajar) && !empty($request->fecha_regreso_trabajar) && !is_null($request->fecha_regreso_trabajar)) {
			$fecha_regreso_trabajar = Carbon::createFromFormat('d/m/Y', $request->fecha_regreso_trabajar);

			if (!isset($request->fecha_final) && empty($request->fecha_final) && !is_null($request->fecha_final)) {
				return back()->withInput()->with('error', 'No puedes ingresar una fecha de regreso al trabajo sin cargar una fecha final.');
			}

			if ($fecha_final->greaterThan($fecha_regreso_trabajar)) {
				return back()->withInput()->with('error', 'La fecha final no puede ser mayor que la fecha de regreso al trabajo.');
			}
		}else{
			return back()->withInput()->with('error', 'La fecha de regreso es obligatoria.');
		}

		$buscar_ausentismos_trabajador = Ausentismo::where('id_trabajador', $request->trabajador)
		->where('fecha_regreso_trabajar', null)
		->count();

		if ($buscar_ausentismos_trabajador > 0) {
			return back()->withInput()->with('error', 'Este trabajador tiene ausentismos sin fecha de regreso cargada. No podrás cargar nuevos ausentismos hasta en tanto dicha fecha sea cargada.');
		}

		$ausentismos_solapamientos = Ausentismo::where('id_trabajador', $request->trabajador)
		->where(function($query) use ($fecha_inicio,$fecha_final){
			$query
				->where(function($query) use ($fecha_inicio){
					/// busco si se solapa la fecha de inicio
					$query
						->whereRaw("'{$fecha_inicio}' >= fecha_inicio AND '{$fecha_inicio}' < fecha_final");
				})
				->orWhere(function($query) use ($fecha_final){
					/// busco si se solapa la fecha del final
					$query
						->whereRaw( "'{$fecha_final}' >= fecha_inicio AND '{$fecha_final}' <= fecha_final");

				});
		})
		->count();

		if($ausentismos_solapamientos){
			return back()->withInput()->with('error','Las fechas seleccionadas se superponen con otra fecha de un ausentismo ya cargado.');
		}

		///chequear solapamiento de fechas



		//Guardar en base Ausentismo
		$ausentismo = new Ausentismo();

		$ausentismo->id_trabajador = $request->trabajador;
		$ausentismo->id_cliente = auth()->user()->id_cliente_actual;

		$ausentismo->id_tipo = $request->tipo;
		$ausentismo->fecha_inicio = $fecha_inicio;
		$ausentismo->fecha_final = $fecha_final;
		$ausentismo->comentario = ($request->comentario) ? $request->comentario : null;

		if (isset($request->fecha_regreso_trabajar) && !empty($request->fecha_regreso_trabajar)) {
			$ausentismo->fecha_regreso_trabajar = $fecha_regreso_trabajar;
		}else {
			$ausentismo->fecha_regreso_trabajar = $fecha_final;
		}

		// Si hay un archivo adjunto
		/*if($request->hasFile('archivo')) {
			$archivo = $request->file('archivo');
			$nombre = $archivo->getClientOriginalName();
			$ausentismo->archivo = $nombre;
			$ausentismo->hash_archivo = $archivo->hashName();
		}*/
		$ausentismo->user = auth()->user()->nombre;
		$ausentismo->save();
		// Si hay un archivo adjunto
		/*if($request->hasFile('archivo')) {
			Storage::disk('local')->put('ausentismos/trabajador/'.$ausentismo->id, $archivo);
		}*/


		//Guardar en base Comunicacion
		$comunicacion = new Comunicacion();
		$comunicacion->id_ausentismo = $ausentismo->id;
		$comunicacion->id_tipo = $request->tipo_comunicacion;
		$comunicacion->descripcion = $request->descripcion;
		$comunicacion->user = auth()->user()->nombre;
		$comunicacion->save();

		if($request->archivos_comunicacion){
			foreach($request->archivos_comunicacion as $file){

				$comunicacion_archivo = new ComunicacionArchivo;
				$comunicacion_archivo->id_comunicacion = $comunicacion->id;
				$comunicacion_archivo->archivo = $file->getClientOriginalName();
				$comunicacion_archivo->hash_archivo = $file->hashName();

				$comunicacion_archivo->save();

				Storage::disk('local')->put('comunicaciones/'.$comunicacion->id, $file);
			}
		}

		//Guardar en base Documentación si tiene
		if($request->incluir_certificado=='on'){
			$documentacion = new AusentismoDocumentacion();

			$documentacion->id_ausentismo = $ausentismo->id;
			$documentacion->institucion = $request->cert_institucion;
			$documentacion->medico = $request->cert_medico;
			$documentacion->matricula_provincial = $request->cert_matricula_provincial;
			$documentacion->matricula_nacional = $request->cert_matricula_nacional;
			$documentacion->observaciones = $request->cert_observaciones;

			$documentacion->fecha_documento = Carbon::createFromFormat('d/m/Y', $request->cert_fecha_documento);
			$documentacion->diagnostico = $request->cert_diagnostico;
			$documentacion->user = auth()->user()->nombre;
			$documentacion->save();

			foreach($request->archivos_certificado as $file){

				$doc_archivo = new AusentismoDocumentacionArchivos;
				$doc_archivo->ausentismo_documentacion_id = $documentacion->id;
				$doc_archivo->archivo = $file->getClientOriginalName();
				$doc_archivo->hash_archivo = $file->hashName();
				$doc_archivo->save();
				Storage::disk('local')->put('documentacion_ausentismo/'.$documentacion->id, $file);

				//$archivo_cert = $request->file('cert_archivo');
				//$nombre_archivo_cert = $archivo_cert->getClientOriginalName();
				//$documentacion->archivo = $nombre_archivo_cert;
				//$documentacion->hash_archivo = $archivo_cert->hashName();

			}

		}

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

		$ausentismo = Ausentismo::findOrFail($id);
		$clientes = $this->getClientesUser();

		$tipo_comunicaciones = TipoComunicacion::orderBy('nombre', 'asc')->get();

		//dd( $ausentismo->comunicaciones[1]->archivos->toArray() ? 'lleno' : 'vacio' );

		return view('empleados.ausentismos.show',compact('ausentismo','clientes','tipo_comunicaciones'));

	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{


		$ausentismo = Ausentismo::select('ausentismos.*')
			->with(['comunicaciones.tipo','comunicaciones.archivos','tipo','trabajador'=>function($query){
				$query->select('id','nombre','email','estado','dni','telefono');
			}])
			->where('id_cliente',auth()->user()->id_cliente_actual)
			->where('ausentismos.id', $id)
			->first();

		//dd($ausentismo->comunicaciones);

		$clientes = $this->getClientesUser();
		$ausentismo_tipos = AusentismoTipo::orderBy('nombre', 'asc')->get();
		$tipo_comunicaciones = TipoComunicacion::orderBy('nombre', 'asc')->get();

		return view('empleados.ausentismos.edit', compact('ausentismo', 'clientes', 'ausentismo_tipos', 'tipo_comunicaciones'));
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
			'fecha_inicio' => 'required',
			'tipo' => 'required',
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

		//dd($request->archivos_comunicacion);
		$ausentismo->fecha_inicio = $fecha_inicio;
		$ausentismo->id_tipo = $request->tipo;
		$ausentismo->comentario = ($request->comentario) ? $request->comentario : null;

		//Guardar en base Comunicacion
		if($ausentismo->comunicaciones){

			$ausentismo->comunicaciones[0]->id_tipo = $request->tipo_comunicacion;
			$ausentismo->comunicaciones[0]->descripcion = $request->descripcion;
			$ausentismo->comunicaciones[0]->save();

			/*if($ausentismo->comunicaciones[0]->archivos){
				foreach($ausentismo->comunicaciones[0]->archivos as $file){}
			}*/
			if($request->archivos_comunicacion){
				foreach($request->archivos_comunicacion as $file){

					$comunicacion_archivo = new ComunicacionArchivo;
					$comunicacion_archivo->id_comunicacion = $ausentismo->comunicaciones[0]->id;
					$comunicacion_archivo->archivo = $file->getClientOriginalName();
					$comunicacion_archivo->hash_archivo = $file->hashName();

					$comunicacion_archivo->save();

					Storage::disk('local')->put('comunicaciones/'.$ausentismo->comunicaciones[0]->id, $file);

				}
			}
		}
		//$comunicacion = new Comunicacion;
		//$comunicacion->id_ausentismo = $ausentismo->id;
		//$comunicacion->id_tipo = $request->tipo_comunicacion;
		//$comunicacion->descripcion = $request->descripcion;
		//$comunicacion->save();
		///dd($ausentismo->comunicacion);

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

		if ($ausentismo) {
			// Eliminar el archivo si existe
			$ruta_archivo = storage_path("app/ausentismos/trabajador/{$ausentismo->id}/{$ausentismo->hash_archivo}");
			if(file_exists($ruta_archivo)) unlink($ruta_archivo);

			// Eliminar la carpeta si existe
			$ruta_carpeta = storage_path("app/ausentismos/trabajador/{$ausentismo->id}");
			if(file_exists($ruta_carpeta)) rmdir($ruta_carpeta);

			// Eliminar las comunicaciones asociadas al ausentismo
			Comunicacion::where('id_ausentismo', $id)->delete();

			// Finalmente, eliminar el ausentismo
			$ausentismo->delete();
			return back()->with('success', 'Ausentismo y Comunicación asociada eliminados correctamente');
		}else{
			return back()->with('error', 'El ausentismo no existe. Consulte a soporte si tiene dudas');
		}

	}


	public function tipo(Request $request)
	{

		$validatedData = $request->validate([
			'nombre' => 'required|string',
			'incluir_indice' => 'required'
		]);

		// dd($request);
		//Guardar en base
		$tipo_ausentismo = new AusentismoTipo();
		$tipo_ausentismo->nombre = $request->nombre;
		$tipo_ausentismo->incluir_indice = $request->incluir_indice;

		//generar un color único para los gráficos
		$colorHex = sprintf("#%02x%02x%02x", rand(25, 200), rand(25, 200), rand(25, 200));
		$tipo_ausentismo->color = $colorHex;

		$tipo_ausentismo->save();

		return back()->with([
			'success'=>'Tipo de ausentismo creado con éxito',
			'id_tipo'=>$tipo_ausentismo->id
		]);
	}


	public function editarTipo(Request $request)
	{

		if ($request->tipo_editado == null || $request->id_tipo == null || $request->editar_incluir_indice == null) {
			return back()->with('error', 'No puede enviar campos vacíos');
		}
		$tipo_ausentismo = AusentismoTipo::find($request->id_tipo);
		$tipo_ausentismo->nombre = $request->tipo_editado;
		$tipo_ausentismo->color = $request->color;
		$tipo_ausentismo->incluir_indice = $request->editar_incluir_indice;
		$tipo_ausentismo->save();

		return back()->with('success', 'Tipo de ausentismo editado con éxito');
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


	public function extensionComunicacion(Request $request)
	{

		$validatedData = $request->validate([
			'fecha_final' => 'required',
			'descripcion' => 'required'
		]);

		$ausentismo = Ausentismo::findOrFail($request->id_ausentismo);
		$fecha_final = Carbon::createFromFormat('d/m/Y', $request->fecha_final);
		if ($fecha_final->lessThan($ausentismo->fecha_inicio)) {
			return back()->with('error', 'La fecha final es menor a la de inicio');
		}
		$ausentismo->fecha_final = $fecha_final;
		$ausentismo->fecha_regreso_trabajar = $fecha_final;
		$ausentismo->save();

		$comunicacion = new Comunicacion();
	  	$comunicacion->id_ausentismo = $request->id_ausentismo;
	  	$comunicacion->id_tipo = $request->id_tipo;
	  	$comunicacion->descripcion = $request->descripcion;
	  	$comunicacion->user = auth()->user()->nombre;
	  	$comunicacion->save();

	  	return redirect('empleados/comunicaciones/'.$request->id_ausentismo)->with('success', 'Comunicación guardada con éxito');

	}





}
