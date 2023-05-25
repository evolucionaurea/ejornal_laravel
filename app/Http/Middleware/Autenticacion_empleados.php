<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Cliente;
use App\ClienteUser;

class Autenticacion_empleados
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $user_loggeado = auth()->user();

      $user = User::where('users.email', $user_loggeado->email)
      ->select('users.id_rol')
      ->first();

      $clientes_activos = 0;

      $clientes = ClienteUser::where('id_user', auth()->user()->id)->select('id_cliente')->get();
      foreach ($clientes as $cliente) {
        $buscar_cliente = Cliente::where('id', $cliente->id_cliente)->first();
        if ($buscar_cliente != null) {
          $clientes_activos ++;
        }
      }

      // El ID 2 es Empleado
      if ($user->id_rol == 2 && $clientes_activos > 0) {
        return $next($request);
      }else {
        return redirect('web_oficial')->with('error', 'Email o contraseña incorrectas');
      }

    }
}
