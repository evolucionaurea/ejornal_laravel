<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{



  public function login(Request $request)
  {

      $validatedData = $request->validate([
        'email' => 'required',
        'password' => 'required',
      ]);

      $data = $request;

      if (Auth::attempt(['email' => $data->email, 'password' => $data->password])) {
        $user = User::where('users.email', $data->email)
        ->select('users.id_rol', 'users.estado', 'users.id')
        ->first();

        if ($user->estado == 1) {
          switch ($user->id_rol) {
            // Administrador
            case 1:
              return Redirect::route('/admin/resumen');
              break;

            // Empleado
            case 2:
              return Redirect::route('/empleados/resumen');
              break;

            // Clientes
            case 3:
              return Redirect::route('/clientes/resumen');
              break;

            case 4:
              return Redirect::route('/grupos/resumen');
              break;

            default:
            return redirect('/')->with('error', 'Hubo un problema con su usuario. Inténtelo de nuevo o pongase en contacto');
            break;
          }
        }else {
          return redirect('/')->with('error', 'Usuario sin permisos de acceso');
        }


      }else {
        return redirect('/')->with('error', 'Email o contraseña incorrectas');
      }
  }



  public function logout()
  {
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
}
