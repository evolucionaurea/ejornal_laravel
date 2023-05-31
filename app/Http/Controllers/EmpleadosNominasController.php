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
use Intervention\Image\Facades\Image;


class EmpleadosNominasController extends Controller
{

	use Clientes,Nominas;

	private $error_message;

	public function index()
	{
		$clientes = $this->getClientesUser();
		//dd($clientes->first()->nombre);
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

		return array_merge($resultados,['fichada_user'=>auth()->user()->fichada]);

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
			'email' => 'required|email',
			'estado' => 'required',
			'sector' => 'required'
		]);

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
		$trabajador->dni = $request->dni;
		$trabajador->estado = $request->estado;
		$trabajador->sector = $request->sector;
		if ($request->estado == 0) {
			$trabajador->fecha_baja =  Carbon::now();
		}else{
			$trabajador->fecha_baja =  null;
		}

		$trabajador->save();


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

		$consultas_medicas = ConsultaMedica::join('diagnostico_consulta', 'consultas_medicas.id_diagnostico_consulta', 'diagnostico_consulta.id')
		->where('consultas_medicas.id_nomina', $id)
		->select('consultas_medicas.fecha', 'consultas_medicas.amerita_salida', 'consultas_medicas.peso',
		'consultas_medicas.altura', 'consultas_medicas.imc', 'consultas_medicas.glucemia', 'consultas_medicas.saturacion_oxigeno',
		'consultas_medicas.tension_arterial', 'consultas_medicas.frec_cardiaca', 'consultas_medicas.derivacion_consulta', 'consultas_medicas.anamnesis',
		'consultas_medicas.tratamiento', 'consultas_medicas.observaciones', DB::raw('diagnostico_consulta.nombre diagnostico'), 'consultas_medicas.created_at')
		->orderBy('consultas_medicas.fecha', 'desc')
		->get();


		$consultas_enfermeria = ConsultaEnfermeria::join('diagnostico_consulta', 'consultas_enfermerias.id_diagnostico_consulta', 'diagnostico_consulta.id')
		->where('consultas_enfermerias.id_nomina', $id)
		->select('consultas_enfermerias.fecha', 'consultas_enfermerias.amerita_salida', 'consultas_enfermerias.peso',
		'consultas_enfermerias.altura', 'consultas_enfermerias.imc', 'consultas_enfermerias.glucemia', 'consultas_enfermerias.saturacion_oxigeno',
		'consultas_enfermerias.tension_arterial', 'consultas_enfermerias.frec_cardiaca', 'consultas_enfermerias.derivacion_consulta',
		'consultas_enfermerias.observaciones', DB::raw('diagnostico_consulta.nombre diagnostico'), 'consultas_enfermerias.created_at')
		->orderBy('consultas_enfermerias.fecha', 'desc')
		->get();

		$ausentismos = Ausentismo::join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
		->where('ausentismos.id_trabajador', $id)
		->select('ausentismos.id', 'ausentismos.fecha_inicio', 'ausentismos.fecha_final', 'ausentismos.fecha_regreso_trabajar',
		'ausentismos.archivo', 'ausentismos.hash_archivo', DB::raw('ausentismo_tipo.nombre tipo'), 'ausentismos.created_at')
		->orderBy('ausentismos.fecha_inicio', 'desc')
		->get();

		$testeos = CovidTesteo::where('id_nomina', $id)
		->join('covid_testeos_tipo', 'covid_testeos.id_tipo', 'covid_testeos_tipo.id')
		->select('covid_testeos_tipo.nombre', 'covid_testeos.resultado', 'covid_testeos.laboratorio', 'covid_testeos.fecha')
		->orderBy('covid_testeos.fecha', 'desc')
		->get();

		$vacunas = CovidVacuna::where('id_nomina', $id)
		->join('covid_vacunas_tipo', 'covid_vacunas.id_tipo', 'covid_vacunas_tipo.id')
		->select('covid_vacunas_tipo.nombre', 'covid_vacunas.institucion', 'covid_vacunas.fecha')
		->orderBy('covid_vacunas.fecha', 'desc')
		->get();

		$preocupacionales = Preocupacional::join('nominas', 'preocupacionales.id_nomina', 'nominas.id')
		->where('nominas.id_cliente', auth()->user()->id_cliente_actual)
		->where('preocupacionales.id_nomina', $id)
		->select('preocupacionales.id', 'nominas.nombre', 'preocupacionales.observaciones', 'preocupacionales.archivo',
		'preocupacionales.hash_archivo', 'preocupacionales.created_at', 'preocupacionales.fecha')
		->orderBy('preocupacionales.fecha', 'desc')
		->get();

		return view('empleados.nominas.show', compact('trabajador', 'consultas_medicas',
		'consultas_enfermeria', 'ausentismos', 'clientes', 'vacunas', 'testeos', 'preocupacionales'));
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
			'email' => 'required|email',
			'estado' => 'required',
			'sector' => 'required'
		]);

		//Actualizar en base
		$trabajador = Nomina::findOrFail($id);
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

		$trabajador = Nomina::find($id)->delete();
		return redirect('empleados/nominas')->with('success', 'Trabajador de la nómina eliminado correctamente');
	}


	public function cargar_excel(Request $request)
	{

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

				if(
					empty($fila[0]) ||
					empty($fila[1]) ||
					empty($fila[4]) ||
					empty($fila[5])
				){
					$error = true;
					break;
				}

				$registros[] = (object) [
					'nombre'=>$fila[0],
					'email'=>$fila[1],
					'telefono'=>$fila[2],
					'dni'=>$fila[3],
					'estado'=>$fila[4],
					'sector'=>$fila[5],

					'calle'=>isset($fila[6]) ? $fila[6] : null,
					'nro'=>isset($fila[7]) ? $fila[7] : null,
					'entre_calles'=>isset($fila[8]) ? $fila[8] : null,
					'localidad'=>isset($fila[9]) ? $fila[9] : null,
					'partido'=>isset($fila[10]) ? $fila[10] : null,
					'cod_postal'=>isset($fila[11]) ? $fila[11] : null,
					'observaciones'=>isset($fila[12]) ? $fila[12] : null

				];
			}else{


				if(
					!isset($fila[0]) ||
					!isset($fila[1]) ||
					!isset($fila[3]) ||
					!isset($fila[4]) ||
					!isset($fila[5])
				){
					return back()->with('error', 'El excel no tiene las cabeceras correctas. Debe tener: nombre, email, telefono, dni, estado y sector obligatoriamente');
					break;

				}


			}

			///$registro = [];
			// Crea un array asociativo con los nombres y valores de los campos
			/*for ($icampo = 0; $icampo < $num_campos; $icampo++) {

				if ($datos[$icampo] !== '') {
					switch ($icampo) {
						case 0:
							$registro['nombre'] = $datos[$icampo];
							break;
						case 1:
							$registro['email'] = $datos[$icampo];
							break;
						case 2:
							$registro['telefono'] = $datos[$icampo];
							break;
						case 3:
							$registro['dni'] = $datos[$icampo];
							break;
						case 4:
							$registro['estado'] = $datos[$icampo];
							break;
						case 5:
							$registro['sector'] = $datos[$icampo];
							break;
						case 6:
							$registro['calle'] = $datos[$icampo];
							break;
						case 7:
							$registro['nro'] = $datos[$icampo];
							break;
						case 8:
							$registro['entre_calles'] = $datos[$icampo];
							break;
						case 9:
							$registro['localidad'] = $datos[$icampo];
							break;
						case 10:
							$registro['partido'] = $datos[$icampo];
							break;
						case 11:
							$registro['cod_postal'] = $datos[$icampo];
							break;
						case 12:
							$registro['observaciones'] = $datos[$icampo];
							break;
					}
				}else {
					switch ($icampo) {
						case 0:
							$registro['nombre'] = '';
							break;
						case 1:
							$registro['email'] = '';
							break;
						case 2:
							$registro['telefono'] = '';
							break;
						case 3:
							$registro['dni'] = '';
							break;
						case 4:
							$registro['estado'] = '';
							break;
						case 5:
							$registro['sector'] = '';
							break;
						case 6:
							$registro['calle'] = '';
							break;
						case 7:
							$registro['nro'] = '';
							break;
						case 8:
							$registro['entre_calles'] = '';
							break;
						case 9:
							$registro['localidad'] = '';
							break;
						case 10:
							$registro['partido'] = '';
							break;
						case 11:
							$registro['cod_postal'] = '';
							break;
						case 12:
							$registro['observaciones'] = '';
							break;
					}
				}
			}*/
			// Añade el registro leido al array de registros


			$indice++;
		}
		fclose($fichero);

		if($error){
			return back()->with('error', 'El excel tiene datos mal cargados en la fila '.($indice+1).'<br>Los campos nombre, email, estado y sector son obligatorios.');
		}



		///dd($registros[0]);


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
				$nomina->dni = $registro->dni;
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

		if($request->borrar==1){
			Nomina::whereIn('id',$empleados_borrables)->delete();
		}


		// Registrar cantidad de empleados en cada carga
		$now = CarbonImmutable::now();

		$values = [
			'total'=>count($registros),
			'nuevos'=>count($empleados_inexistentes),
			'existentes'=>count($empleados_existentes),
			'actualizados'=>count($empleados_actualizados),
			'borrados'=>$request->borrar==1 ? count($empleados_borrables) : 0,
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

		// Mostrar registro de empleados modificados, borrados y agregados.
		return redirect('empleados/nominas')->with([
			'success'=>'Carga masiva de trabajadores de la nómina exitosa'
		]);

	}

	public function buscar_en_dbb($empleados,$registro){
		foreach($empleados as $ke=>$empleado){
			if($empleado->dni==$registro->dni || $empleado->email==$registro->email) return $empleado->id;
		}
		return false;
	}
	public function buscar_en_csv($registros,$empleado){
		foreach($registros as $kr=>$registro){
			if($empleado->dni==$registro->dni || $empleado->email==$registro->email) return true;
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


}
