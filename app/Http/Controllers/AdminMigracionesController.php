<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\User;
use App\ClienteUser;
use App\Nomina;
use App\Fichada;
use App\FichadaNueva;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use App\MigrarSitioPrevio;

class AdminMigracionesController extends Controller
{

	public function migrar()
	{

		if (auth()->user()->email == 'sebas_admin@ejornal.com.ar') {

			$migraciones = MigrarSitioPrevio::all()->first();
			$sitio_previo_migrado = false;

			if (isset($migraciones) && !empty($migraciones)) {

				if ($migraciones->clientes == 0) {
					$clientes = false;
				}else {
					$clientes = true;
				}

				if ($migraciones->user_empleados == 0) {
					$users_empleados = false;
				}else {
					$users_empleados = true;
				}

				if ($migraciones->nominas == 0) {
					$nominas = false;
				}else {
					$nominas = true;
				}

			}else {
				$sitio_previo_migrado = false;
				$users_empleados = false;
				$nominas = false;
				$clientes = false;
			}

			if ($clientes == true && $users_empleados == true && $nominas == true) {
				$sitio_previo_migrado = true;
			}

			// Se agreggó por una migracion el campo Fichada. Este campo es para migrar los datos de la tabla fichada
			// a la nueva tabla de FichadasNuevas. Esto no es del sitio viejo. Se pensaron de una forma las fichadas y
			// luego se decidió cambiarla, por eso la migracion.
			if ($migraciones->fichada == 0) {
				$fichadas = false;
			}else {
				$fichadas = true;
			}

			return view('admin.migrar', compact('sitio_previo_migrado', 'clientes', 'users_empleados', 'nominas', 'fichadas'));

		}else {
			return redirect('admin/resumen');
		}
	}


	public function migrarClientes()
	{

		$archivo = file_get_contents(__DIR__.'/migrar_viejo_sitio/clientes.json');
		$array = json_decode($archivo);

		if (empty($array) || count($array) < 1) {
			return back()->with('error', 'El archivo no tiene información o está vacío');
		}else {
			foreach ($array as $value) {
				$cliente = new Cliente();
				$cliente->id = $value->id;
				$cliente->nombre = $value->nombre;
				$cliente->direccion = $value->direccion;
				$cliente->save();
			}

			$clientes_migrados = MigrarSitioPrevio::findOrFail(1);
			$clientes_migrados->clientes = 1;
			$clientes_migrados->save();

			return redirect()->action([AdminMigracionesController::class, 'migrar']);
		}



	}


	public function migrarUsersEmpleados()
	{
		$archivo = file_get_contents(__DIR__.'/migrar_viejo_sitio/users_empleados.json');
		$array = json_decode($archivo);


		if (empty($array) || count($array) < 1) {
			return back()->with('error', 'El archivo no tiene información o está vacío');
		}else {
			foreach ($array as $value) {
				$user = new User();
				$user->id = $value->id;
				$user->id_rol = 2;
				$user->nombre = $value->nombre . ' ' . $value->apellido;
				$user->estado = 1;
				if (isset($value->email) && !empty($value->email)) {
					$user->email = $value->email;
				}
				$user->password = bcrypt('123456');
				$user->save();
			}

			$clientes_migrados = MigrarSitioPrevio::findOrFail(1);
			$clientes_migrados->user_empleados = 1;
			$clientes_migrados->save();

			return redirect()->action([AdminMigracionesController::class, 'migrar']);
		}

	}


	public function migrarNominas()
	{
		$archivo = file_get_contents(__DIR__.'/migrar_viejo_sitio/nominas.json');
		$array = json_decode($archivo);

		if (empty($array) || count($array) < 1) {
			return back()->with('error', 'El archivo no tiene información o está vacío');
		}else {
			foreach ($array as $value) {
				$nomina = new Nomina();
				$nomina->id = $value->id;
				$nomina->id_cliente = $value->empresa_id;
				$nomina->nombre = $value->nombre . ' ' . $value->apellido;
				$nomina->email = $value->email;
				$nomina->telefono = $value->telefono;
				$nomina->dni = $value->documento;
				$nomina->estado = 1;
				$nomina->sector = $value->sector;
				$nomina->save();
			}

			$clientes_migrados = MigrarSitioPrevio::findOrFail(1);
			$clientes_migrados->nominas = 1;
			$clientes_migrados->save();

			return redirect()->action([AdminMigracionesController::class, 'migrar']);
		}


	}



