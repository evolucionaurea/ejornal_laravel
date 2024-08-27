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

        $user = User::where('users.email', $user_loggeado->email)
            ->select('users.id_rol', 'users.id_cliente_actual', 'fichada')
            ->first();

        $clientes_activos = 0;

        $clientes = ClienteUser::where('id_user', auth()->user()->id)
            ->select('id_cliente')
            ->get();

        $clientes_activos_ids = [];  // Array para almacenar los IDs de clientes activos

        foreach ($clientes as $cliente) {
            $buscar_cliente = Cliente::where('id', $cliente->id_cliente)->first();
            if ($buscar_cliente != null) {
                $clientes_activos++;
                $clientes_activos_ids[] = $cliente->id_cliente;  // Agregar el ID del cliente activo al array
            }
        }

        // El ID 2 es Empleado
        if ($user->id_rol == 2 && $clientes_activos > 0) {
          
            // Validar que el user tenga como id_cliente_actual un cliente que esté asociado a él
            if (!in_array($user->id_cliente_actual, $clientes_activos_ids)) {
                // Desficho al user que tiene una fichada iniciada y guardo la informacion
                if ($user_loggeado->fichada == 1) {

                    $usuario = User::find(auth()->user()->id);
                    $usuario->fichada = 0;
                    $usuario->save();
                    
                    $egreso = Carbon::now();

                    $agent = new Agent();
                    $device = $agent->platform();
                    $fichada = FichadaNueva::where('id_user', $user_loggeado->id)->latest()->first();
                    
                    // Verificar si se encontró una fichada
                    if ($fichada) {
                        $fichada->egreso = $egreso;
                        $f_ingreso = new DateTime($fichada->ingreso);
                        $f_egreso = new DateTime();
                        $time = $f_ingreso->diff($f_egreso);
                        $tiempo_dedicado = $time->days . ' días ' . $time->format('%H horas %i minutos %s segundos');
                        $fichada->ip = \Request::ip();
                        $fichada->dispositivo = $device;
                        $fichada->tiempo_dedicado = $tiempo_dedicado;
                        $fichada->save();
                    }
                }

                return redirect('/')
                    ->with('error', 'Un administrador te ha quitado el cliente con el que estabas trabajando. 
                    Vuelve a iniciar sesión.');
            }

            return $next($request);

        } else {
            return redirect('web_oficial')
                ->with('error', 'Email o contraseña incorrectas');
        }
    }
}
