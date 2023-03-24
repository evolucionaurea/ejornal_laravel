<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Nomina;
//use App\ClienteUser;
//use App\Cliente;
use App\Http\Traits\Clientes;
use App\Http\Traits\Nominas;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use App\Ausentismo;
use App\ConsultaMedica;
use App\ConsultaEnfermeria;
use Illuminate\Support\Facades\DB;
use App\CovidVacuna;
use App\CovidTesteo;
use App\Preocupacional;
use Illuminate\Support\Facades\Storage;

class EmpleadosNominasController extends Controller
{

	use Clientes,Nominas;

	public function index()
	{
		$clientes = $this->getClientesUser();
		//dd($clientes->first()->nombre);
		return view('empleados.nominas', compact('clientes'));
	}
	public function busqueda(Request $request)
	{

		$this->request = $request;


		if(auth()->user()->id_cliente_actual) {
			$idcliente = auth()->user()->id_cliente_actual;
		}else{
			/// si no tiene cliente actual traigo la lista de clientes que tenga y asigno al primero
			$clientes = $this->getClientesUser();
			$idcliente = $clientes->first()->id;
		}


		//Traits > Nominas
		$resultados = $this->searchNomina($idcliente);

		return array_merge($resultados,['fichada_user'=>auth()->user()->fichada]);

	}


	/*public function listado(Request $request)
	{

		//Sin filtros
		///return redirect()->action('EmpleadosNominasController@index'); //Sin filtros

		//Con filtros
		$trabajadores = $this->buscar($request);
		$filtros = $request->all();
		$clientes = $this->getClientesUser();
		return view('empleados.nominas', compact('trabajadores', 'clientes', 'filtros'));
	}*/

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


		// Si hay un archivo adjunto
		if ($request->hasFile('foto') && $request->file('foto') > 0) {
			$foto = $request->file('foto');
			$nombre = $foto->getClientOriginalName();
			$trabajador->foto = $nombre;
			$trabajador->hash_foto = $foto->hashName();
			$trabajador->save();

			// Guardar foto
			Storage::disk('public')->put('nominas/fotos/'.$trabajador->id, $foto);
			// Completar el base el hash del foto guardado
			///$trabajador->save();
		}else {
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

			//Saber si ya hay una foto guardada
			if (isset($trabajador->foto) && !empty($trabajador->foto)) {
				$foto = $request->file('foto');
				$nombre = $foto->getClientOriginalName();
				$trabajador->foto = $nombre;
				$trabajador->save();

				$ruta_archivo = public_path("storage/nominas/fotos/{$trabajador->id}/{$trabajador->hash_foto}");
				unlink($ruta_archivo);
				Storage::disk('public')->put('nominas/fotos/'.$trabajador->id, $foto);


				// Completar en base el hash de la foto guardada
				$trabajador = Nomina::findOrFail($trabajador->id);
				$trabajador->hash_foto = $foto->hashName();
				$trabajador->save();
			} else {
				$foto = $request->file('foto');
				$nombre = $foto->getClientOriginalName();
				$trabajador->foto = $nombre;
				$trabajador->save();

				// Guardar foto
				Storage::disk('public')->put('nominas/fotos/'.$trabajador->id, $foto);

				// Completar el base el hash del foto guardado
				$trabajador->hash_foto = $foto->hashName();
				$trabajador->save();
			}

		}else {
			$trabajador->save();
		}


		return redirect('empleados/nominas')->with('success', 'Trabajador de la nómina actualizado correctamente');

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


		if (!$request->hasFile('archivo')) return back()->with('error', 'No has subido ningún archivo');

		$file = $request->file('archivo');

		// $registros = array();
		$fichero = fopen($file, "r");
		// Lee los nombres de los campos
		$nombres_campos = fgetcsv($fichero, 0 , ";" , '"');
		$num_campos = count($nombres_campos);
		$registros = [];



		// Lee los registros
		while (($datos = fgetcsv($fichero, 0 , ";" , '"')) !== FALSE) {

			$registro = [];
			// Crea un array asociativo con los nombres y valores de los campos
			for ($icampo = 0; $icampo < $num_campos; $icampo++) {

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
			}
			// Añade el registro leido al array de registros
			$registros[] = $registro;
		}
		fclose($fichero);

		$errores = false;
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

		if ($errores)  return back()->with('error', 'El excel no tiene las cabeceras correctas. Debe tener: nombre, email, telefono, dni, estado y sector obligatoriamente');


		/// GUARDAR DATOS

		// Traigo todos los empleados de la nómina
		$empleados_existentes = Nomina::where('id_cliente', auth()->user()->id_cliente_actual)->get();

