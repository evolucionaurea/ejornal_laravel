<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

//use App\ClienteUser;
//use App\Cliente;
use App\Nomina;
use App\Http\Traits\Clientes;
use App\Http\Traits\Nominas;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use App\Ausentismo;
use App\ConsultaMedica;
use App\ConsultaEnfermeria;
use App\CovidVacuna;
use App\CovidTesteo;
use App\Preocupacional;
use App\NominaImportacion;
use App\NominaHistorial;
use Intervention\Image\Facades\Image;


class EmpleadosNominasController extends Controller
{

	use Clientes,Nominas;

	private $error_message;

	public function index()
	{
		$clientes = $this->getClientesUser();
		return view('empleados.nominas', compact('clientes'));
	}
	public function busqueda(Request $request)
	{

		if(auth()->user()->id_cliente_actual) {
			$idcliente = auth()->user()->id_cliente_actual;
		}else{
			/// si no tiene cliente actual traigo la lista de clientes que tenga y asigno al primero
			$clientes = $this->getClientesUser();
			$idcliente = $clientes->first()->id;
		}

		//Traits > Nominas
		$resultados = $this->searchNomina($idcliente,$request);

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

		$clientes = $this->getClientesUser();
		return view('empleados.nominas.create', compact('clientes'));
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
			'nombre' => 'required|string',
			'dni' => 'required|digits:8',
			'estado' => 'required',
			'sector' => 'required'
		]);

		if(!auth()->user()->id_cliente_actual){
			return back()->withInput()->with('error','Debes estar trabajando en algun cliente para poder realizar esta accion.');
		}


		///Chequear qe no exista por el dni!
		$existing_nomina = Nomina::where('dni',$request->dni)->first();
		if($existing_nomina){
			return back()->withInput()->with('error','El trabajador que intentas crear ya existe en la base de datos');
		}

		//Guardar en base
		$trabajador = new Nomina();
		$trabajador->id_cliente = auth()->user()->id_cliente_actual;
		$trabajador->nombre = $request->nombre;
		if (isset($request->email) && !empty($request->email)) {
			$trabajador->email = $request->email;
		}
		if (isset($request->telefono) && !empty($request->telefono)) {
			$trabajador->telefono = $request->telefono;
		}
		if (isset($request->calle) && !empty($request->calle)) {
			$trabajador->calle = $request->calle;
		}
		if (isset($request->nro) && !empty($request->nro)) {
			$trabajador->nro = $request->nro;
		}
		if (isset($request->entre_calles) && !empty($request->entre_calles)) {
			$trabajador->entre_calles = $request->entre_calles;
		}
		if (isset($request->localidad) && !empty($request->localidad)) {
			$trabajador->localidad = $request->localidad;
		}
		if (isset($request->partido) && !empty($request->partido)) {
			$trabajador->partido = $request->partido;
		}
		if (isset($request->cod_postal) && !empty($request->cod_postal)) {
			$trabajador->cod_postal = $request->cod_postal;
		}
		if (isset($request->observaciones) && !empty($request->observaciones)) {
			$trabajador->observaciones = $request->observaciones;
		}

		if($request->fecha_nacimiento){
			$trabajador->fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $request->fecha_nacimiento);
		}

		$trabajador->dni = preg_replace('/[^0-9]/', '', $request->dni);
		$trabajador->estado = $request->estado;
		$trabajador->sector = $request->sector;
		if ($request->estado == 0) {
			$trabajador->fecha_baja =  Carbon::now();
		}else{
			$trabajador->fecha_baja =  null;
		}

		//dd($trabajador);

		$trabajador->save();


		/// Agrego registro en el historial de nóminas
		$nomina_historial_creado = NominaHistorial::where('cliente_id',auth()->user()->id_cliente_actual)
			->orderBy('year_month','desc')
			->first();
		$nomina_historial = new NominaHistorial;
		$nomina_historial->year_month = CarbonImmutable::now()->format('Ym');
		$nomina_historial->cliente_id = auth()->user()->id_cliente_actual;

		if($nomina_historial_creado){
			//si existe el registro del mes se actualiza
			if($nomina_historial_creado->year_month==CarbonImmutable::now()->format('Ym')){
				$nomina_historial = $nomina_historial_creado;
			}
			$nomina_historial->cantidad = $nomina_historial_creado->cantidad+1;
			$nomina_historial->save();

		}else{
			//si no existe el registro del mes se crea
			$nomina_historial->cantidad = 1;
			$nomina_historial->save();
		}




		if ($request->hasFile('foto')) {
			if(!$trabajador = $this->procesar_foto($request,$trabajador)){
				return back()->with('error',$this->error_message);
			}
			$trabajador->save();
		}


		return redirect('empleados/nominas')->with('success', 'Trabajador asignado con éxito a la nómina');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$clientes = $this->getClientesUser();

		$trabajador = Nomina::findOrFail($id);


		$consultas_medicas = ConsultaMedica::where('id_nomina',$id)
			->with('diagnostico')
			->orderBy('fecha','desc')
			->get();

		$consultas_enfermeria = ConsultaEnfermeria::where('id_nomina',$id)
			->with('diagnostico')
			->orderBy('fecha','desc')
			->get();

		$ausentismos = Ausentismo::where('id_trabajador',$id)
			->with('trabajador')
			->with('tipo')
			->with('comunicacion.tipo')
			->with('documentaciones')
			->orderBy('fecha_inicio', 'desc')
			->get();


		$testeos = CovidTesteo::where('id_nomina',$id)
			->with('tipo')
			->orderBy('fecha', 'desc')
			->get();
		$vacunas = CovidVacuna::where('id_nomina',$id)
			->with('tipo')
			->orderBy('fecha', 'desc')
			->get();


		$preocupacionales = Preocupacional::where('id_nomina',$id)
			->with('trabajador')
			->whereHas('trabajador',function($query){
				$query->where('id_cliente', auth()->user()->id_cliente_actual);
			})
			->orderBy('fecha', 'desc')
			->get();


			$resumen_historial = DB::table('consultas_medicas')
			->select('fecha', 'diagnostico_consulta.nombre as tipo', 'user as usuario', DB::raw('"Consulta Médica" as evento'), 'consultas_medicas.observaciones as observaciones')
			->join('diagnostico_consulta', 'consultas_medicas.id_diagnostico_consulta', '=', 'diagnostico_consulta.id')
			->where('fecha', '<>', '0000-00-00')
			->where('user', '<>', '')
			->where('id_nomina', $id)
			->unionAll(DB::table('consultas_enfermerias')
				->select('fecha', 'diagnostico_consulta.nombre as tipo', 'user as usuario', DB::raw('"Consulta Enfermería" as evento'), 'consultas_enfermerias.observaciones as observaciones')
				->join('diagnostico_consulta', 'consultas_enfermerias.id_diagnostico_consulta', '=', 'diagnostico_consulta.id')
				->where('fecha', '<>', '0000-00-00')
				->where('user', '<>', '')
				->where('id_nomina', $id)
			)
			->unionAll(DB::table('ausentismos')
				->select('fecha_inicio as fecha', 'ausentismo_tipo.nombre as tipo', 'user as usuario', DB::raw('"Ausentismo" as evento'), 'ausentismos.comentario as observaciones')
				->join('ausentismo_tipo', 'ausentismos.id_tipo', '=', 'ausentismo_tipo.id')
				->where('fecha_inicio', '<>', '0000-00-00')
				->where('user', '<>', '')
				->where('id_trabajador', $id)
			)
			->unionAll(DB::table('preocupacionales')
				->select('fecha', DB::raw('"Archivo adjunto" as tipo'), 'nominas.nombre as usuario', DB::raw('"Exámen Médico Complementario" as evento'), 'preocupacionales.observaciones as observaciones')
				->join('nominas', 'preocupacionales.id_nomina', '=', 'nominas.id')
				->where('fecha', '<>', '0000-00-00')
				->where('id_nomina', $id)
			)
			->orderBy('fecha', 'desc')
			->get();



		// dd($resumen_historial);

		return view('empleados.nominas.show', compact(
			'trabajador',
			'consultas_medicas',
			'consultas_enfermeria',
			'ausentismos',
			'clientes',
			'vacunas',
			'testeos',
			'preocupacionales',
			'resumen_historial'
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
		$trabajador = Nomina::findOrFail($id);
		$clientes = $this->getClientesUser();

		///dd($trabajador->toArray());
		return view('empleados.nominas.edit', compact('trabajador', 'clientes'));
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
			'nombre' => 'required|string',
			'dni' => 'required|numeric|digits:8',
			'estado' => 'required',
			'sector' => 'required'
		]);




		//Actualizar en base
		$trabajador = Nomina::findOrFail($id);

		///Chequear que el dno no exista!
		$existing_nomina = Nomina::where('dni',$request->dni)
			->where('id_cliente',auth()->user()->id_cliente_actual)
			->where('id','!=',$id)
			->first();
		if($trabajador->dni != $request->dni && $existing_nomina){
			return back()->with('error','El dni que intentas poner ya existe en otro trabajador');
		}

		if($request->fecha_nacimiento){
			$trabajador->fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $request->fecha_nacimiento);
		}


		$trabajador->nombre = $request->nombre;
		$trabajador->email = $request->email;

		$trabajador->telefono = $request->telefono;
		$trabajador->dni = $request->dni;
		$trabajador->calle = $request->calle;
		$trabajador->nro = $request->nro;
		$trabajador->entre_calles = $request->entre_calles;
		$trabajador->localidad = $request->localidad;
		$trabajador->partido = $request->partido;
		$trabajador->cod_postal = $request->cod_postal;
		$trabajador->observaciones = $request->observaciones;
		$trabajador->estado = $request->estado;
		$trabajador->sector = $request->sector;
		if ($request->estado == 0) {
			$trabajador->fecha_baja =  Carbon::now();
		}else{
			$trabajador->fecha_baja =  null;
		}



		// Si hay un archivo adjunto
		if ($request->hasFile('foto') && $request->file('foto') > 0) {


			/// Si tenía una imagen la busca y la borro
			if (isset($trabajador->foto)) {
				$ruta_foto = public_path("storage/nominas/fotos/{$trabajador->id}/{$trabajador->hash_foto}");
				if(file_exists($ruta_foto)) unlink($ruta_foto);

			}
			if (isset($trabajador->thumbnail)) {
				$ruta_th = public_path("storage/nominas/fotos/{$trabajador->id}/{$trabajador->hash_thumbnail}");
				if(file_exists($ruta_th)) unlink($ruta_th);
			}

			if(!$trabajador = $this->procesar_foto($request,$trabajador)){
				return back()->with('error',$this->error_message);
			}
		}


		$trabajador->save();


		return redirect('empleados/nominas')->with('success', 'Trabajador de la nómina actualizado correctamente');

	}

	public function procesar_foto(Request $request,$trabajador){

		$foto = $request->file('foto');

		/// Chequeo que sea una imagen
		if( !str_starts_with( $foto->getMimeType(), 'image/' ) ){
			$this->error_message = 'Debes subir solamente imágenes.';
			return false;
		}

		// Me fijo si existe el directorio, sino lo creo
		if(!File::exists(public_path("storage/nominas/fotos/{$trabajador->id}"))){

			if(!File::makeDirectory(public_path("storage/nominas/fotos/{$trabajador->id}"), 0777, true)){
				$this->error_message = 'No se pudo guardar la imagen en la carpeta.';
				return false;
			}

		}

		$hash_foto = Str::random(40);
		$hash_thumbnail = Str::random(40);

		// Achico la imagen
		Image::make($foto->path())
			->resize(800,800,function($constraint){
				$constraint->aspectRatio();
				$constraint->upsize();
			})
			->save('storage/nominas/fotos/'.$trabajador->id.'/'.$hash_foto.'.'.$foto->getClientOriginalExtension());
		$trabajador->foto = $foto->getClientOriginalName();
		$trabajador->hash_foto = $hash_foto.'.'.$foto->getClientOriginalExtension();

		// Genero Thumbnail
		///$hash_th = Str::random(40);
		Image::make($foto->path())
			->resize(150,150,function($constraint){
				$constraint->aspectRatio();
				$constraint->upsize();
			})
			->save('storage/nominas/fotos/'.$trabajador->id.'/'.$hash_thumbnail.'.'.$foto->getClientOriginalExtension());

		// Guardo la imagen
		$trabajador->thumbnail = pathinfo($foto->getClientOriginalName(), PATHINFO_FILENAME).'-t.'.$foto->getClientOriginalExtension();
		$trabajador->hash_thumbnail = $hash_thumbnail.'.'.$foto->getClientOriginalExtension();


		return $trabajador;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		// Consultar los borrados logicos
		// $trabajador = Nomina::onlyTrashed()->get();

		$trabajador = Nomina::find($id);

		/// Agrego registro en el historial de nóminas
		$nomina_historial_creado = NominaHistorial::where('cliente_id',$trabajador->id_cliente)
			->orderBy('year_month','desc')
			->first();
		$nomina_historial = new NominaHistorial;
		$nomina_historial->year_month = CarbonImmutable::now()->format('Ym');
		$nomina_historial->cliente_id = $trabajador->id_cliente;

		if($nomina_historial_creado){
			//si existe el registro del mes se actualiza
			if($nomina_historial_creado->year_month==CarbonImmutable::now()->format('Ym')){
				$nomina_historial = $nomina_historial_creado;
			}
			$nomina_historial->cantidad = $nomina_historial_creado->cantidad ? $nomina_historial_creado->cantidad-1 : 0;
			$nomina_historial->save();
		}


		$trabajador->delete();
		return redirect('empleados/nominas')->with('success', 'Trabajador de la nómina eliminado correctamente');
	}


	public function cargar_excel(Request $request)
	{

		if (!$request->hasFile('archivo')) return back()->with('error', 'No has subido ningún archivo.');

		$file = $request->file('archivo');


		//$contents_utf8 = mb_convert_encoding($file, 'UTF-8', mb_detect_encoding($file,mb_list_encodings(), true));
		//dd($contents_utf8);

		// $registros = array();
		if(($fichero = fopen($file, "r"))===false) return back()->with('error','No se pudo leer el archivo. Intenta nuevamente.');


		//dd(fgetcsv($fichero, 0, ";", '"'));
		///dd(mb_detect_encoding($fichero->uri,mb_list_encodings(), true));

		// Lee los nombres de los campos
		$nombres_campos = [];
		$registros = [];
		///$num_campos = count($nombres_campos);
		$indice = 0;
		$error = false;

		// Lee los registros
		while (($fila = fgetcsv($fichero, 0, ";", '"')) !== false) {


			if($indice!==0){


				/*
				if(
					empty($fila[0]) ||
					empty($fila[1]) ||
					empty($fila[4]) ||
					empty($fila[5])
				){
					$error = true;
					break;
				}*/

				$registros[] = (object) [
					'nombre'=>iconv('ISO-8859-1', 'UTF-8//IGNORE', $fila[0]),
					'dni'=>$fila[1],
					'estado'=>$fila[2],
					'sector'=>iconv('ISO-8859-1', 'UTF-8//IGNORE', $fila[3]),
					'email'=>$fila[4],
					'telefono'=>$fila[5],
					'fecha_nacimiento'=>$fila[6],

					'calle'=>isset($fila[7]) ? iconv('ISO-8859-1', 'UTF-8//IGNORE', $fila[7]) : null,
					'nro'=>isset($fila[8]) ? $fila[8] : null,
					'entre_calles'=>isset($fila[9]) ? iconv('ISO-8859-1', 'UTF-8//IGNORE', $fila[9]) : null,
					'localidad'=>isset($fila[10]) ? iconv('ISO-8859-1', 'UTF-8//IGNORE', $fila[10]) : null,
					'partido'=>isset($fila[11]) ? iconv('ISO-8859-1', 'UTF-8//IGNORE', $fila[11]) : null,
					'cod_postal'=>isset($fila[12]) ? $fila[12] : null,
					'observaciones'=>isset($fila[13]) ? iconv('ISO-8859-1', 'UTF-8//IGNORE', $fila[13]) : null

				];
			}else{

				if(
					!isset($fila[0]) ||
					!isset($fila[1]) ||
					!isset($fila[2]) ||
					!isset($fila[3])
				){
					return back()->with('error', 'El excel no tiene las cabeceras correctas. Debe tener: nombre, dni, estado y sector obligatoriamente');
					break;
				}

			}
			$indice++;
		}
		fclose($fichero);

		if($error){
			return back()->with('error', 'El excel tiene datos mal cargados en la fila '.($indice+1).'<br>Los campos nombre, cuil, estado y sector son obligatorios.');
		}

		///dd(mb_convert_encoding($registros[0]->sector, 'UTF-8', 'ISO-8859-1'));
		//dd(mb_detect_encoding($registros[0]->sector));


		/*$errores = false;
		$vueltas = 1;
		foreach ($registros as $registro) {
			if ($registro['nombre'] == null || $registro['nombre'] == '' || $registro['estado'] == null ||
					$registro['estado'] == '' || $registro['sector'] == null || $registro['sector'] == '') {
				$respuesta_error = "El excel tiene datos mal cargados en la fila " . $vueltas;
				return back()->with('error', $respuesta_error);
			}
			if (!isset($registro['nombre']) || !isset($registro['email']) || !isset($registro['telefono']) ||
					!isset($registro['dni']) || !isset($registro['estado']) || !isset($registro['sector'])) {
				$errores = true;
			}else {
				$errores = false;
			}
			$vueltas++;
		}

		if ($errores)  return back()->with('error', 'El excel no tiene las cabeceras correctas. Debe tener: nombre, email, telefono, dni, estado y sector obligatoriamente');*/


		/// GUARDAR DATOS

		// Traigo todos los empleados de la nómina actual
		$nomina_actual = Nomina::where('id_cliente', auth()->user()->id_cliente_actual)->get();

		$empleados_borrables = [];
		$empleados_inexistentes = [];
		$empleados_actualizados = [];
		$empleados_existentes = [];



		///validar registros
		$errores = [];
		foreach($registros as $kr=>$registro){
			$dni = preg_replace('/[^0-9]/', '', $registro->dni);
			if(strlen($dni)!=8){
				$errores[] = (object) [
					'fila'=>$kr+2,
					'columna'=>'DNI',
					'valor'=>$registro->dni,
					'error'=>'Ingrese un valor válido para este campo. (sólamente números)'
				];
			}
			if(!$registro->nombre){
				$errores[] = (object) [
					'fila'=>$kr+2,
					'columna'=>'Nombre',
					'valor'=>$registro->nombre,
					'error'=>'Falta ingresar un valor en este campo.'
				];
			}
			if(!$registro->estado ){
				$errores[] = (object) [
					'fila'=>$kr+2,
					'columna'=>'Estado',
					'valor'=>$registro->estado,
					'error'=>'Falta ingresar un valor en este campo. Valores válidos: Activo | Baja'
				];
			}
			if(!$registro->sector){
				$errores[] = (object) [
					'fila'=>$kr+2,
					'columna'=>'Sector',
					'valor'=>$registro->sector,
					'error'=>'Falta ingresar un valor en este campo.'
				];
			}

			if($registro->fecha_nacimiento){
				$f_nac = explode('/',$registro->fecha_nacimiento);
				if(count($f_nac)!==3){
					$errores[] = (object) [
						'fila'=>$kr+2,
						'columna'=>'Fecha de Nacimiento',
						'valor'=>$registro->fecha_nacimiento,
						'error'=>'La fecha de nacimiento debe tener el formato dd/mm/aaaa. Ej: 01/01/1980'
					];
				}

			}

		}

		//dd($errores);
		if(!empty($errores)){
			return back()->with(compact('errores'));
		}


		foreach ($registros as $kr=>$registro){
			if(!$empleado_id = $this->buscar_en_dbb($nomina_actual,$registro)){
				// Crear empleado inexistente
				$nomina = new Nomina;
				$nomina->id_cliente = auth()->user()->id_cliente_actual;
				$empleados_inexistentes[] = $registro;
			}else{
				$nomina = Nomina::find($empleado_id);
				if($request->coincidencia==1) $empleados_actualizados[] = $registro;

				$empleados_existentes[] = $registro;
			}


			// Actualizar empleado existente
			if(!$empleado_id || ($empleado_id && $request->coincidencia==1)){
				$nomina->nombre = $registro->nombre;
				$nomina->email = strtolower($registro->email);

				$nomina->dni = preg_replace('/[^0-9]/', '', $registro->dni);

				/*$f_nac_arr = explode('/',$registro->fecha_nacimiento);
				$nomina->fecha_nacimiento = $f_nac_arr[2].'-'.$f_nac_arr[1].'-'.$f_nac_arr[0];*/
				if($registro->fecha_nacimiento){
					$nomina->fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $registro->fecha_nacimiento);
				}else{
					$nomina->fecha_nacimiento = null;
				}

				$nomina->telefono = $registro->telefono;
				$nomina->sector = $registro->sector;
				$nomina->calle = $registro->calle;
				$nomina->nro = $registro->nro;
				$nomina->entre_calles = $registro->entre_calles;
				$nomina->localidad = $registro->localidad;
				$nomina->partido = $registro->partido;
				$nomina->cod_postal = $registro->cod_postal;
				$nomina->observaciones = $registro->observaciones;
				$nomina->estado = strtolower($registro->estado)=='activo' ? 1 : 0;
				$nomina->fecha_baja = strtolower($registro->estado)=='activo' ? null : Carbon::now();
				$nomina->save();

			}
		}


		foreach ($nomina_actual as $ke=>$empleado){
			if(!$this->buscar_en_csv($registros,$empleado)){
				$empleados_borrables[] = $empleado->id;
			}
		}

		// Antes se borrada el registro. Ahora se actualiza el estado a Inactivo
		// if($request->borrar==1){
		// 	Nomina::whereIn('id',$empleados_borrables)->delete();
		// }
		if ($request->borrar == 1) {
			Nomina::whereIn('id', $empleados_borrables)->update(['estado' => 0]);
		}



		// Registrar cantidad de empleados en cada carga
		$now = CarbonImmutable::now();

		$values = [
			'total'=>count($registros),
			'nuevos'=>count($empleados_inexistentes),
			'existentes'=>count($empleados_existentes),
			'actualizados'=>count($empleados_actualizados),
			//'borrados'=>$request->borrar==1 ? count($empleados_borrables) : 0,
			'borrados'=>0,
			'year_month'=>(int) $now->format('Ym'),
			'filename'=>$file->getClientOriginalName(),
			'user_id'=>auth()->user()->id,
			'cliente_id'=>auth()->user()->id_cliente_actual,
			'updated_at'=>$now
		];

		///dd($now->startOfMonth()->subMonth());
		//dd($values);


		NominaImportacion::updateOrCreate(
			[
				'year_month'=>$now->format('Ym'),
				'cliente_id'=>auth()->user()->id_cliente_actual
			],
			$values
		);

		//dd($values);


		///Actualizo el historial de nómina
		$nomina_historial_creado = NominaHistorial::where('cliente_id',auth()->user()->id_cliente_actual)
			->orderBy('year_month','desc')
			->first();
		$total_nomina = count($nomina_actual)+count($empleados_inexistentes)-($request->borrar==1 ? count($empleados_borrables) : 0);
		$nomina_historial = new NominaHistorial;
		$nomina_historial->year_month = CarbonImmutable::now()->format('Ym');
		$nomina_historial->cliente_id = auth()->user()->id_cliente_actual;
		$nomina_historial->cantidad = $total_nomina;

		if($nomina_historial_creado){
			//si existe el registro del mes se actualiza
			if($nomina_historial_creado->year_month==CarbonImmutable::now()->format('Ym')){
				$nomina_historial = $nomina_historial_creado;
				$nomina_historial->cantidad = $total_nomina;
			}
		}
		$nomina_historial->save();


		// Mostrar registro de empleados modificados, borrados y agregados.
		return redirect('empleados/nominas')->with([
			'success'=>'Carga masiva de trabajadores de la nómina exitosa'
		]);

	}

	public function buscar_en_dbb($empleados,$registro){
		foreach($empleados as $ke=>$empleado){
			if($empleado->dni==$registro->dni) return $empleado->id;
		}
		return false;
	}
	public function buscar_en_csv($registros,$empleado){
		foreach($registros as $kr=>$registro){
			if($empleado->dni==$registro->dni) return true;
		}
		return false;
	}


	public function exportar(Request $request)
	{

		if(auth()->user()->id_cliente_actual) {
			$idcliente = auth()->user()->id_cliente_actual;
		}else{
			/// si no tiene cliente actual traigo la lista de clientes que tenga y asigno al primero
			$clientes = $this->getClientesUser();
			$idcliente = $clientes->first()->id;
		}

		//Traits > Nominas
		return $this->exportNomina($idcliente,$request);
	}


	public function historial()
	{

		$clientes = $this->getClientesUser();
		//dd($clientes);
		return view('empleados.nominas.nominas_historial', compact('clientes'));

	}
	public function historial_listado(Request $request){

		$query = NominaHistorial::select()
			->where('cliente_id',auth()->user()->id_cliente_actual);

		$total = $query->count();

		if($request->order){
			$sort = $request->columns[$request->order[0]['column']]['data'];
			$dir  = $request->order[0]['dir'];
			switch ($sort) {
				case 'year':
					$sort = 'year_month';
					break;
			}
			$query->orderBy($sort,$dir);
		}
		$query->orderBy('created_at','desc');


		$records_filtered = $query->count();
		$historial = $query->skip($request->start)->take($request->length)->get();

		foreach($historial as $k=>$hist){
			if($k===count($historial)-1){
				$hist->dif_mes_anterior = 0;
			}else{
				$hist->dif_mes_anterior = $hist->cantidad-$historial[$k+1]->cantidad;
			}
		}
		return [
			'draw'=>$request->draw,
			'recordsTotal'=>$total,
			'recordsFiltered'=>$records_filtered,
			'data'=>$historial,
			'request'=>$request->all()
		];

	}


}
