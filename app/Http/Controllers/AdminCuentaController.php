<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rol;
use App\User;

class AdminCuentaController extends Controller
{

  public function index()
  {
    $roles = Rol::all();
    return view('admin.cuenta', compact('roles'));
  }

  public function store(Request $request)
  {

    if ($request->nombre == '' || $request->nombre == null || empty($request->nombre) || $request->estado == '' || $request->estado == null || empty($request->estado)) {
      return back()->with('error', 'No puedes guardar campos vacíos');
    }
    $user = User::findOrFail($request->id_user);
    $user->nombre = $request->nombre;
    $user->estado = $request->estado;
    $user->save();

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



}
