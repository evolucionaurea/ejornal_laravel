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

class AdminUserController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{

		/*$users = User::where('id',111)->get();
		$user = $users[0];
		$user->clientes =  $user->clientes_user()->get()->toArray();
		dd($user);*/


		$users = User::join('roles', 'users.id_rol', '=', 'roles.id')
		->leftJoin('especialidades', 'users.id_especialidad', 'especialidades.id')
		->select('users.*', DB::raw( 'roles.nombre rol'), DB::raw( 'especialidades.nombre especialidad'))
		->orderBy('nombre', 'asc')
		->get();

		$users_sin_empresas = [];
		foreach ($users as $user) {
			if ($user->id_rol == 2) {
				if ($user->id_cliente_actual == null) {
					$users_sin_empresas[] = $user;
				}
			}
		}

		$roles = Rol::orderBy('nombre', 'asc')->get();
		$clientes = Cliente::orderBy('nombre', 'asc')->get();
		$grupos = Grupo::orderBy('nombre', 'asc')->get();

		return view('admin.users', compact('users', 'users_sin_empresas', 'roles', 'clientes', 'grupos'));
	}
	public function busqueda(Request $request)
	{
		$query_users = User::select('users.*',DB::raw( 'roles.nombre rol'), DB::raw( 'especialidades.nombre especialidad'))
			->join('roles', 'users.id_rol', '=', 'roles.id')
			->leftJoin('especialidades', 'users.id_especialidad', 'especialidades.id')
			->orderBy('nombre', 'asc');

		if(isset($request->especialidad)) $query_users->where('users.id_especialidad',$request->especialidad);
		if(isset($request->rol)) $query_users->where('users.id_rol',$request->rol);
		if(isset($request->estado)) $query_users->where('users.estado',$request->estado);
		if(isset($request->fichada)) $query_users->where('users.fichada',$request->fichada);

		if(isset($request->rol)) $query_users->where('users.id_rol',$request->rol);
		if(isset($request->grupo)) $query_users->where('users.id_grupo',$request->grupo);

		$query_users
			->with('clientes_user')
			->with('grupo')
			->with('cliente_relacionar');

		if(isset($request->cliente)){
			$query_users
				->whereHas('clientes_user',function($query){
					global $request;
					return $query->where('id_cliente','=',$request->cliente);
				})
				->orWhere('id_cliente_relacionar',$request->cliente);
		}

		$users = $query_users->get();

		return [
			'results'=>$users,
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
		$roles = Rol::orderBy('nombre', 'asc')->get();
		$clientes = Cliente::orderBy('nombre', 'asc')->get();
		$especialidades = Especialidad::orderBy('nombre', 'asc')->get();
		$grupos = Grupo::orderBy('nombre', 'asc')->get();
		return view('admin.users.create', compact('roles', 'clientes', 'especialidades', 'grupos'));
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
			'estado' => 'required|numeric',
			'rol' => 'required|numeric',
			'password' => 'required|min:6'
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
		if(count($email_existente)>0){
			return back()->withInput($request->input())->with('error', 'Ya existe un usuario con el email ingresado.');
		}



		//Guardar en base
		$user = new User();
		$user->nombre = $request->nombre;
		$user->email = $request->email;
		$user->estado = $request->estado;
		$user->id_rol = $request->rol;
		$user->password = bcrypt($request->password);
		if (isset($request->personal_interno) && !empty($request->personal_interno) && $request->personal_interno == 'on' && $request->rol == 2) {
		$user->personal_interno = 1;
		}else {
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

			if($request->calle != null){
				$user->calle = $request->calle;
			}
			if($request->nro != null){
				$user->nro = $request->nro;
			}
			if($request->entre_calles != null){
				$user->entre_calles = $request->entre_calles;
			}
			if($request->localidad != null){
				$user->localidad = $request->localidad;
			}
			if($request->partido != null){
				$user->partido = $request->partido;
			}
			if($request->cod_postal != null){
				$user->cod_postal = $request->cod_postal;
			}
			if($request->observaciones != null){
				$user->observaciones = $request->observaciones;
			}

		}
		$user->cuil = $request->cuil;
		if (isset($request->contratacion) && $request->contratacion != 0) {
			$user->contratacion = $request->contratacion;
		}else {
			$user->contratacion = null;
		}
		if ($request->rol == 3) {
			$user->id_cliente_relacionar = $request->id_cliente_original;
		}
		if($request->rol==4){
			$user->id_grupo = $request->id_grupo;
		}

		$user->save();

		//Guardar en base relacion con cliente y usuario para saber donde trabajará
		if ($request->rol == 2 ) {
			$clientes_seleccionados = $request->clientes;
			foreach ($clientes_seleccionados as $key => $value) {
				$cliente_user = new ClienteUser();
				$cliente_user->id_cliente = $value;
				$cliente_user->id_user = $user->id;
				$cliente_user->save();
			}
		}



		return redirect('admin/users')->with('success', 'Usuario guardado con éxito');

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$user = User::where('users.id', $id)
		->join('roles', 'users.id_rol', 'roles.id')
		->join('grupos', 'grupos.id_grupo', 'grupos.id')
		->select('users.nombre', 'users.cuil', 'users.estado', 'users.email', 'users.dni', DB::raw('roles.nombre rol'),
		'users.id_rol', 'users.id_cliente_relacionar', 'grupos.nombre grupo')
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
		}

		return view('admin.users.show', compact('user', 'datos'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$user = User::findOrFail($id);
		// dd($user);
		$roles = Rol::orderBy('nombre', 'asc')->get();
		$clientes = Cliente::orderBy('nombre', 'asc')->get();
		$especialidades = Especialidad::orderBy('nombre', 'asc')->get();
		$buscar_clientes_asignados = ClienteUser::where('id_user', $id)->get();
		$clientes_seleccionados = [];
		foreach ($buscar_clientes_asignados as $asignados) {
			$clientes_seleccionados[] = $asignados->id_cliente;
		}

		return view('admin.users.edit', compact('user', 'roles', 'clientes', 'clientes_seleccionados', 'especialidades'));

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
			'estado' => 'required|numeric'
		]);

		if ($request->rol == 2) {
			if (!isset($request->clientes) || empty($request->clientes) || count($request->clientes) == 0 || is_null($request->clientes)) {
				return back()->withInput($request->input())->with('error', 'No puede crear a un usuario empleado sin definir en que empresa/s trabajará.');
			}
			$user = User::findOrFail($id);
			if ($user->fichada == 1 && $request->fichar == 0) {

				// Desficho al user que tiene una fichada iniciada y guardo la informacion
				$egreso = Carbon::now();
				$user->fichada = 0;
				$user->save();

				$agent = new Agent();
      			$device = $agent->platform();


				//Actualizar en base
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

		//Actualizar en base
		$user = User::findOrFail($id);
		$user->nombre = $request->nombre;
		$user->email = $request->email;
		$user->estado = $request->estado;
		if ($request->rol == 1) {
			$user->permiso_edicion_fichada = $request->permiso_edicion_fichada;
		}
		if (isset($request->personal_interno) && !empty($request->personal_interno) && $request->personal_interno == 'on' && $request->rol == 2) {
		$user->personal_interno = 1;
		}else {
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

			if($request->calle != null){
				$user->calle = $request->calle;
			}
			if($request->nro != null){
				$user->nro = $request->nro;
			}
			if($request->entre_calles != null){
				$user->entre_calles = $request->entre_calles;
			}
			if($request->localidad != null){
				$user->localidad = $request->localidad;
			}
			if($request->partido != null){
				$user->partido = $request->partido;
			}
			if($request->cod_postal != null){
				$user->cod_postal = $request->cod_postal;
			}
			if($request->observaciones != null){
				$user->observaciones = $request->observaciones;
			}

		}
		$user->cuil = $request->cuil;
		if (isset($request->contratacion) && $request->contratacion != 0) {
			$user->contratacion = $request->contratacion;
		}else {
			$user->contratacion = null;
		}

		$user->save();

		//Eliminar los clientes actuales y luego guardar en base relacion con cliente y usuario para saber donde trabaja
		if ($request->rol == 2){
			ClienteUser::where('id_user', $id)->delete();

			$clientes_seleccionados = $request->clientes;

			// Verificar si el id_cliente_actual del usuario está en la lista de clientes seleccionados
			if (!in_array($user->id_cliente_actual, $clientes_seleccionados)) {
				$user->id_cliente_actual = $clientes_seleccionados[0];
				$user->save();
			}

			foreach ($clientes_seleccionados as $key => $value) {
				$cliente_user = new ClienteUser();
				$cliente_user->id_cliente = $value;
				$cliente_user->id_user = $user->id;
				$cliente_user->save();
			}
		}

		return redirect('admin/users')->with('success', 'Usuario actualizado con éxito');

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$user = User::find($id)->delete();
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

		//Actualizar en base
		$user = User::findOrFail($request->id_user);
		$user->password = bcrypt($request->nueva_pass);
		$user->save();

		return back()->with('success', 'Contraseña cambiada correctamente');

	}



	public function downloadTitulo($id)
	{
		$user = User::findOrFail($id);
		$hash_titulo = $user->hash_titulo;
		$ruta = storage_path("app/titulos/user/{$id}/{$hash_titulo}");
		return response()->download($ruta);
		return back();
	}
	public function downloadDni($id)
	{
		$user = User::findOrFail($id);
		$hash_dni = $user->hash_dni;
		$ruta = storage_path("app/dni/user/{$id}/{$hash_dni}");
		return response()->download($ruta);
		return back();
	}
	public function downloadMatricula($id)
	{
		$user = User::findOrFail($id);
		$hash_matricula = $user->hash_matricula;
		$ruta = storage_path("app/matriculas/user/{$id}/{$hash_matricula}");
		return response()->download($ruta);
		return back();
	}
	public function downloadTituloDetras($id)
	{
		$user = User::findOrFail($id);
		$hash_titulo_detras = $user->hash_titulo_detras;
		$ruta = storage_path("app/titulos/user/{$id}/{$hash_titulo_detras}");
		return response()->download($ruta);
		return back();
	}
	public function downloadDniDetras($id)
	{
		$user = User::findOrFail($id);
		$hash_dni_detras = $user->hash_dni_detras;
		$ruta = storage_path("app/dni/user/{$id}/{$hash_dni_detras}");
		return response()->download($ruta);
		return back();
	}
	public function downloadMatriculaDetras($id)
	{
		$user = User::findOrFail($id);
		$hash_matricula_detras = $user->hash_matricula_detras;
		$ruta = storage_path("app/matriculas/user/{$id}/{$hash_matricula_detras}");
		return response()->download($ruta);
		return back();
	}



}
