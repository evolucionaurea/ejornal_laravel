<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Rol;
use App\Estado;
use App\Cliente;
use App\ClienteUser;
use App\Especialidad;
use App\FichadaNueva;
use App\Grupo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;
use DateTime;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\UserMatricula;

class AdminUserController extends Controller
{
	public function index()
	{
		$users = User::join('roles', 'users.id_rol', '=', 'roles.id')
			->leftJoin('especialidades', 'users.id_especialidad', 'especialidades.id')
			->select('users.*', DB::raw('roles.nombre rol'), DB::raw('especialidades.nombre especialidad'))
			->orderBy('nombre', 'asc')
			->get();

		$users_sin_empresas = [];
		foreach ($users as $user) {
			if ($user->id_rol == 2 && $user->id_cliente_actual == null) {
				$users_sin_empresas[] = $user;
			}
		}

		$roles = Rol::orderBy('nombre', 'asc')->get();
		$clientes = Cliente::orderBy('nombre', 'asc')->get();
		$grupos = Grupo::orderBy('nombre', 'asc')->get();

		return view('admin.users', compact('users', 'users_sin_empresas', 'roles', 'clientes', 'grupos'));
	}

	public function busqueda(Request $request)
	{
		$query_users = User::select('users.*', DB::raw('roles.nombre rol'), DB::raw('especialidades.nombre especialidad'))
			->join('roles', 'users.id_rol', '=', 'roles.id')
			->leftJoin('especialidades', 'users.id_especialidad', 'especialidades.id')
			->orderBy('nombre', 'asc');

		if (isset($request->especialidad)) $query_users->where('users.id_especialidad', $request->especialidad);
		if (isset($request->rol)) $query_users->where('users.id_rol', $request->rol);
		if (isset($request->estado)) $query_users->where('users.estado', $request->estado);
		if (isset($request->fichada)) $query_users->where('users.fichada', $request->fichada);
		if (isset($request->grupo)) $query_users->where('users.id_grupo', $request->grupo);

		$query_users->with('clientes_user')->with('grupo')->with('cliente_relacionar');

		if (isset($request->cliente)) {
			$query_users
				->whereHas('clientes_user', function ($query) {
					global $request;
					return $query->where('id_cliente', '=', $request->cliente);
				})
				->orWhere('id_cliente_relacionar', $request->cliente);
		}

		$users = $query_users->get();

		return [
			'results' => $users,
			'request' => $request->all()
		];
	}

	public function create()
	{
		$roles = Rol::orderBy('nombre', 'asc')->get();
		$clientes = Cliente::orderBy('nombre', 'asc')->get();
		$especialidades = Especialidad::orderBy('nombre', 'asc')->get();
		$grupos = Grupo::orderBy('nombre', 'asc')->get();
		
		$matriculas_form = $this->buildMatriculasForm(null);

		return view('admin.users.create', compact('roles', 'clientes', 'especialidades', 'grupos', 'matriculas_form'));
	}

