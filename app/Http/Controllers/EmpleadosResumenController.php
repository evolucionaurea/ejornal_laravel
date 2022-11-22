<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
//use App\ClienteUser;
use App\Http\Traits\Clientes;
use App\Nomina;
use App\Ausentismo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\CovidTesteo;
use App\ConsultaMedica;
use App\ConsultaEnfermeria;
use App\CovidVacuna;
// use Jenssegers\Agent\Agent;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use GuzzleHttp\Client;
use Jenssegers\Agent\Agent;


class EmpleadosResumenController extends Controller
{

	use Clientes;

	public function index()
	{

		/////////////////////
		// Nombre de la Aplicacion: ejornal

		// $guzzle = new \GuzzleHttp\Client();
		// $url = 'https://login.microsoftonline.com/' . $tenantId . '/oauth2/token?api-version=1.0';
		// $token = json_decode($guzzle->post($url, [
		//     'form_params' => [
		//         'client_id' => 'b9ceb9f0-2531-4858-9c08-c36a6220ac40',
		//         'client_secret' => 'i_N89-54gdP62.fr0UauUS-xMiOQ8-VNPf',
		//         'resource' => 'https://graph.microsoft.com/',
		//         'grant_type' => 'client_credentials',
		//     ],
		// ])->getBody()->getContents());
		// $accessToken = $token->access_token;


		//////////////////

		$fecha_actual = Carbon::now();

		$clientes = $this->getClientesUser();

		$trabajadores = Nomina::where('id_cliente', auth()->user()->id_cliente_actual)
		->where('nominas.estado', 1)
		->get();

		$total_nomina = count($trabajadores);

		$ausentes_hoy_q = Ausentismo::join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
		->join('ausentismo_tipo','ausentismo_tipo.id','ausentismos.id_tipo')
		->where('nominas.id_cliente', auth()->user()->id_cliente_actual)
		->where('ausentismos.fecha_regreso_trabajar', null)
		->whereDate('ausentismos.fecha_inicio', '<=', $fecha_actual)
		//->whereDate('ausentismos.fecha_final', '>=', $fecha_actual)
		->select(
			'ausentismos.*',
			'nominas.nombre',
			'nominas.email',
			'nominas.telefono',
			'nominas.dni',
			'nominas.estado',
			'ausentismo_tipo.nombre as ausentismo_tipo'
		)
		->get();
		$ausencia_covid = 0;
		$ausentes_hoy = count($ausentes_hoy_q);

		foreach($ausentes_hoy_q as $ausente){
			if(preg_match('/covid/', $ausente->ausentismo_tipo)) $ausencia_covid++;
		}



		$consultas_medicas = ConsultaMedica::join('nominas', 'consultas_medicas.id_nomina', 'nominas.id')
		->where('nominas.id_cliente', auth()->user()->id_cliente_actual)
		->whereDate('consultas_medicas.fecha', '=', Carbon::now()->format('Y-m-d'))
		->count();

		$consultas_enfermeria = ConsultaEnfermeria::join('nominas', 'consultas_enfermerias.id_nomina', 'nominas.id')
		->where('nominas.id_cliente', auth()->user()->id_cliente_actual)
		->whereDate('consultas_enfermerias.fecha', '=', Carbon::now()->format('Y-m-d'))
		->count();

		/*$vacunados_varias_dosis = CovidVacuna::join('nominas', 'covid_vacunas.id_nomina', 'nominas.id')
		->where('nominas.id_cliente', auth()->user()->id_cliente_actual)
		->selectRaw('covid_vacunas.id_nomina, count(*)')
		->groupBy('covid_vacunas.id_nomina')
		->select('nominas.nombre', DB::raw('count(*) cantidad'))
		->get();*/

		/*$cant_vacunados_una_dosis = 0;
		$cant_vacunados_dos_dosis = 0;
		$cant_vacunados_tres_dosis = 0;
		if (count($vacunados_varias_dosis) > 0) {
			foreach ($vacunados_varias_dosis as $dosis) {
				if ($dosis->cantidad >= 1) {
					$cant_vacunados_una_dosis++;
				}
				if ($dosis->cantidad >= 2) {
					$cant_vacunados_dos_dosis++;
				}
				if ($dosis->cantidad >= 3) {
					$cant_vacunados_tres_dosis++;
				}
			}
		}*/

		/*$testeos_positivos = CovidTesteo::where('resultado', 'positivo')
		->join('nominas', 'covid_testeos.id_nomina', 'nominas.id')
		->where('nominas.id_cliente', auth()->user()->id_cliente_actual)
		->count();*/

		$medicas_mes = ConsultaMedica::join('nominas', 'consultas_medicas.id_nomina', 'nominas.id')
		->where('nominas.id_cliente', auth()->user()->id_cliente_actual)
		->whereMonth('consultas_medicas.fecha', '=', $fecha_actual->month)
		->count();

		$enfermerias_mes = ConsultaEnfermeria::join('nominas', 'consultas_enfermerias.id_nomina', 'nominas.id')
		->where('nominas.id_cliente', auth()->user()->id_cliente_actual)
		->whereMonth('consultas_enfermerias.fecha', '=', $fecha_actual->month)
		->count();

		/*$ausencia_covid = Ausentismo::join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
		->where('nominas.id_cliente', auth()->user()->id_cliente_actual)
		->where('ausentismos.fecha_regreso_trabajar', '!=', null)
		->where('ausentismos.id_tipo', 8)
		->orWhere('ausentismos.id_tipo', 9)
		->whereDate('ausentismos.fecha_inicio', '>=', $fecha_actual)
		->whereDate('ausentismos.fecha_final', '<=', $fecha_actual)
		->whereDate('ausentismos.fecha_regreso_trabajar', '<=', $fecha_actual)
		->select('ausentismos.*', 'nominas.nombre', 'nominas.email', 'nominas.telefono', 'nominas.dni', 'nominas.estado')
		->count();*/

		return view('empleados.resumen', compact('clientes', 'total_nomina', 'ausentes_hoy', 'consultas_medicas',
		'medicas_mes', 'enfermerias_mes', 'consultas_enfermeria','ausencia_covid'));
	}



}
