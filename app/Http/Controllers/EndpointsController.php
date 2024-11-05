<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Nomina;
use App\Ausentismo;
use App\Cliente;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\EdicionFichada;
use App\FichadaNueva;
use Jenssegers\Agent\Agent;
use DateTime;

class EndpointsController extends Controller
{

		public function ausentismosMesActual($token)
		{

			$cliente = Cliente::where('token', '=', $token)->first();
			$inicio_mes_actual = new Carbon('first day of this month');
			$inicio_mes_actual->startOfMonth();
			$final_mes_actual = new Carbon('last day of this month');
			$final_mes_actual->endOfMonth();

			$ausentismos_mes_actual = Ausentismo::join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
			->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
			->where('nominas.id_cliente', $cliente->id)
			->whereDate('ausentismos.fecha_inicio', '>=', $inicio_mes_actual)
			->whereDate('ausentismos.fecha_inicio', '<=', $final_mes_actual)
			->select('ausentismos.fecha_inicio', 'ausentismos.fecha_final', 'ausentismos.fecha_regreso_trabajar',
			'nominas.nombre', 'nominas.email', 'nominas.telefono', 'nominas.dni',
			DB::raw('ausentismo_tipo.nombre tipo_ausentismo'), DB::raw('ausentismos.user profesional_cargo_info'))
			->get();

			return $ausentismos_mes_actual;
		}

		public function ausentismosHoy($token)
		{
			$cliente = Cliente::where('token', '=', $token)->first();
			$hoy = Carbon::now();

			$ausentismos_hoy = Ausentismo::join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
			->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
			->where('nominas.id_cliente', $cliente->id)
			->whereDate('ausentismos.fecha_inicio', '<=', $hoy)
			->whereDate('ausentismos.fecha_final', '>=', $hoy)
			->where('ausentismos.fecha_regreso_trabajar', null)
			->select('ausentismos.fecha_inicio', 'ausentismos.fecha_final', 'ausentismos.fecha_regreso_trabajar',
			'nominas.nombre', 'nominas.email', 'nominas.telefono', 'nominas.dni',
			DB::raw('ausentismo_tipo.nombre tipo_ausentismo'), DB::raw('ausentismos.user profesional_cargo_info'))
			->get();

			return $ausentismos_hoy;
		}

		public function getNominas($token)
		{
			$cliente = Cliente::where('token', '=', $token)->first();
			$nominas = Nomina::where('nominas.id_cliente', $cliente->id)
			->select('nominas.id', 'nominas.nombre', 'nominas.email', 'nominas.telefono', 'nominas.dni', 'nominas.sector')
			->get();

			return $nominas;
		}

		public function setNominas(Request $request)
		{


			// Validaciones //
			if (empty($request->nombre) || empty($request->email) || empty($request->telefono) ||
					empty($request->dni) || empty($request->estado) || empty($request->sector) ||
					!isset($request->nombre) || !isset($request->email) || !isset($request->telefono) ||
					!isset($request->dni) || !isset($request->estado) || !isset($request->sector)
					|| !isset($request->token) || empty($request->token))
					{
						return response()->json([
							'mensaje' => 'Campos invalidos'
						]);
					}

			if (!is_string($request->nombre) || !is_string($request->email) || !is_string($request->telefono) ||
					!is_string($request->dni) || !is_numeric($request->estado) || !is_string($request->sector))
					{
						return response()->json([
							'mensaje' => 'El formato de algunos campos es invalido'
						]);
					}
			// Validaciones //



			if ($request->estado == 0 || $request->estado == 1) {

				$cliente_existe = Cliente::where('token', $request->token)->first();

				if ($cliente_existe == null) {
					return response()->json([
						'mensaje' => 'No existe el cliente'
					]);
				} else {
					$cliente = new Nomina();
					$cliente->id_cliente = $cliente_existe->id;
					$cliente->nombre = $request->nombre;
					$cliente->email = $request->email;
					$cliente->telefono = $request->telefono;
					$cliente->dni = $request->dni;
					$cliente->estado = $request->estado;
					$cliente->sector = $request->sector;
					$cliente->save();

					return response()->json([
						'mensaje' => 'Guardado correctamente'
					]);
				}

			} else {
				return response()->json([
					'mensaje' => 'Estado inválido'
				]);
			}


		}



		public function deleteNominas(Request $request)
		{

			// return response()->json([
			//   'mensaje' => 'Recurso aún no activo'
			// ]);


			if (!isset($request->token) || empty($request->token) || !isset($request->id_nomina)
					|| empty($request->id_nomina) || is_string($request->id_nomina))
			{
				return response()->json([
					'mensaje' => 'Formato invalido'
				]);
			}



			if (is_numeric($request->id_nomina)) {

				$cliente_existe = Cliente::where('token', $request->token)->first();
				$nomina_existe = Nomina::find($request->id_nomina);

				if ($cliente_existe == null || $nomina_existe == null) {
					return response()->json([
						'mensaje' => 'No existe el cliente o el profesional de la nomina'
					]);
				} else {
					$nomina = Nomina::where('id_cliente', $cliente_existe->id)
					->where('id', $request->id_nomina)->first();

					if (!empty($nomina)) {
						$nomina->delete();
					} else {
						return response()->json([
							'mensaje' => 'Datos invalidos o mal intencionados'
						]);
					}

					return response()->json([
						'mensaje' => 'Eliminado correctamente'
					]);
				}

			} else {
				return response()->json([
					'mensaje' => 'Id nomina mal formateado'
				]);
			}



		}