		$empleados_borrables = [];
		$empleados_inexistentes = [];
		$empleados_actualizados = [];


		foreach ($registros as $kr=>$registro){
			if(!$empleado_id = $this->buscar_en_dbb($empleados_existentes,$registro)){
				// Crear empleado inexistente
				$nomina = new Nomina;
				$nomina->id_cliente = auth()->user()->id_cliente_actual;
				$empleados_inexistentes[] = $registro;
			}else{
				$nomina = Nomina::find($empleado_id);
				if($request->coincidencia==1) $empleados_actualizados[] = $registro;
			}


			// Actualizar empleado existente
			if(!$empleado_id || ($empleado_id && $request->coincidencia==1)){
				$nomina->nombre = $registro['nombre'];
				$nomina->email = $registro['email'];
				$nomina->dni = $registro['dni'];
				$nomina->telefono = $registro['telefono'];
				$nomina->sector = $registro['sector'];
				$nomina->calle = $registro['calle'];
				$nomina->nro = $registro['nro'];
				$nomina->entre_calles = $registro['entre_calles'];
				$nomina->localidad = $registro['localidad'];
				$nomina->partido = $registro['partido'];
				$nomina->cod_postal = $registro['cod_postal'];
				$nomina->observaciones = $registro['observaciones'];
				$nomina->estado = strtolower($registro['estado'])=='activo' ? 1 : 0;
				$nomina->fecha_baja = strtolower($registro['estado'])=='activo' ? null : Carbon::now();
				$nomina->save();

			}
		}
		foreach ($empleados_existentes as $ke=>$empleado){
			if(!$this->buscar_en_csv($registros,$empleado)){
				$empleados_borrables[] = $empleado->id;
			}
		}

		if($request->borrar==1){
			Nomina::whereIn('id',$empleados_borrables)->delete();
		}


		//dd($empleados_actualizados);

		/*foreach ($registros as $registro) {


			if ($buscar_coincidencia == null) {
				//Guardar en base
				$nomina = new Nomina();
				$nomina->id_cliente = auth()->user()->id_cliente_actual;
				$nomina->nombre = $registro['nombre'];
				if (isset($registro['email']) && !empty($registro['email'])) {
					$nomina->email = $registro['email'];
				}
				$nomina->telefono = $registro['telefono'];
				if (isset($registro['dni']) && !empty($registro['dni'])) {
					$nomina->dni = $registro['dni'];
				}
				$nomina->sector = $registro['sector'];
				if ($registro['estado'] == 'Activo') {
					$nomina->estado = 1;
					$nomina->fecha_baja =  null;
				}else {
					$nomina->estado = 0;
					$nomina->fecha_baja =  Carbon::now();
				}
				$nomina->save();

			}else {
				// 1 es actualizar los datos completos / 2 Es no subirlo y dejar el actual
				if ($request->coincidencia == 1) {
					$buscar_coincidencia->id_cliente = auth()->user()->id_cliente_actual;
					$buscar_coincidencia->nombre = $registro['nombre'];
					$buscar_coincidencia->telefono = $registro['telefono'];
					if (isset($registro['dni']) && !empty($registro['dni'])) {
						$buscar_coincidencia->dni = $registro['dni'];
					}
					$buscar_coincidencia->sector = $registro['sector'];
					if ($registro['estado'] == 'Activo') {
						$buscar_coincidencia->estado = 1;
						$buscar_coincidencia->fecha_baja =  null;
					}else {
						$buscar_coincidencia->estado = 0;
						$buscar_coincidencia->fecha_baja =  Carbon::now();
					}
					$buscar_coincidencia->save();
				}
			}

		}*/

		// Mostrar registro de empleados modificados, borrados y agregados.
		return redirect('empleados/nominas')->with([
			'success'=>'Carga masiva de trabajadores de la nómina exitosa'
		]);

	}

	public function buscar_en_dbb($empleados,$registro){
		foreach($empleados as $ke=>$empleado){
			if($empleado->dni==$registro['dni'] || $empleado->email==$registro['email']) return $empleado->id;
		}
		return false;
	}
	public function buscar_en_csv($registros,$empleado){
		foreach($registros as $kr=>$registro){
			if($empleado->dni==$registro['dni'] || $empleado->email==$registro['email']) return true;
		}
		return false;
	}


	public function exportar()
	{

		if(auth()->user()->id_cliente_actual) {
			$idcliente = auth()->user()->id_cliente_actual;
		}else{
			/// si no tiene cliente actual traigo la lista de clientes que tenga y asigno al primero
			$clientes = $this->getClientesUser();
			$idcliente = $clientes->first()->id;
		}

		//Traits > Nominas
		return $this->exportNomina($idcliente);
	}


}
