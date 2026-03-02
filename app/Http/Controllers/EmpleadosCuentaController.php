<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Rol;
use App\Cliente;
use App\ClienteUser;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class EmpleadosCuentaController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$roles = Rol::all();
		$clientes = ClienteUser::join('clientes', 'cliente_user.id_cliente', 'clientes.id')
			->where('cliente_user.id_user', '=', auth()->user()->id)
			->select('clientes.nombre', 'clientes.id')
			->get();
		
		return view('empleados.cuenta', compact('roles', 'clientes'));
	}


	public function store(Request $request)
	{

		$user = User::findOrFail($request->id_user);
		$user->dni = $request->dni;
		$user->sello_linea_1 = $request->sello_linea_1 ?? null;
		$user->sello_linea_2 = $request->sello_linea_2 ?? null;
		$user->sello_linea_3 = $request->sello_linea_3 ?? null;
		$user->matricula = $request->matricula;
		$user->tipo_matricula = $request->tipo_matricula;
		if (isset($request->fecha_vencimiento_matricula) && !empty($request->fecha_vencimiento_matricula)) {
			$fecha_vencimiento_matricula = Carbon::createFromFormat('d/m/Y', $request->fecha_vencimiento_matricula);
		}else {
			$fecha_vencimiento_matricula = null;
		}
		$user->fecha_vencimiento = $fecha_vencimiento_matricula;
		$user->save();



		/////////////////////// Archivo Firma Medico /////////////////
		if ($user->firma_medico == null) {

				// --- CASO 1: SUBE ARCHIVO POR PRIMERA VEZ ---
				if ($request->hasFile('firma_medico') && $request->file('firma_medico')->isValid()) {
						$archivo = $request->file('firma_medico');
						$nombre = $archivo->getClientOriginalName();
						$hash_firma_medico = $archivo->hashName();

						// Guardamos físicamente en public/storage/users/{id}/firmas_medico
						$destino = public_path("storage/users/{$request->id_user}/firmas_medico");
						
						// Mueve el archivo a la carpeta pública directamente
						$archivo->move($destino, $hash_firma_medico);

						$user->firma_medico = $nombre;
						$user->hash_firma_medico = $hash_firma_medico;
						$user->save();
				} else {
						return back()->with('error', 'No se ha seleccionado ningún archivo válido para la firma.');
				}

		} else {

				// --- CASO 2: EDITA ARCHIVO EXISTENTE ---
				if ($request->hasFile('firma_medico') && $request->file('firma_medico')->isValid()) {
						$archivo = $request->file('firma_medico');
						$nombre = $archivo->getClientOriginalName();
						$hash_firma_medico = $archivo->hashName();

						// 1. Definir ruta del archivo viejo y nuevo
						$ruta_vieja = public_path("storage/users/{$request->id_user}/firmas_medico/{$user->hash_firma_medico}");
						$destino = public_path("storage/users/{$request->id_user}/firmas_medico");

						// 2. Eliminar archivo viejo si existe
						if (File::exists($ruta_vieja)) {
								File::delete($ruta_vieja);
						}

						// 3. Mover el nuevo
						$archivo->move($destino, $hash_firma_medico);

						$user->firma_medico = $nombre;
						$user->hash_firma_medico = $hash_firma_medico;
						$user->save();
				}
		}
		/////////////////////// Fin Firma Medico /////////////////



		/////////////////////// Si hay un archivo de Titulo PARTE DELANTERA /////////////////
		if ($request->hasFile('archivo_titulo') && $request->file('archivo_titulo') > 0) {
			$archivo = $request->file('archivo_titulo');
			$nombre = $archivo->getClientOriginalName();
			$user->titulo = $nombre;
		}
		$user->save();

		// Si hay un archivo adjunto
		if ($request->hasFile('archivo_titulo') && $request->file('archivo_titulo') > 0) {

		$hash_titulo = auth()->user()->hash_titulo;
		$ruta_archivo = storage_path("app/titulos/user/{$request->id_user}/{$hash_titulo}");
		if (is_file($ruta_archivo)) {
			unlink($ruta_archivo);
		}
		Storage::disk('local')->put('titulos/user/'.$request->id_user, $archivo);

		// Completar el base el hash del archivo guardado
		$user = User::findOrFail($request->id_user);
		$user->hash_titulo = $archivo->hashName();
		$user->save();
		}
		/////////////////////// Si hay un archivo de Titulo PARTE DELANTERA /////////////////


		/////////////////////// Si hay un archivo de Titulo PARTE TRASERA /////////////////
		if ($request->hasFile('archivo_titulo_detras') && $request->file('archivo_titulo_detras') > 0) {
			$archivo = $request->file('archivo_titulo_detras');
			$nombre = $archivo->getClientOriginalName();
			$user->archivo_titulo_detras = $nombre;
		}
		$user->save();

		// Si hay un archivo adjunto
		if ($request->hasFile('archivo_titulo_detras') && $request->file('archivo_titulo_detras') > 0) {

		$hash_titulo_detras = auth()->user()->hash_titulo_detras;
		$ruta_archivo = storage_path("app/titulos/user/{$request->id_user}/{$hash_titulo_detras}");
		if (is_file($ruta_archivo)) {
			unlink($ruta_archivo);
		}
		Storage::disk('local')->put('titulos/user/'.$request->id_user, $archivo);

		// Completar el base el hash del archivo guardado
		$user = User::findOrFail($request->id_user);
		$user->hash_titulo_detras = $archivo->hashName();
		$user->save();
		}
		/////////////////////// Si hay un archivo de Titulo PARTE TRASERA /////////////////


		/////////////////////// Si hay un archivo de Matricula PARTE DELANTERA /////////////////
		if ($request->hasFile('archivo_matricula') && $request->file('archivo_matricula') > 0) {
			$archivo = $request->file('archivo_matricula');
			$nombre = $archivo->getClientOriginalName();
			$user->archivo_matricula = $nombre;
		}
		$user->save();

		// Si hay un archivo adjunto
		if ($request->hasFile('archivo_matricula') && $request->file('archivo_matricula') > 0) {

		$hash_matricula = auth()->user()->hash_matricula;
		$ruta_archivo = storage_path("app/matriculas/user/{$request->id_user}/{$hash_matricula}");
		if (is_file($ruta_archivo)) {
			unlink($ruta_archivo);
		}
		Storage::disk('local')->put('matriculas/user/'.$request->id_user, $archivo);

		// Completar el base el hash del archivo guardado
		$user = User::findOrFail($request->id_user);
		$user->hash_matricula = $archivo->hashName();
		$user->save();
		}
		/////////////////////// Si hay un archivo de Matricula PARTE DELANTERA /////////////////


		/////////////////////// Si hay un archivo de Matricula PARTE trasera /////////////////
		if ($request->hasFile('archivo_matricula_detras') && $request->file('archivo_matricula_detras') > 0) {
			$archivo = $request->file('archivo_matricula_detras');
			$nombre = $archivo->getClientOriginalName();
			$user->archivo_matricula_detras = $nombre;
		}
		$user->save();

		// Si hay un archivo adjunto
		if ($request->hasFile('archivo_matricula_detras') && $request->file('archivo_matricula_detras') > 0) {

		$hash_matricula_detras = auth()->user()->hash_matricula_detras;
		$ruta_archivo = storage_path("app/matriculas/user/{$request->id_user}/{$hash_matricula_detras}");
		if (is_file($ruta_archivo)) {
			unlink($ruta_archivo);
		}
		Storage::disk('local')->put('matriculas/user/'.$request->id_user, $archivo);

		// Completar el base el hash del archivo guardado
		$user = User::findOrFail($request->id_user);
		$user->hash_matricula_detras = $archivo->hashName();
		$user->save();
		}
		/////////////////////// Si hay un archivo de Matricula PARTE trasera /////////////////


		/////////////////////// Si hay un archivo de DNI PARTE DELANTERA /////////////////
		if ($request->hasFile('archivo_dni') && $request->file('archivo_dni') > 0) {
			$archivo = $request->file('archivo_dni');
			$nombre = $archivo->getClientOriginalName();
			$user->archivo_dni = $nombre;
		}
		$user->save();

		// Si hay un archivo adjunto
		if ($request->hasFile('archivo_dni') && $request->file('archivo_dni') > 0) {

			$hash_dni = auth()->user()->hash_dni;
			$ruta_archivo = storage_path("app/dni/user/{$request->id_user}/{$hash_dni}");
			if (is_file($ruta_archivo)) {
				unlink($ruta_archivo);
			}
			Storage::disk('local')->put('dni/user/'.$request->id_user, $archivo);

			// Completar el base el hash del archivo guardado
			$user = User::findOrFail($request->id_user);
			$user->hash_dni = $archivo->hashName();
			$user->save();
		}
		/////////////////////// Si hay un archivo de DNI PARTE DELANTERA /////////////////


		/////////////////////// Si hay un archivo de DNI PARTE TRASERA /////////////////
		if ($request->hasFile('archivo_dni_detras') && $request->file('archivo_dni_detras') > 0) {
			$archivo = $request->file('archivo_dni_detras');
			$nombre = $archivo->getClientOriginalName();
			$user->archivo_dni_detras = $nombre;
		}
		$user->save();

		// Si hay un archivo adjunto
		if ($request->hasFile('archivo_dni_detras') && $request->file('archivo_dni_detras') > 0) {

			$hash_dni_detras = auth()->user()->hash_dni_detras;
			$ruta_archivo = storage_path("app/dni/user/{$request->id_user}/{$hash_dni_detras}");
			if (is_file($ruta_archivo)) {
				unlink($ruta_archivo);
			}
			Storage::disk('local')->put('dni/user/'.$request->id_user, $archivo);

			// Completar el base el hash del archivo guardado
			$user = User::findOrFail($request->id_user);
			$user->hash_dni_detras = $archivo->hashName();
			$user->save();
		}
		/////////////////////// Si hay un archivo de DNI PARTE TRASERA /////////////////


    return back()->with('success', 'Datos cambiados correctamente');

  }



	public function cambiar_pass(Request $request)
	{

		$caracteres = strlen($request->password);

		if ($request->password == '' || $request->password == null || empty($request->password) || $caracteres < 6) {
			return back()->with('error', 'La contraseña no puede estar vacía ni tener menos de 6 caracteres');
		}

		if ($request->password != $request->cpassword) {
			return back()->with('error', 'No conciden la contraseñas');
		}

		$user = User::findOrFail($request->id_user);
		$user->password = bcrypt($request->password);
		$user->save();

		return back()->with('success', 'Contraseña cambiada correctamente');
	}

	public function downloadTitulo($id)
	{
		$hash_titulo = auth()->user()->hash_titulo;
		$ruta = storage_path("app/titulos/user/{$id}/{$hash_titulo}");
		return response()->download($ruta);
		return back();
	}

	public function downloadDni($id)
	{
		$hash_dni = auth()->user()->hash_dni;
		$ruta = storage_path("app/dni/user/{$id}/{$hash_dni}");
		return response()->download($ruta);
		return back();
	}

	public function downloadMatricula($id)
	{
		$hash_matricula = auth()->user()->hash_matricula;
		$ruta = storage_path("app/matriculas/user/{$id}/{$hash_matricula}");
		return response()->download($ruta);
		return back();
	}


	public function downloadTituloDetras($id)
	{
		$hash_titulo_detras = auth()->user()->hash_titulo_detras;
		$ruta = storage_path("app/titulos/user/{$id}/{$hash_titulo_detras}");
		return response()->download($ruta);
		return back();
	}

	public function downloadDniDetras($id)
	{
		$hash_dni_detras = auth()->user()->hash_dni_detras;
		$ruta = storage_path("app/dni/user/{$id}/{$hash_dni_detras}");
		return response()->download($ruta);
		return back();
	}

	public function downloadMatriculaDetras($id)
	{
		$hash_matricula_detras = auth()->user()->hash_matricula_detras;
		$ruta = storage_path("app/matriculas/user/{$id}/{$hash_matricula_detras}");
		return response()->download($ruta);
		return back();
	}

}