	public function store(Request $request)
	{
		$request->validate([
			'nombre' => 'required|string',
			'email' => 'required|email',
			'estado' => 'required|numeric',
			'rol' => 'required|numeric',
			'password' => 'required|min:6',
			'sexo' => 'nullable|string|in:M,F,X,O',
		]);

		if ($request->cuil != null) {
			$buscar_exitencia_cuil = User::where('cuil', $request->cuil)->get();
			if (count($buscar_exitencia_cuil) != 0) {
				return back()->withInput($request->input())->with('error', 'El Cuil ingresado ya fue cargado para otro usuario. Revíselo.');
			}
		}

		if (empty($request->password) || empty($request->cpassword)) {
			return back()->withInput($request->input())->with('error', 'Ningun campo de contraseña puede estar vacío');
		}

		if ($request->password != $request->cpassword) {
			return back()->withInput($request->input())->with('error', 'Las contraseñas no coinciden. Revíselas por favor.');
		}

		if ($request->rol == 2) {
			if (!isset($request->clientes) || empty($request->clientes) || count($request->clientes) == 0 || is_null($request->clientes)) {
				return back()->withInput($request->input())->with('error', 'No puede crear a un usuario empleado sin definir en que empresa/s trabajará.');
			}
		}

		$email_existente = User::where('email', $request->email)->get();
		if (count($email_existente) > 0) {
			return back()->withInput($request->input())->with('error', 'Ya existe un usuario con el email ingresado.');
		}

		$user = new User();
		$user->nombre = $request->nombre;
		$user->email = $request->email;
		$user->sexo = $request->sexo;
		$user->estado = $request->estado;
		$user->id_rol = $request->rol;
		$user->password = bcrypt($request->password);

		if (isset($request->personal_interno) && !empty($request->personal_interno) && $request->personal_interno == 'on' && $request->rol == 2) {
			$user->personal_interno = 1;
		} else {
			$user->personal_interno = 0;
		}

		if ($request->rol == 1) {
			$user->permiso_edicion_fichada = $request->permiso_edicion_fichada;
		}

		if ($request->rol == 2) {
			$user->permiso_desplegables = $request->permiso_desplegables;
			$user->id_especialidad = $request->especialidad;
			$user->id_cliente_actual = $request->clientes[0];
			$user->onedrive = $request->onedrive;
			$user->fichar = $request->fichar;

			if ($request->calle != null) $user->calle = $request->calle;
			if ($request->nro != null) $user->nro = $request->nro;
			if ($request->entre_calles != null) $user->entre_calles = $request->entre_calles;
			if ($request->localidad != null) $user->localidad = $request->localidad;
			if ($request->partido != null) $user->partido = $request->partido;
			if ($request->cod_postal != null) $user->cod_postal = $request->cod_postal;
			if ($request->observaciones != null) $user->observaciones = $request->observaciones;
		}

		$user->cuil = $request->cuil;

		if (isset($request->contratacion) && $request->contratacion != 0) {
			$user->contratacion = $request->contratacion;
		} else {
			$user->contratacion = null;
		}

		if ($request->rol == 3) {
			$user->id_cliente_relacionar = $request->id_cliente_original;
		}

		if ($request->rol == 4) {
			$user->id_grupo = $request->id_grupo;
		}

		$user->save();

		// ✅ docs médicos si corresponde
		if ($this->esEmpleadoMedico($request)) {
			$this->syncMedicoDocs($user, $request);
		}

		// Relación cliente-user para empleados
		if ($request->rol == 2) {
			$clientes_seleccionados = $request->clientes;
			foreach ($clientes_seleccionados as $value) {
				$cliente_user = new ClienteUser();
				$cliente_user->id_cliente = $value;
				$cliente_user->id_user = $user->id;
				$cliente_user->save();
			}
		}

		return redirect('admin/users')->with('success', 'Usuario guardado con éxito');
	}

	public function show($id)
	{
		$user = User::where('users.id', $id)
			->join('roles', 'users.id_rol', 'roles.id')
			->join('grupos', 'grupos.id_grupo', 'grupos.id')
			->select(
				'users.nombre',
				'users.cuil',
				'users.estado',
				'users.email',
				'users.dni',
				DB::raw('roles.nombre rol'),
				'users.id_rol',
				'users.id_cliente_relacionar',
				'grupos.nombre grupo'
			)
			->first();

		switch ($user->id_rol) {
			case 1:
				$datos = [];
				break;
			case 2:
				$datos = FichadaNueva::where('id_user', $id)->take(30)->get();
				break;
			case 3:
				$datos = Cliente::find($user->id_cliente_relacionar);
				break;
			default:
				$datos = [];
				break;
		}

		return view('admin.users.show', compact('user', 'datos'));
	}

	public function edit($id)
	{
		$user = User::findOrFail($id);

		$roles = Rol::orderBy('nombre', 'asc')->get();
		$clientes = Cliente::orderBy('nombre', 'asc')->get();
		$especialidades = Especialidad::orderBy('nombre', 'asc')->get();

		$buscar_clientes_asignados = ClienteUser::where('id_user', $id)->get();
		$clientes_seleccionados = [];
		foreach ($buscar_clientes_asignados as $asignados) {
			$clientes_seleccionados[] = $asignados->id_cliente;
		}

		$matriculas_form = $this->buildMatriculasForm($user);

		return view('admin.users.edit', compact(
			'user',
			'roles',
			'clientes',
			'clientes_seleccionados',
			'especialidades',
			'matriculas_form'
		));
	}

