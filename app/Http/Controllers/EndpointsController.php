<?php

namespace App\Http\Controllers;

use App\Agenda;
use App\AgendaMotivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Nomina;
use App\Ausentismo;
use App\Caratula;
use App\Cliente;
use App\EdicionFichada;
use App\FichadaNueva;
use App\Patologia;
use Carbon\Carbon;
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


		public function getCaratulaNomina($id_nomina)
		{
			$caratula = Caratula::with(['patologias', 'nomina', 'cliente'])  // Cargar patologías desde la tabla intermedia
				->where('id_nomina', $id_nomina)
				->latest()
				->first();			
			if(!$caratula) return '<div class="alert alert-info">No se ha creado una carátula para este trabajador de la nómina aún.</div>';

			return view('modulos.caratula_trabajador', compact('caratula'));
		
			// Formatear la respuesta antes de devolverla
			/* $caratulaData = [
				'estado' => !empty($caratula) ? true : false,
				'data' => $caratula ? $caratula->toArray() + [  // Incluye todos los campos de la carátula
					'patologias' => $caratula->patologias->map(function($patologia) {
						return [
							'id' => $patologia->id,
							'nombre' => $patologia->nombre
						];
					}),
					'nomina' => $caratula->nomina ? [
						'id' => $caratula->nomina->id,
						'nombre' => $caratula->nomina->nombre
					] : null,
					'cliente' => $caratula->cliente ? [
						'id' => $caratula->cliente->id,
						'nombre' => $caratula->cliente->nombre
					] : null
				] : null
			];
		
			return response()->json($caratulaData); */
		}
		public function getCaratulaModal($id_nomina)
		{
			$caratula = Caratula::with(['patologias', 'nomina', 'cliente'])  // Cargar patologías desde la tabla intermedia
				->where('id_nomina', $id_nomina)
				->latest()
				->first();	
			$patologias = Patologia::all();

			return view('modulos.modal_caratula_trabajador', compact('caratula','patologias'));
		}
		

    
		/***************
		TODO: mover este método al controlador de Caratulas
		ya que se omite el middleware y no se puedo obtener los datos del usuario logueado
		*****************/
		public function actualizarCaratula(Request $request)
		{
			try {
				// Claves de la carátula
				$idNomina  = $request->input('trabajador_id_edit_caratula');
				$idCliente = $request->input('cliente_id_edit_caratula');

				// Buscar existente o crear uno nuevo (sin mass-assignment)
				$caratula = Caratula::firstOrNew([
					'id_nomina'  => $idNomina,
					'id_cliente' => $idCliente,
				]);

				// Normalizar números (coma a punto)
				$pesoRaw   = (string) $request->input('peso_edit_caratula', '');
				$alturaRaw = (string) $request->input('altura_edit_caratula', '');
				$peso      = $pesoRaw   !== '' ? (float) str_replace(',', '.', $pesoRaw)   : null;
				$altura    = $alturaRaw !== '' ? (float) str_replace(',', '.', $alturaRaw) : null;

				// Set de campos
				$caratula->medicacion_habitual = $request->input('medicacion_habitual_edit_caratula');
				$caratula->antecedentes        = $request->input('antecedentes_edit_caratula');
				$caratula->alergias            = $request->input('alergias_edit_caratula');
				$caratula->peso                = $peso;
				$caratula->altura              = $altura;

				// IMC: usar el enviado o calcular si hay datos
				$imc = $request->input('imc_edit_caratula');
				if ($imc === null && $peso && $altura) {
					$imc = round($peso / pow($altura / 100, 2), 2);
				}
				$caratula->imc = $imc;

				// (opcional) auditoría
				if (auth()->check()) {
					$caratula->user = auth()->user()->nombre ?? auth()->user()->name ?? null;
				}

				$caratula->save();

				// ---- Patologías: aceptar ambos nombres y sincronizar SIEMPRE ----
				// Puede venir como 'patologia_edit_caratula[]' (array) o 'patologia_id_edit_caratula' (string/array)
				$patologias = $request->input('patologia_edit_caratula[]');
				if ($patologias === null) {
					$patologias = $request->input('patologia_edit_caratula'); // por si en algún lugar no usan []
				}
				if ($patologias === null) {
					$patologias = $request->input('patologia_id_edit_caratula');
				}
				if ($patologias === null) {
					$patologias = [];
				}
				if (!is_array($patologias)) {
					$patologias = [$patologias];
				}
				// Limpiar/convertir a enteros y quitar vacíos
				$patologias = collect($patologias)
					->map(function ($v) { return (int) $v; })
					->filter() // quita 0/null
					->values()
					->all();

				// sync SIEMPRE (incluso vacío para remover relaciones)
				$caratula->patologias()->sync($patologias);

				return response()->json([
					'estado'  => true,
					'message' => 'Carátula actualizada correctamente',
				]);
			} catch (\Throwable $th) {
				return response()->json([
					'estado'  => false,
					'message' => $th->getMessage(),
				], 500);
			}
		}


		public function getPatologias()
		{
			try {
				$patologias = Patologia::all();

				return response()->json([
					'estado' => true,
					'data' => $patologias
				]);
			} catch (\Throwable $th) {
				return response()->json([
					'estado' => false,
					'data' => $th->getMessage()
				]);
			}
		}
		
    


}