		public function cambiarFichada(Request $request)
		{

			///dd($request->json());

			// Validar la entrada
			$request->validate([
				'id' => 'required|integer|exists:fichadas_nuevas,id',
				'newDate' => 'required|string', // Cambiado a 'string' para manipulación
				//'oldDate' => 'required|string',
				'action' => 'required|string',
				'id_loggeado' => 'required|integer|exists:users,id' // Asegúrate de validar también el id del usuario
			]);

			// Obtener datos del usuario autenticado
			$userId = $request->id_loggeado;
			$fichadaId = $request->id;
			$oldValue = $request->oldDate;


			$oldDateObj = new DateTime($oldValue);
			// Convertir la nueva fecha desde el formato DD/MM/YYYY
			$dateParts = explode('/', $request->newDate);
			if (count($dateParts) === 3) {
				// Crear la fecha en formato 'YYYY-MM-DD'
				$formattedDate = "{$dateParts[2]}-{$dateParts[1]}-{$dateParts[0]} {$oldDateObj->format('H:i:s')}"; // '2024-10-25'
				//dd($oldValue);
				$newDate = new DateTime($formattedDate); // Crear el objeto DateTime
			} else {
				return response()->json(['success' => false, 'message' => 'Formato de fecha inválido.'], 400);
			}

			// Buscar la fichada existente
			$fichada = FichadaNueva::find($fichadaId);
			if (!$fichada) {
				return response()->json(['success' => false, 'message' => 'Fichada no encontrada.'], 404);
			}

			// Obtener el id_user de la fichada
			$fichadaUserId = $fichada->id_user;

			// Verificar si es la última fichada del usuario
			$ultimoRegistro = FichadaNueva::where('id_user', $fichadaUserId)
					->orderBy('id', 'desc')
					->first();
			///dd($ultimoRegistro);

			if (!$ultimoRegistro || $ultimoRegistro->id != $fichadaId) {
					return response()->json([
						'success' => false,
						'message' => 'No es posible editar el registro porque no es la última fichada del usuario.'
					], 400);
			}

			// Validar según el tipo de edición
			if ($request->action=='ingreso') {
					// Validar que el egreso no tenga una fecha inferior a la nueva fecha de ingreso
					if ($ultimoRegistro->egreso && new DateTime($ultimoRegistro->egreso) < $newDate) {
							return response()->json(['success' => false, 'message' => 'El egreso registrado es anterior a la nueva fecha de ingreso.'], 400);
					}

					$ultimoRegistro->ingreso = $newDate;
					$ultimoRegistro->save();
					$edicionFichada = new EdicionFichada();
					$edicionFichada->old_ingreso = new DateTime($oldValue);
					$edicionFichada->new_ingreso = $newDate;
					$edicionFichada->old_egreso = null; // Asegúrate de establecer egreso como null
					$edicionFichada->new_egreso = null; // Asegúrate de establecer egreso como null
			}
			if ($request->action=='egreso') {
					// Validar que el ingreso no tenga una fecha mayor a la nueva fecha de egreso
					if ($ultimoRegistro->ingreso && new DateTime($ultimoRegistro->ingreso) > $newDate) {
							return response()->json(['success' => false, 'message' => 'El ingreso registrado es posterior a la nueva fecha de egreso.'], 400);
					}
					//$oldDateObj = new DateTime($oldValue);
					$ultimoRegistro->egreso = $newDate; //mantengo la hora original
					$ultimoRegistro->save();
					$edicionFichada = new EdicionFichada();
					$edicionFichada->old_egreso = new DateTime($oldValue);
					$edicionFichada->new_egreso = $newDate;
					$edicionFichada->old_ingreso = null; // Asegúrate de establecer ingreso como null
					$edicionFichada->new_ingreso = null; // Asegúrate de establecer ingreso como null
			}

			// Asignar id_user y id_fichada
			$edicionFichada->id_user = $userId;
			$edicionFichada->id_fichada = $fichadaId;

			// Obtener la IP y el dispositivo
			$agent = new Agent();
			$edicionFichada->ip = $request->ip();
			$edicionFichada->dispositivo = $agent->device() . ' (' . $agent->platform() . ' ' . $agent->version($agent->platform()) . ')';

			// Guardar el registro
			$edicionFichada->save();

			return response()->json(['success' => true]);
		}



}