	public function update(Request $request, $id)
	{
		$request->validate([
			'nombre' => 'required|string',
			'email' => 'required|email',
			'estado' => 'required|numeric',
			'sexo' => 'nullable|string|in:M,F,X,O',
		]);

		if ($request->rol == 2) {
			if (!isset($request->clientes) || empty($request->clientes) || count($request->clientes) == 0 || is_null($request->clientes)) {
				return back()->withInput($request->input())->with('error', 'No puede crear a un usuario empleado sin definir en que empresa/s trabajará.');
			}

			$user = User::findOrFail($id);

			if ($user->fichada == 1 && $request->fichar == 0) {

				$egreso = Carbon::now();
				$user->fichada = 0;
				$user->save();

				$agent = new Agent();
				$device = $agent->platform();

				$fichada = FichadaNueva::where('id_user', $user->id)->latest()->first();
				$fichada->egreso = $egreso;

				$f_ingreso = new DateTime($fichada->ingreso);
				$f_egreso = new DateTime();
				$time = $f_ingreso->diff($f_egreso);
				$tiempo_dedicado = $time->days . ' días ' . $time->format('%H horas %i minutos %s segundos');

				$fichada->id_user = $id;
				$fichada->id_cliente = $user->id_cliente_actual;
				$fichada->ip = \Request::ip();
				$fichada->dispositivo = $device;
				$fichada->tiempo_dedicado = $tiempo_dedicado;
				$fichada->save();
			}
		}

		if ($request->cuil != null) {
			$user_actual = User::findOrFail($id);
			if ($user_actual->cuil != $request->cuil) {
				$buscar_exitencia_cuil = User::where('cuil', $request->cuil)->get();
				if (count($buscar_exitencia_cuil) != 0) {
					return back()->withInput($request->input())->with('error', 'El Cuil ingresado ya fue cargado para otro usuario. Revíselo.');
				}
			}
		}

		$user = User::findOrFail($id);
		$user->nombre = $request->nombre;
		$user->sexo = $request->sexo;
		$user->email = $request->email;
		$user->estado = $request->estado;

		if ($request->rol == 1) {
			$user->permiso_edicion_fichada = $request->permiso_edicion_fichada;
		}

		if (isset($request->personal_interno) && !empty($request->personal_interno) && $request->personal_interno == 'on' && $request->rol == 2) {
			$user->personal_interno = 1;
		} else {
			$user->personal_interno = 0;
		}

		if ($request->rol == 2) {
			$user->permiso_desplegables = $request->permiso_desplegables;
			$user->id_especialidad = $request->especialidad;
			$user->fichar = $request->fichar;

			if ($user->id_cliente_actual == null) {
				$user->id_cliente_actual = $request->clientes[0];
			}

			$user->onedrive = $request->onedrive;

			if ($request->calle != null) $user->calle = $request->calle;
			if ($request->nro != null) $user->nro = $request->nro;
			if ($request->entre_calles != null) $user->entre_calles = $request->entre_calles;
			if ($request->localidad != null) $user->localidad = $request->localidad;
			if ($request->partido != null) $user->partido = $request->partido;
			if ($request->cod_postal != null) $user->cod_postal = $request->cod_postal;
			if ($request->observaciones != null) $user->observaciones = $request->observaciones;
		}

		$user->cuil = $request->cuil;

		if (isset($request->contratacion) && $request->contratacion != 0) {
			$user->contratacion = $request->contratacion;
		} else {
			$user->contratacion = null;
		}

		$user->save();

		// ✅ docs médicos si corresponde
		if ($this->esEmpleadoMedico($request)) {
			$this->syncMedicoDocs($user, $request);
		}

		// Re-sync clientes empleados
		if ($request->rol == 2) {
			ClienteUser::where('id_user', $id)->delete();

			$clientes_seleccionados = $request->clientes;

			if (!in_array($user->id_cliente_actual, $clientes_seleccionados)) {
				$user->id_cliente_actual = $clientes_seleccionados[0];
				$user->save();
			}

			foreach ($clientes_seleccionados as $value) {
				$cliente_user = new ClienteUser();
				$cliente_user->id_cliente = $value;
				$cliente_user->id_user = $user->id;
				$cliente_user->save();
			}
		}

		return redirect('admin/users')->with('success', 'Usuario actualizado con éxito');
	}

