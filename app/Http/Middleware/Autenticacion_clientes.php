<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

class Autenticacion_clientes
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

      // El ID 3 es Clientes
      if ($user->id_rol == 3) {
        return $next($request);
      }else {
        return redirect('web_oficial')->with('error', 'Email o contraseña incorrectas');
      }

    }
}
