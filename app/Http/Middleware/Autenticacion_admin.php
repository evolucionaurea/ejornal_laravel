<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

class Autenticacion_admin
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


      // ID 1 es Admin
      if ($user->id_rol == 1) {
        return $next($request);
      }else {
        return redirect('web_oficial')->with('error', 'Email o contraseña incorrectas');
      }

    }
}