	public function destroy($id)
	{
		User::find($id)->delete();
		return redirect('admin/users')->with('error', 'Usuario eliminado correctamente');
	}

	public function reset_password(Request $request)
	{
		if (empty($request->nueva_pass) || empty($request->confirm_nueva_pass) || $request->nueva_pass == null || $request->confirm_nueva_pass == null) {
			return back()->with('error', 'Ningun campo de contraseña puede estar vacío');
		}

		if ($request->nueva_pass != $request->confirm_nueva_pass) {
			return back()->with('error', 'Las contraseñas no coinciden. Revíselas por favor.');
		}

		$user = User::findOrFail($request->id_user);
		$user->password = bcrypt($request->nueva_pass);
		$user->save();

		return back()->with('success', 'Contraseña cambiada correctamente');
	}

	// ============================================================
	// Helpers de guardado (DISK public: storage/app/public/users/..)
	// ============================================================

	private function ensureDiskDir(string $dir): void
	{
		if (!Storage::disk('public')->exists($dir)) {
			Storage::disk('public')->makeDirectory($dir);
		}
	}

	/**
	 * Guarda archivo en storage/app/public/users/{id}/{folder}/{hash}
	 * y actualiza columnas nameCol/hashCol en users.
	 * Además limpia duplicados: deja solo $keepHashes.
	 */
	private function savePublicUserFile(User $user, \Illuminate\Http\UploadedFile $file, string $folder, string $nameCol, string $hashCol, array $keepHashes = []): void
	{
		$dir = "users/{$user->id}/{$folder}";
		$this->ensureDiskDir($dir);

		// borrar anterior referenciado en DB
		$oldHash = (string) ($user->{$hashCol} ?? '');
		if ($oldHash !== '' && Storage::disk('public')->exists("{$dir}/{$oldHash}")) {
			Storage::disk('public')->delete("{$dir}/{$oldHash}");
		}

		$hash = $file->hashName();
		Storage::disk('public')->putFileAs($dir, $file, $hash);

		$user->forceFill([
			$nameCol => $file->getClientOriginalName(),
			$hashCol => $hash,
		])->save();

		// limpiar duplicados
		$allowed = array_values(array_unique(array_filter(array_merge($keepHashes, [$hash]))));
		$files = Storage::disk('public')->files($dir);

		foreach ($files as $path) {
			$bn = basename($path);
			if (!in_array($bn, $allowed, true)) {
				Storage::disk('public')->delete($path);
			}
		}
	}

	private function filePathPublicOrLegacy(?string $publicRel, ?string $legacyAbs): ?string
	{
		// publicRel: relativo a storage/app/public
		if ($publicRel && Storage::disk('public')->exists($publicRel)) {
			return storage_path("app/public/{$publicRel}");
		}
		if ($legacyAbs && is_file($legacyAbs)) return $legacyAbs;
		return null;
	}

	// ============================================================
	// Downloads (admin): SIEMPRE por id_user (nunca auth())
	// ============================================================

	public function downloadTitulo($id)
	{
		$user = User::findOrFail($id);
		$hash = (string) $user->hash_titulo;

		if ($hash === '') return back()->with('error', 'No hay título adjunto.');

		$publicRel = "users/{$id}/titulos/{$hash}";
		$legacyAbs = storage_path("app/titulos/user/{$id}/{$hash}");

		$ruta = $this->filePathPublicOrLegacy($publicRel, $legacyAbs);
		if (!$ruta) return back()->with('error', 'El archivo no existe en el servidor.');

		return response()->download($ruta, $user->titulo ?: $hash);
	}

