<?php

namespace App\Http\Controllers;

use App\Configuracion;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Sesion;

class UserController extends Controller
{

	public function login(Request $request)
	{
			$validatedData = $request->validate([
					'email' => 'required|email',
					'password' => 'required',
			]);

			$credentials = $request->only('email', 'password');


			if (!Auth::attempt($credentials))  return back()->withErrors(['mensaje' => 'Email o contraseña incorrectas']);


			$user = Auth::user();

			$mantenimiento = $this->validarMantenimiento();
			if ($user->id_rol != 1 && !$mantenimiento) {
				Auth::logout();
				return view('mantenimiento');
			}
			// Verificar si el usuario ya tiene una sesión activa
			$existingSession = Sesion::where('id_user', $user->id)->first();

			if ($existingSession) {
				// Cerrar sesión en otros dispositivos
				Auth::logoutOtherDevices($request->password);
				// Si ya existe una sesión, actualizarla
				$existingSession->update(['loggeado' => 1]);
			} else {
				// Si no existe una sesión, crear una nueva
				Sesion::create([
					'id_user' => $user->id,
					'loggeado' => 1,
				]);
			}

			// Redireccionar según el rol del usuario
			if ($user->estado != 1) return redirect('/')->with('error', 'Usuario sin permisos de acceso');


			//dd($user->id_rol);
			switch ($user->id_rol) {
				// Administrador
				case 1:
					return redirect('/admin/resumen');
					break;

				// Empleado
				case 2:
					return redirect('/empleados/resumen');
					break;

				// Clientes
				case 3:
					return redirect('/clientes/resumen');
					break;

				case 4:
					return redirect('/grupos/resumen');
					break;

				default:
					return redirect('/')->with('error', 'Hubo un problema con su usuario. Inténtelo de nuevo o póngase en contacto');
					break;
			}

	}




	public function logout()
	{
		$user = Auth::user();

		// Marcar al usuario como deslogueado
		$sesion = Sesion::where('id_user', $user->id)->first();
		if ($sesion) {
				$sesion->loggeado = 0;
				$sesion->save();
		}

		Auth::logout();
		return redirect('/');
	}


		public function index()
		{
			$users = User::all();
			return view('admin/users', compact('users'));
		}


		public function create()
		{
			return view('admin.users.create');
		}


		public function store(Request $request)
		{

			$validatedData = $request->validate([
				'nombre' => 'required|string',
				'email' => 'required|email',
				'password' => 'required|min:6',
				'cpassword' => 'required'
			]);

			if ($request->password !== $request->cpassword) {
				return redirect('admin/users/create')->with('error', 'No coinciden las contraseñas');
			}else {

				//Guardar en base
				$user = new User();
				$user->nombre = $request->nombre;
				$user->email = $request->email;
				$user->password = bcrypt($request->password);
				$user->save();

				return redirect('admin/users')->with('message', 'Usuario guardado con éxito');
			}


		}


		public function show($id)
		{
			//
		}


		public function edit($id)
		{

			$user = User::findOrFail($id);
			return view('admin.users.edit', compact('user'));

		}


		public function update(Request $request, $id)
		{

			//Validar formulario
			if ($request->password !== $request->cpassword) {
				return Redirect::back()->with('error', 'No coinciden las contraseñas');
			}else {
				//Actualizar en base
				$user = User::findOrFail($id);
				$user->name = $request->name;
				$user->email = $request->email;
				if ($request->password !== null && !$request->cpassword !== null) {
					$user->password = bcrypt($request->password);
				}
				$user->save();

				return redirect('admin/users')->with('success', 'Usuario guardado con éxito');
			}


		}


		public function destroy($id)
		{

			$user = User::findOrFail($id);
			$user->delete();
			return back()->with('success', 'Usuario eliminado con éxito');

		}


		private function validarMantenimiento()
		{
			$data = Configuracion::first();
			if (gettype($data) == 'object' && $data->online == 1) {
				return true;
			}else {
				return false;
			}
		}
}
