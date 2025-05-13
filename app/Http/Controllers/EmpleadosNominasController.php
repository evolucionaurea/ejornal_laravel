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
use App\Comunicacion;
use App\ConsultaMedica;
use App\ConsultaEnfermeria;
use App\CovidVacuna;
use App\CovidTesteo;
use App\TareaLiviana;
use App\Preocupacional;
use App\NominaImportacion;
use App\NominaHistorial;
use App\NominaClienteHistorial;
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

		if ($request->hasFile('foto')) {
			if(!$trabajador = $this->procesar_foto($request,$trabajador)){
				return back()->with('error',$this->error_message);
			}
			$trabajador->save();
		}

		/// Agrego registro en el historial de nóminas
		$this->nomina_historial($trabajador,'suma');


		// Agrego registro en el historial de nóminas/clientes
		NominaClienteHistorial::create([
			'nomina_id'=>$trabajador->id,
			'cliente_id'=>auth()->user()->id_cliente_actual,
			'user_id'=>auth()->user()->id
		]);

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

		return view('empleados.nominas.show',$this->perfilTrabajador($id));
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

		///Chequear que el dni no exista!
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



		/// guardo en el historial el cambio de cliente
		/// todo: soft delete del empleado en la empresa anterior y crear nuevo empleado en la empresa nueva
		if($trabajador->id_cliente != $request->id_cliente){



			///dd($tarea_liviana);
			if($has_ausentismo_tarea_liviana = $this->hasAusentismoOrTareaLiviana($trabajador)){
				return back()->with('error',$has_ausentismo_tarea_liviana);
			}

			//$trabajador->id_cliente = $request->id_cliente;
			//$trabajador->created_at = Carbon::now();

			NominaClienteHistorial::create([
				'nomina_id'=>$trabajador->id,
				'cliente_id'=>$request->id_cliente,
				'user_id'=>auth()->user()->id
			]);
			$this->nomina_historial($trabajador,'resta'); /// chequear!!
			//$trabajador->delete();
			//$trabajador = new Nomina;
			$trabajador->id_cliente = $request->id_cliente;
			$trabajador->created_at = Carbon::now(); //// se pisa la fecha original de creación!

			$this->nomina_historial($trabajador,'suma');

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
		$this->nomina_historial($trabajador,'resta');


		$trabajador->delete();
		return redirect('empleados/nominas')->with('success', 'Trabajador de la nómina eliminado correctamente');
	}

	public function nomina_historial($trabajador,$operation='suma')
	{

		$year_month = CarbonImmutable::now()->format('Ym');

		$nomina_historial = NominaHistorial::where('cliente_id',$trabajador->id_cliente)
			->where('year_month',$year_month)
			->orderBy('year_month','desc')
			->first();

		// Si tuvo un ausentismo o tarea adecuada en el mes/año actual no hay que restarlo del historial!
		$ausentismo = Ausentismo::where('id_cliente',$trabajador->id_cliente)
			->whereRaw("{$year_month} BETWEEN EXTRACT(YEAR_MONTH FROM fecha_inicio) AND EXTRACT(YEAR_MONTH FROM fecha_final)")
			->where('id_trabajador',$trabajador->id)
			->get();

		$tarea_liviana = TareaLiviana::where('id_cliente',$trabajador->id_cliente)
			->whereRaw("{$year_month} BETWEEN EXTRACT(YEAR_MONTH FROM fecha_inicio) AND EXTRACT(YEAR_MONTH FROM fecha_final)")
			->where('id_trabajador',$trabajador->id)
			->get();
		//dd($tarea_liviana);



		if($nomina_historial){

			if($operation=='resta'){

				if($ausentismo->count() || $tarea_liviana->count()){
					$resta = $nomina_historial->cantidad; /// dejo la misma cantidad que tenía
				}else{
					$resta = $nomina_historial->cantidad ? $nomina_historial->cantidad-1 : 0;
				}
				$nomina_historial->cantidad = $resta;
			}
			if($operation=='suma'){
				$nomina_historial->cantidad = $nomina_historial->cantidad+1;
			}
			$nomina_historial->save();

		}else{

			$nomina_historial = new NominaHistorial;
			$nomina_historial->year_month = CarbonImmutable::now()->format('Ym');
			$nomina_historial->cliente_id = $trabajador->id_cliente;

			if($ausentismo->count() || $tarea_liviana->count()){
				$resta = 1;
			}else{
				$resta = 0;
			}

			$nomina_historial->cantidad = $operation=='suma' ? 1 : $resta;

			$nomina_historial->save();
		}

		return true;


	}


	public function cargar_excel(Request $request)
	{


		if(!auth()->user()->id_cliente_actual) return back()->with();

		if (!$request->hasFile('archivo')) return back()->with('error', 'No has subido ningún archivo.');

		$file = $request->file('archivo');

		// $registros = array();
		if(($fichero = fopen($file, "r"))===false) return back()->with('error','No se pudo leer el archivo. Intenta nuevamente.');


		// Lee los nombres de los campos
		$nombres_campos = [];
		$registros = [];
		///$num_campos = count($nombres_campos);
		$indice = 0;
		$error = false;

		// Lee los registros
		while (($fila = fgetcsv($fichero, 0, ";", '"')) !== false) {

			if($indice!==0){

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


		/// GUARDAR DATOS
		$empleados_borrables = [];
		$empleados_inexistentes = [];
		$empleados_actualizados = [];
		$empleados_existentes = [];
		$empleados_transferidos = [];

		// Traigo todos los empleados de la nómina actual del cliente actual
		// $nomina_actual = Nomina::where('id_cliente', auth()->user()->id_cliente_actual)->get();

		// tener en cuenta toda la nómina para saber si ya existe el trabajador en otro cliente
		$nomina_actual = Nomina::all();
		$nomina_actual_cliente = Nomina::where('id_cliente', auth()->user()->id_cliente_actual)->get();

		///dd($nomina_actual->count());

		///validar registros
		$errores = [];
		foreach($registros as $kr=>$registro){
			$dni = preg_replace('/[^0-9]/', '', $registro->dni);
			if(strlen($dni)!=8){
				$errores[] = (object) [
					'fila'=>$kr+2,
					'columna'=>'DNI',
					'valor'=>$registro->dni,
					'error'=>'Ingrese un valor válido para este campo. (8 dígitos, solamente números)'
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
			if(!$registro->estado){
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



			if($request->mover==='1'){
				foreach($nomina_actual as $n_actual){
					if($n_actual->dni == $registro->dni && $n_actual->id_cliente != auth()->user()->id_cliente_actual ){

						// compruebo si tiene ausentismo o tarea liviana activo
						if($has_ausentismo_tarea_liviana = $this->hasAusentismoOrTareaLiviana($n_actual)){
							$errores[] = (object) [
								'fila'=>$kr+2,
								'columna'=>'Nombre',
								'valor'=>$registro->nombre,
								'error'=>$has_ausentismo_tarea_liviana
							];
						}
						break;
					}
				}

			}


		}

		if(!empty($errores)) return back()->with(compact('errores'));


		foreach ($registros as $kr=>$registro):

			$empleado_existente = false;
			foreach($nomina_actual as $n_actual){
				if($n_actual->dni == $registro->dni && $n_actual->email==$registro->email){
					$empleado_existente = $n_actual;
					break;
				}
			}


			if(!$empleado_existente){
				// Crear empleado inexistente
				$nomina = new Nomina;
				$nomina->id_cliente = auth()->user()->id_cliente_actual;
				$empleados_inexistentes[] = $registro;
			}else{
				//$nomina = Nomina::find($empleado);
				$nomina = clone $empleado_existente;
				if($request->coincidencia==='1') $empleados_actualizados[] = $registro;
				$empleados_existentes[] = $registro;

				// el empleado existe en otra empresa
				if( $empleado_existente->id_cliente != auth()->user()->id_cliente_actual && $request->mover==='1'){
					$empleados_transferidos[] = $empleado_existente;
					$nomina->id_cliente = auth()->user()->id_cliente_actual;
					$nomina->created_at = Carbon::now();
				}

			}

			///dd($nomina);
			///dd($empleados_inexistentes);


			// Actualizar empleado existente
			if(!$empleado_existente || ($empleado_existente && $request->coincidencia==='1')){

				$nomina->nombre = $registro->nombre;
				$nomina->email = strtolower($registro->email);

				$nomina->dni = preg_replace('/[^0-9]/', '', $registro->dni);

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


			if(!$empleado_existente){
				/// guardo en el historial de la nómina con el cliente cuando se crea
				NominaClienteHistorial::create([
					'nomina_id'=>$nomina->id,
					'cliente_id'=>auth()->user()->id_cliente_actual,
					'user_id'=>auth()->user()->id
				]);

			}else{

				if( $empleado_existente->id_cliente != auth()->user()->id_cliente_actual && $request->mover==='1' ){

					//dd($empleado_existente);

					NominaClienteHistorial::create([
						'nomina_id'=>$nomina->id,
						'cliente_id'=>auth()->user()->id_cliente_actual,
						'user_id'=>auth()->user()->id
					]);
					$this->nomina_historial($empleado_existente,'resta');
					//$trabajador->created_at = Carbon::now(); //// se pisa la fecha original de creación!
					$this->nomina_historial($nomina,'suma');

				}

			}


		endforeach; // end registros


		//dd($nomina_actual->count());

		// ojo con esto!
		//$nomina_actual = Nomina::where('id_cliente','!=',auth()->user()->id_cliente_actual)->get();


		if($request->borrar==='1'){

			foreach ($nomina_actual_cliente as $ke=>$empleado){
				//$empleado->refresh();
				foreach($registros as $kr=>$registro){
					if($empleado->dni==$registro->dni && $empleado->email==$registro->email){
						continue 2;
					}
				}

				$empleados_borrables[] = $empleado->id;
			}

			//dd($empleados_borrables);

			// Antes se borrada el registro. Ahora se actualiza el estado a Inactivo
			// Nomina::whereIn('id',$empleados_borrables)->delete();
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
		NominaImportacion::updateOrCreate(
			[
				'year_month'=>$now->format('Ym'),
				'cliente_id'=>auth()->user()->id_cliente_actual
			],
			$values
		);

		//dd($values);


		/// Actualizo el historial de nómina
		$nomina_historial_creado = NominaHistorial::where('cliente_id',auth()->user()->id_cliente_actual)
			->orderBy('year_month','desc')
			->first();
		$total_nomina = count($nomina_actual)+count($empleados_inexistentes)-($request->borrar==='1' ? count($empleados_borrables) : 0);
		$nomina_historial = new NominaHistorial;
		$nomina_historial->year_month = CarbonImmutable::now()->format('Ym');
		$nomina_historial->cliente_id = auth()->user()->id_cliente_actual;
		$nomina_historial->cantidad = $total_nomina;
		if($nomina_historial_creado){
			// si existe el registro del mes se actualiza
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


	public function movimientos(){
		$clientes = $this->getClientesUser();
		return view('empleados.nominas.movimientos',compact('clientes'));
	}
	public function movimientos_search(Request $request){
		$cliente_ids = $this->getClientesIds();
		return $this->movimientosListado($cliente_ids,$request);
	}


}