	public function downloadTituloDetras($id)
	{
		$user = User::findOrFail($id);
		$hash = (string) $user->hash_titulo_detras;

		if ($hash === '') return back()->with('error', 'No hay título (dorso) adjunto.');

		$publicRel = "users/{$id}/titulos/{$hash}";
		$legacyAbs = storage_path("app/titulos/user/{$id}/{$hash}");

		$ruta = $this->filePathPublicOrLegacy($publicRel, $legacyAbs);
		if (!$ruta) return back()->with('error', 'El archivo no existe en el servidor.');

		return response()->download($ruta, $user->archivo_titulo_detras ?: $hash);
	}

	public function downloadDni($id)
	{
		$user = User::findOrFail($id);
		$hash = (string) $user->hash_dni;

		if ($hash === '') return back()->with('error', 'No hay DNI adjunto.');

		$publicRel = "users/{$id}/dni/{$hash}";
		$legacyAbs = storage_path("app/dni/user/{$id}/{$hash}");

		$ruta = $this->filePathPublicOrLegacy($publicRel, $legacyAbs);
		if (!$ruta) return back()->with('error', 'El archivo no existe en el servidor.');

		return response()->download($ruta, $user->archivo_dni ?: $hash);
	}

	public function downloadDniDetras($id)
	{
		$user = User::findOrFail($id);
		$hash = (string) $user->hash_dni_detras;

		if ($hash === '') return back()->with('error', 'No hay DNI (dorso) adjunto.');

		$publicRel = "users/{$id}/dni/{$hash}";
		$legacyAbs = storage_path("app/dni/user/{$id}/{$hash}");

		$ruta = $this->filePathPublicOrLegacy($publicRel, $legacyAbs);
		if (!$ruta) return back()->with('error', 'El archivo no existe en el servidor.');

		return response()->download($ruta, $user->archivo_dni_detras ?: $hash);
	}

	public function downloadMatricula($id, Request $request)
	{
		// $id = id_user SIEMPRE
		$user = User::findOrFail($id);

		$tipo = strtoupper(trim((string) $request->query('tipo', 'MN')));

		$m = UserMatricula::where('id_user', $user->id)->where('tipo', $tipo)->first();

		// fallback: si no existe ese tipo, usamos la primera disponible
		if (!$m) {
			$m = UserMatricula::where('id_user', $user->id)->orderBy('id', 'asc')->first();
		}

		if (!$m || empty($m->hash_frente)) {
			return back()->with('error', 'No hay matrícula (frente) adjunta.');
		}

		$publicRel = "users/{$user->id}/matriculas/{$m->tipo}/{$m->hash_frente}";
		$legacyAbs = storage_path("app/matriculas/user/{$user->id}/{$m->tipo}/{$m->hash_frente}");

		$ruta = $this->filePathPublicOrLegacy($publicRel, $legacyAbs);
		if (!$ruta) return back()->with('error', 'El archivo no existe en el servidor.');

		return response()->download($ruta, $m->archivo_frente ?: $m->hash_frente);
	}

	public function downloadMatriculaDetras($id, Request $request)
	{
		$user = User::findOrFail($id);

		$tipo = strtoupper(trim((string) $request->query('tipo', 'MN')));

		$m = UserMatricula::where('id_user', $user->id)->where('tipo', $tipo)->first();

		if (!$m) {
			$m = UserMatricula::where('id_user', $user->id)->orderBy('id', 'asc')->first();
		}

		if (!$m || empty($m->hash_dorso)) {
			return back()->with('error', 'No hay matrícula (dorso) adjunta.');
		}

		$publicRel = "users/{$user->id}/matriculas/{$m->tipo}/{$m->hash_dorso}";
		$legacyAbs = storage_path("app/matriculas/user/{$user->id}/{$m->tipo}/{$m->hash_dorso}");

		$ruta = $this->filePathPublicOrLegacy($publicRel, $legacyAbs);
		if (!$ruta) return back()->with('error', 'El archivo no existe en el servidor.');

		return response()->download($ruta, $m->archivo_dorso ?: $m->hash_dorso);
	}

