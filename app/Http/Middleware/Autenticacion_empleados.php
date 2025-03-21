<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Cliente;
use App\ClienteUser;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;
use DateTime;
use App\FichadaNueva;
use Illuminate\Http\Request;

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

		// Obtener información del usuario logueado
		/*$user = User::where('users.email', $user_loggeado->email)
			->select('users.id_rol', 'users.id_cliente_actual', 'fichada')
			->first();*/

		// Verificar los clientes activos del usuario
		///$clientes_activos = 0;
		$clientes = ClienteUser::where('id_user', $user_loggeado->id)
			->with('cliente')
			->select('id_cliente')
			->get();

		if(!$clientes) return redirect('/')->with('error', 'No tienes clientes asignados en tu cuenta. Comunícate con los administradores del sitio para que te asignen algún cliente.');

		//$clientes_activos_ids = [];
		/*foreach ($clientes as $cliente) {
			if(!$cliente->cliente) continue;
			$clientes_activos++;
			$clientes_activos_ids[] = $cliente->id_cliente;  // Guardar los IDs de clientes activos
		}*/

		// Solo aplica a los usuarios con rol de empleado (id_rol = 2)
		if ($user_loggeado->id_rol != 2) {
			return redirect('web_oficial')->with('error', 'Tu rol asignado no tiene permisos para acceder a esta sección.');
		}

		// Si no tiene un cliente seleccionado busco el primero de la lista de los asignados
		if(!$user_loggeado->id_cliente_actual){
			$user = User::findOrFail($user_loggeado->id);
			$user->id_cliente_actual = $clientes[0]->id_cliente;
			$user->save();
			//dd($clientes[0]->id_cliente);
		}

		// Validar que el usuario está trabajando para un cliente activo
		//if (!in_array($user_loggeado->id_cliente_actual, $clientes_activos_ids)) {
			// Redirigir después de desfichar
			//return redirect('/')->with('error', 'El cliente actual ya no está asignado a tu cuenta. Has sido deslogueado y debes iniciar sesión nuevamente.');
		///}

		// Verificar si el usuario tiene una fichada activa
		if ($user_loggeado->fichada == 1) {
			// Obtener la última fichada del usuario
			$verificar_fichada_nueva = FichadaNueva::where('id_user', $user_loggeado->id)->latest()->first();

			if ($verificar_fichada_nueva && $verificar_fichada_nueva->egreso === null) {
				// Verificar si han pasado más de 12 horas desde el ingreso
				$ingreso = Carbon::parse($verificar_fichada_nueva->ingreso);
				$ahora = Carbon::now();

				if ($ingreso->diffInHours($ahora) > 12) {
					// Desfichar al usuario si han pasado más de 12 horas
					$this->desficharUsuario($user_loggeado);
				}
			}
		}


		// Si el cliente actual es válido, continuar con la solicitud
		return $next($request);
	}

	/**
	 * Desficha al usuario, guardando la información de la fichada.
	 */
	private function desficharUsuario($user_loggeado)
	{
		$usuario = User::find($user_loggeado->id);
		$usuario->fichada = 0;  // Marcar que el usuario ya no está fichado
		$usuario->save();

		$egreso = Carbon::now();

		// Obtener la última fichada del usuario
		$fichada = FichadaNueva::where('id_user', $user_loggeado->id)->latest()->first();

		if ($fichada) {
			$f_ingreso = new DateTime($fichada->ingreso);
			$f_egreso = new DateTime();
			$time = $f_ingreso->diff($f_egreso);

			// Formatear el tiempo dedicado
			$tiempo_dedicado = $time->days . ' días ' . $time->format('%H horas %i minutos %s segundos');

			// Registrar información adicional de la ficha (IP, sistema operativo, etc.)
			$agent = new Agent();
			$fichada->egreso = $egreso;
			$fichada->tiempo_dedicado = $tiempo_dedicado;
			$fichada->ip = \Request::ip();
			$fichada->sistema_operativo = $agent->platform();
			$fichada->browser = $agent->browser();
			$fichada->dispositivo = device_spanish($agent->deviceType());

			$fichada->save();
		}
	}
}