	public function migrarFichadas()
	{
			$results =  Fichada::join('users', 'fichadas.id_user', 'users.id')
			->join('clientes', 'fichadas.id_cliente', 'clientes.id')
			->select('fichadas.*', DB::raw('clientes.nombre cliente'), DB::raw('users.nombre user'))
			->orderBy('fichadas.id_user', 'desc')
			->orderBy('fichadas.created_at', 'desc')
			->get();

			$fichadas = [];

			$modelo = 'App\Fichada';
			foreach ($results as $resultado) {
				$audits_fichadas = DB::table('audits')->where('auditable_type', $modelo)->get();
					if (!empty($audits_fichadas) && count($audits_fichadas) > 0) {
					foreach ($audits_fichadas as $audit) {
						if ($resultado->id == json_decode($audit->new_values)->id) {
							$resultado['ip'] = $audit->ip_address;
						}
					}
				}
			}

			foreach ($results as $key => $result) {

					$egreso_hallado = null;
					$ingreso_hallago = null;

					if ($result->horario_ingreso != null) {
						$ingreso_hallago = $result->created_at;
						if (isset($results[$key-1]->id_user) && $results[$key-1]->id_user == $result->id_user) {
							// Cargar el egreso
							$egreso_hallado = $results[$key-1]->created_at;
						}else {
							$egreso_hallado = null;
						}

						$fecha_ingreso = Carbon::createFromFormat('Y-m-d H:i:s', $ingreso_hallago)->format('d-m-Y H:i:s');

						if ($egreso_hallado != null) {
							$fecha_egreso = Carbon::createFromFormat('Y-m-d H:i:s', $egreso_hallado)->format('d-m-Y H:i:s');
							$f_ingreso = new DateTime($result->created_at);
							$f_egreso = new DateTime($egreso_hallado);
							$time = $f_ingreso->diff($f_egreso);
							$tiempo_dedicado = $time->days . ' dias ' . $time->format('%H horas %i minutos %s segundos');
						}

						$fichadas[] = [
							'id' => $result->id,
							'fecha_actual' => $result->fecha_actual,
							'created_at' => $result->created_at,
							'updated_at' => $result->updated_at,
							'cliente' => $result->cliente,
							'id_cliente' => $result->id_cliente,
							'id_user' => $result->id_user,
							'user' => $result->user,
							'tiempo_dedicado' => (isset($tiempo_dedicado) && !empty($tiempo_dedicado)) ? $tiempo_dedicado : 'Aún trabajando',
							'fecha_ingreso' => $fecha_ingreso,
							'fecha_egreso' => ($egreso_hallado != null) ? $fecha_egreso : null,
							'ip' => $result->ip
						];
					}
				}
				foreach ($fichadas as $fichada) {
					$fichadas_nuevas = new FichadaNueva;
					$fichadas_nuevas->ingreso = new DateTime($fichada['fecha_ingreso']);
					$fichadas_nuevas->egreso = ($fichada['fecha_egreso'] != null) ? new DateTime($fichada['fecha_egreso']) : null;
					$fichadas_nuevas->tiempo_dedicado = $fichada['tiempo_dedicado'];
					$fichadas_nuevas->id_user = $fichada['id_user'];
					$fichadas_nuevas->id_cliente = $fichada['id_cliente'];
					$fichadas_nuevas->ip = $fichada['ip'];
					$fichadas_nuevas->created_at = $fichada['created_at'];
					$fichadas_nuevas->updated_at = $fichada['updated_at'];
					$fichadas_nuevas->save();
				}

				$fichadas_migradas = MigrarSitioPrevio::findOrFail(1);
				$fichadas_migradas->fichada = 1;
				$fichadas_migradas->save();

				return redirect()->action([AdminMigracionesController::class, 'migrar']);

	}


	public function migrarUsersClientes()
	{
		$users = User::where('id_rol',3)->whereNotNull('id_cliente_relacionar')->get();

		$clientes_users = ClienteUser::all();

		foreach($users as $user)
		{
			$exists = false;
			foreach($clientes_users as $cliente_user)
			{
				if($cliente_user->id_user==$user->id && $cliente_user->id_cliente==$user->id_cliente_relacionar) $exists=true;
			}

			$cl_us = new ClienteUser;
			$cl_us->id_user = $user->id;
			$cl_us->id_cliente = $user->id_cliente_relacionar;
			$cl_us->save();

			$user->id_cliente_relacionar = null;
			$user->save();

			///var_dump($exists);
		}

		return redirect()->action([AdminMigracionesController::class, 'migrar']);


	}


}