	// ============================================================
	// Matrículas form helpers
	// ============================================================


	private function tiposMatriculaDisponibles(): array
{
    // Fuente única y estable
    return ['MN', 'MP'];
}


	private function buildMatriculasForm(?User $user = null): array
	{
		$tipos = $this->tiposMatriculaDisponibles();
		if (empty($tipos)) $tipos = ['MN', 'MP'];

		$labels = ['MN' => 'nacional', 'MP' => 'provincial'];

		$matByTipo = collect();
		if ($user) {
			$matByTipo = UserMatricula::where('id_user', $user->id)->get()->keyBy('tipo');
		}

		$out = [];
		foreach ($tipos as $t) {
			$m = $user ? $matByTipo->get($t) : null;

			$fecha = '';
			if ($m && !empty($m->fecha_vencimiento)) {
				try {
					$fecha = ($m->fecha_vencimiento instanceof \Carbon\Carbon)
						? $m->fecha_vencimiento->format('Y-m-d')
						: \Carbon\Carbon::parse($m->fecha_vencimiento)->format('Y-m-d');
				} catch (\Throwable $e) {
					$fecha = '';
				}
			}

			$out[] = [
				'tipo' => $t,
				'label' => $labels[$t] ?? $t,
				'nro' => (string) session()->getOldInput("matricula_nro.$t", $m ? (string) $m->nro : ''),
				'fecha_vencimiento' => (string) session()->getOldInput("matricula_vencimiento.$t", $fecha),
				'archivo_frente' => $m ? ($m->archivo_frente ?? null) : null,
				'archivo_dorso' => $m ? ($m->archivo_dorso ?? null) : null,
			];
		}

		return $out;
	}

	private function esEmpleadoMedico(Request $request): bool
	{
		return ((int) $request->input('rol') === 2) && ((int) $request->input('especialidad') === 1);
	}

	// ============================================================
	// Sync docs médicos (TODO a DISK public + limpieza duplicados)
	// ============================================================

	private function syncMedicoDocs(User $user, Request $request): void
	{
		$request->validate([
			'dni' => 'nullable|string|max:50',
			'sello_linea_1' => 'nullable|string|max:255',
			'sello_linea_2' => 'nullable|string|max:255',
			'sello_linea_3' => 'nullable|string|max:255',

			'archivo_dni' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
			'archivo_dni_detras' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
			'archivo_titulo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
			'archivo_titulo_detras' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
			'firma_medico' => 'nullable|file|mimes:png|max:10240',

			'matricula_nro' => 'nullable|array',
			'matricula_nro.*' => 'nullable|string|max:50',
			'matricula_vencimiento' => 'nullable|array',
			'matricula_vencimiento.*' => 'nullable|date_format:Y-m-d',

			'archivo_matricula_frente' => 'nullable|array',
			'archivo_matricula_frente.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
			'archivo_matricula_dorso' => 'nullable|array',
			'archivo_matricula_dorso.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
		]);

		// básicos
		$user->dni = $request->input('dni', $user->dni);
		$user->sello_linea_1 = $request->input('sello_linea_1') ?: null;
		$user->sello_linea_2 = $request->input('sello_linea_2') ?: null;
		$user->sello_linea_3 = $request->input('sello_linea_3') ?: null;
		$user->save();

		// DNI (public)
		if ($request->hasFile('archivo_dni') && $request->file('archivo_dni')->isValid()) {
			$this->savePublicUserFile($user, $request->file('archivo_dni'), 'dni', 'archivo_dni', 'hash_dni', [
				(string) $user->hash_dni_detras
			]);
		}
		if ($request->hasFile('archivo_dni_detras') && $request->file('archivo_dni_detras')->isValid()) {
			$this->savePublicUserFile($user, $request->file('archivo_dni_detras'), 'dni', 'archivo_dni_detras', 'hash_dni_detras', [
				(string) $user->hash_dni
			]);
		}

		// TÍTULO (public)
		if ($request->hasFile('archivo_titulo') && $request->file('archivo_titulo')->isValid()) {
			$this->savePublicUserFile($user, $request->file('archivo_titulo'), 'titulos', 'titulo', 'hash_titulo', [
				(string) $user->hash_titulo_detras
			]);
		}
		if ($request->hasFile('archivo_titulo_detras') && $request->file('archivo_titulo_detras')->isValid()) {
			$this->savePublicUserFile($user, $request->file('archivo_titulo_detras'), 'titulos', 'archivo_titulo_detras', 'hash_titulo_detras', [
				(string) $user->hash_titulo
			]);
		}

		// FIRMA (public)
		if ($request->hasFile('firma_medico') && $request->file('firma_medico')->isValid()) {
			$this->savePublicUserFile($user, $request->file('firma_medico'), 'firmas_medico', 'firma_medico', 'hash_firma_medico');
		}

		// Matrículas (public + tabla users_matriculas)
		$tipos = $this->tiposMatriculaDisponibles();
		if (empty($tipos)) $tipos = ['MN', 'MP'];

		$nros = $request->input('matricula_nro', []);
		$vencs = $request->input('matricula_vencimiento', []);
		if (!is_array($nros)) $nros = [];
		if (!is_array($vencs)) $vencs = [];

		foreach ($tipos as $tipo) {
			$tipo = strtoupper(trim((string) $tipo));

			$nro = isset($nros[$tipo]) ? trim((string) $nros[$tipo]) : '';
			$fechaVenc = isset($vencs[$tipo]) ? trim((string) $vencs[$tipo]) : '';
			if ($fechaVenc === '') $fechaVenc = null;

			$fileFrente = $request->file("archivo_matricula_frente.$tipo");
			$fileDorso  = $request->file("archivo_matricula_dorso.$tipo");

			$hayArchivos = ($fileFrente && $fileFrente->isValid()) || ($fileDorso && $fileDorso->isValid());
			$hayDatos = ($nro !== '' || $fechaVenc !== null);

			$mat = UserMatricula::firstOrNew([
				'id_user' => $user->id,
				'tipo' => $tipo,
			]);

			if (!$hayDatos && !$hayArchivos && !$mat->exists) continue;

			$mat->nro = ($nro !== '') ? $nro : null;
			$mat->fecha_vencimiento = $fechaVenc;

			$dir = "users/{$user->id}/matriculas/{$tipo}";
			$this->ensureDiskDir($dir);

			// Frente
			if ($fileFrente && $fileFrente->isValid()) {
				if (!empty($mat->hash_frente) && Storage::disk('public')->exists("{$dir}/{$mat->hash_frente}")) {
					Storage::disk('public')->delete("{$dir}/{$mat->hash_frente}");
				}

				$mat->archivo_frente = $fileFrente->getClientOriginalName();
				$mat->hash_frente = $fileFrente->hashName();
				Storage::disk('public')->putFileAs($dir, $fileFrente, $mat->hash_frente);
			}

			// Dorso
			if ($fileDorso && $fileDorso->isValid()) {
				if (!empty($mat->hash_dorso) && Storage::disk('public')->exists("{$dir}/{$mat->hash_dorso}")) {
					Storage::disk('public')->delete("{$dir}/{$mat->hash_dorso}");
				}

				$mat->archivo_dorso = $fileDorso->getClientOriginalName();
				$mat->hash_dorso = $fileDorso->hashName();
				Storage::disk('public')->putFileAs($dir, $fileDorso, $mat->hash_dorso);
			}

			$mat->save();

			// limpiar duplicados en la carpeta (dejar solo vigente)
			$keep = array_values(array_filter(array_unique([(string) $mat->hash_frente, (string) $mat->hash_dorso])));
			$files = Storage::disk('public')->files($dir);
			foreach ($files as $p) {
				$bn = basename($p);
				if (!in_array($bn, $keep, true)) {
					Storage::disk('public')->delete($p);
				}
			}
		}
	}
}