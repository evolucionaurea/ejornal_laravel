<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ClienteUser;
use App\Nomina;
use App\Cliente;
use App\Ausentismo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use DateTime;
use App\AusentismoTipo;
use App\CovidVacuna;

class ClientesResumenController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{

		$cliente = Cliente::where('id', auth()->user()->id_cliente_relacionar)
			->select('clientes.nombre')
			->first();

		// if ($cliente->id_grupo === null) {
		// 	$grupo = [];
		// }else {
		// 	$clientes_users = ClienteUser::where('id_grupo', $cliente->id_grupo)->get();
		//
		// 	foreach ($clientes_users as $value) {
		// 		$users_del_grupo[] = User::find($value->id_user);
		// 	}
		//
		// }

		$today = CarbonImmutable::now();

		$mes_actual = Carbon::parse(Carbon::now()->format('M'))->month;
		$anio_actual = Carbon::parse(Carbon::now()->format('Y'))->year;

		/*$inicio_mes_pasado = new Carbon('first day of last month');
		$inicio_mes_pasado->startOfMonth();
		$final_mes_pasado = new Carbon('last day of last month');
		$final_mes_pasado->endOfMonth();

		$inicio_mes_actual = new Carbon('first day of this month');
		$inicio_mes_actual->startOfMonth();
		$final_mes_actual = new Carbon('last day of this month');
		$final_mes_actual->endOfMonth();

		$mes_actual = Carbon::parse(Carbon::now()->format('M'))->month;
		$anio_actual = Carbon::parse(Carbon::now()->format('Y'))->year;
		$fecha_actual = Carbon::now();

		$query = Ausentismo::join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
		->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
		->where('nominas.id_cliente', auth()->user()->id_cliente_relacionar)
		->whereDate('ausentismos.fecha_inicio', '>=', $inicio_mes_pasado)
		->whereDate('ausentismos.fecha_inicio', '<=', $final_mes_pasado)
		->select('ausentismos.*', 'nominas.nombre', 'nominas.email', 'nominas.telefono', 'nominas.dni', 'nominas.estado', DB::raw('ausentismo_tipo.nombre nombre_ausentismo'))
		->get();


		$ausentismos_mes_pasado = 0;
		foreach ($query as $value) {
			if ($value->fecha_regreso_trabajar == null || $value->fecha_regreso_trabajar <= $final_mes_pasado) {
				$ausentismos_mes_pasado++;
			}
		}


		$query = Ausentismo::join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
		->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
		->where('nominas.id_cliente', auth()->user()->id_cliente_relacionar)
		->whereDate('ausentismos.fecha_inicio', '>=', $inicio_mes_actual)
		->select('ausentismos.*', 'nominas.nombre', 'nominas.email', 'nominas.telefono', 'nominas.dni', 'nominas.estado', DB::raw('ausentismo_tipo.nombre nombre_ausentismo'))
		->get();

		$ausentismos_mes_actual = 0;
		foreach ($query as $value) {
			if ($value->fecha_regreso_trabajar == null || $value->fecha_regreso_trabajar <= $final_mes_actual) {
				$ausentismos_mes_actual++;
			}
		}*/


		// accidentes_mes_pasado //
		/*$query = Ausentismo::join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
		->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
		->where('nominas.id_cliente', auth()->user()->id_cliente_relacionar)
		->whereDate('ausentismos.fecha_inicio', '>=', $inicio_mes_pasado)
		->whereDate('ausentismos.fecha_inicio', '<=', $final_mes_pasado)
		->where('ausentismo_tipo.nombre', 'LIKE', "%accidente%")
		//->orWhere('ausentismo_tipo.nombre', 'LIKE', "%art%")
		->select('ausentismos.*', 'nominas.nombre', 'nominas.email', 'nominas.telefono', 'nominas.dni', 'nominas.estado',
		DB::raw('ausentismo_tipo.nombre nombre_ausentismo'))
		->get();

		$accidentes_mes_pasado = 0;
		foreach ($query as $value) {
			if ((strpos($value->nombre_ausentismo, 'ART') !== false || strpos($value->nombre_ausentismo, 'accidente') !== false) &&
				($value->fecha_regreso_trabajar == null || $value->fecha_regreso_trabajar <= $final_mes_pasado)) {
				$accidentes_mes_pasado++;
			}
		}*/
		// accidentes_mes_pasado //


		// accidentes_mes_actual //
		/*$query = Ausentismo::join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
			->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
			->where('nominas.id_cliente', auth()->user()->id_cliente_relacionar)
			->whereDate('ausentismos.fecha_inicio', '>=', $inicio_mes_actual)
			->where('ausentismo_tipo.nombre', 'LIKE', "%accidente%")
			//->orWhere('ausentismo_tipo.nombre', 'LIKE', "%art%")
			->select('ausentismos.*', 'nominas.nombre', 'nominas.email', 'nominas.telefono', 'nominas.dni', 'nominas.estado', DB::raw('ausentismo_tipo.nombre nombre_ausentismo'))
			->get();

		$accidentes_mes_actual = 0;
		foreach ($query as $value) {
			if ((strpos($value->nombre_ausentismo, 'ART') !== false || strpos($value->nombre_ausentismo, 'accidente') !== false) &&
				($value->fecha_regreso_trabajar == null || $value->fecha_regreso_trabajar <= $final_mes_actual)) {
				$accidentes_mes_actual++;
			}
		}*/


		/*$ausentismos_top_10 = Ausentismo::join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
		->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
		->where('nominas.id_cliente', '=', auth()->user()->id_cliente_relacionar)
		->select('ausentismo_tipo.nombre', 'ausentismos.fecha_inicio', 'ausentismos.fecha_regreso_trabajar',
		DB::raw('nominas.nombre trabajador'),
		DB::raw('YEAR(ausentismos.fecha_inicio) year, MONTH(ausentismos.fecha_inicio) month'))
		->get();



		// Tabla de cantidad de de veces que se cargó una falta para un trabajador //
		$cant_veces_user_pide_faltar = [];
		$faltas = [];
		$vuelta = 0;
		foreach($ausentismos_top_10 as $t) {
			$cant = 0;
			for ($i=0; $i < count($ausentismos_top_10); $i++) {
				$persona = $ausentismos_top_10[$i]->trabajador;
				if($persona == $t->trabajador){
					$cant_veces_user_pide_faltar[$vuelta][] = [
						'trabajador' => $t->trabajador,
						'cant' => $cant + 1
					];
				}
			}
			$faltas[] = [
				'trabajador' => $t->trabajador,
				'cant' => count($cant_veces_user_pide_faltar[$vuelta])
			];
			$vuelta++;
		}
		$faltas_array = array_values(array_unique($faltas, SORT_REGULAR));
		$faltas_final = array_splice($faltas_array, 0, 10);*/
		// Tabla de cantidad de de veces que se cargó una falta para un trabajador //




		// Tabla Top 10 dias que falto una persona //
		/*$array_top_10_ausentismos = [];
		if(count($ausentismos_top_10) > 0){
			foreach ($ausentismos_top_10 as $key => $value) {
				$fecha_inicio = date_create($value->fecha_inicio);
				$fecha_regreso_trabajar = date_create($value->fecha_regreso_trabajar);
				$diasDiferencia = $fecha_inicio->diff($fecha_regreso_trabajar);
				$array_top_10_ausentismos[] = [
					'info' => $value,
					'dias_ausente' => $diasDiferencia->days
				];
			}
		}


		foreach ($array_top_10_ausentismos as $key => $row) {
			$info[$key]  = $row['info'];
			$days_ausen[$key] = $row['dias_ausente'];
		}
		$info  = array_column($array_top_10_ausentismos, 'info');
		$days_ausen = array_column($array_top_10_ausentismos, 'dias_ausente');
		$array_multidimensional_top_10 = array_multisort($days_ausen, SORT_DESC, $info, SORT_ASC, $array_top_10_ausentismos);
		$top_10_ausentismos = array_splice($array_top_10_ausentismos, 0, 10);*/
		// Tabla Top 10 dias que falto una persona //




		/*$ausencia_covid = Ausentismo::select(
			'ausentismos.*',
			'nominas.nombre',
			'nominas.email',
			'nominas.telefono',
			'nominas.dni',
			'nominas.estado',
			'ausentismo_tipo.nombre as ausentismo_tipo'
		)

		->join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
		->join('ausentismo_tipo', 'ausentismo_tipo.id', 'ausentismos.id_tipo')

		->where('nominas.id_cliente', auth()->user()->id_cliente_relacionar)
		->where('ausentismo_tipo.nombre','like','%covid%')

		->where('ausentismos.fecha_regreso_trabajar', null)
		->whereDate('ausentismos.fecha_inicio', '<=', $fecha_actual)
		->count();


		$vacunados_varias_dosis = CovidVacuna::join('nominas', 'covid_vacunas.id_nomina', 'nominas.id')
		->where('nominas.id_cliente', auth()->user()->id_cliente_relacionar)
		->selectRaw('covid_vacunas.id_nomina, count(*)')
		->groupBy('covid_vacunas.id_nomina')
		->select('nominas.nombre', DB::raw('count(*) cantidad'))
		->get();

		$cant_vacunados_una_dosis = 0;
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





		$ausentismos_mes_actual = Ausentismo::
			where('fecha_inicio','>=',$today->startOfMonth())
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_relacionar)
					->where('deleted_at',null);
			})
			->count();


		$ausentismos_mes_pasado = Ausentismo::
			where('fecha_inicio','>=',$today->startOfMonth()->subMonth())
			->where('fecha_inicio','<=',$today->endOfMonth()->subMonth())
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_relacionar)
					->where('deleted_at',null);
			})
			->count();





		$accidentes_mes_pasado = Ausentismo::
			whereHas('tipo',function($query){
				$query
					->where('nombre','LIKE','%ART%')
					->orWhere('nombre','LIKE','%accidente%');
			})
			->where('fecha_inicio','>=',$today->startOfMonth()->subMonth())
			->where('fecha_inicio','<=',$today->endOfMonth()->subMonth())
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_relacionar)
					->where('deleted_at',null);
			})
			->count();

		$accidentes_mes_actual = Ausentismo::
			whereHas('tipo',function($query){
				$query
					->where('nombre','LIKE','%ART%')
					->orWhere('nombre','LIKE','%accidente%');
			})
			->where('fecha_inicio','>=',$today->startOfMonth())
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_relacionar)
					->where('deleted_at',null);
			})
			->count();



		$ausentismos_top_10_solicitudes = Ausentismo::
			selectRaw('count(*) as total, id_trabajador')
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_relacionar)
					->where('deleted_at',null);
			})
			->with(['trabajador'=>function($query){
				$query->select('id','nombre');
			}])
			->groupBy('id_trabajador')
			->orderBy('total','desc')
			->limit(10)
			->get();


		DB::enableQueryLog();
		$ausentismos_top_10 = Ausentismo::
			selectRaw('SUM(DATEDIFF( IFNULL(fecha_regreso_trabajar,DATE(NOW())),fecha_inicio )) total_dias, id_trabajador')
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_relacionar)
					->where('deleted_at',null);
			})
			->with(['trabajador'=>function($query){
				$query->selectRaw('id,nombre,(SELECT COUNT(a.id) FROM ausentismos a WHERE a.fecha_regreso_trabajar IS NULL AND a.id_trabajador=nominas.id) as regreso_trabajo');
			}])
			->groupBy('id_trabajador')
			->orderBy('total_dias','desc')
			->limit(10)
			->get();
		$query = DB::getQueryLog();

		///dd($ausentismos_top_10->toArray());

		return view('clientes.resumen', compact('cliente', 'ausentismos_mes_pasado', 'ausentismos_mes_actual',
		'accidentes_mes_pasado', 'accidentes_mes_actual', 'ausentismos_top_10', 'ausentismos_top_10_solicitudes', 'mes_actual'));

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
			//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
			//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
			//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
			//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
			//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
			//
	}


	public function getAccidentesMesActual()
	{

		$mes_actual = Carbon::parse(Carbon::now()->format('M'))->month;
		$anio_actual = Carbon::parse(Carbon::now()->format('Y'))->year;

		$ausentismos = Ausentismo::join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
		->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
		->where('nominas.id_cliente', '=', auth()->user()->id_cliente_relacionar)
		->whereMonth('ausentismos.fecha_inicio', "=", $mes_actual)
		->whereYear('ausentismos.fecha_inicio', "=", $anio_actual)
		->select('ausentismo_tipo.nombre', 'ausentismos.fecha_inicio', DB::raw('YEAR(ausentismos.fecha_inicio) year, MONTH(ausentismos.fecha_inicio) month'))
		->get();

		if (count($ausentismos) > 0){

			$resultados = array();
			foreach ($ausentismos->toArray() as $key => $value) {
				$resultados[$value['month']][$key] = $value['nombre'];
			}

			$array_labels = array_column($ausentismos->toArray(), 'nombre');
			$array_labels = array_unique($array_labels);


			$array_labels_ordenado = [];
			foreach ($array_labels as $key => $value) {
				array_push($array_labels_ordenado, $value);
			}

			foreach ($array_labels_ordenado as $key => $value) {
				$labels[$value] = [
					'cantidad' => 0
				];
			}

			$datos = array();
			foreach ($resultados[$mes_actual] as $resultado) {
				$cantidad = 0;
				for ($i=0; $i < count($array_labels_ordenado); $i++) {
					if($resultado == $array_labels_ordenado[$i]){
						// $prueba = array_key_exists($resultado, $labels);
						$labels[$resultado]['cantidad'] ++;
					}
				}
			}
			foreach ($labels as $key => $value) {
				$datos[] = [
					'nombre' => $key,
					'cantidad' => $value['cantidad']
				];
			}

			$response = [
				'datos' => $datos
			];
			return response()->json($response);

		}else {
			return response()->json(['datos' => []]);
		}

	}




	public function getAccidentesAnual()
	{
		$anio_actual = Carbon::parse(Carbon::now()->format('Y'))->year;

		$ausentismos = Ausentismo::join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
		->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
		->where('nominas.id_cliente', auth()->user()->id_cliente_relacionar)
		->whereYear('ausentismos.fecha_inicio', "=", $anio_actual)
		->select('ausentismo_tipo.nombre', 'ausentismos.fecha_inicio', DB::raw('YEAR(ausentismos.fecha_inicio) year, MONTH(ausentismos.fecha_inicio) month'))
		->get();

		if (count($ausentismos) > 0) {
			$resultados = array();
			foreach ($ausentismos->toArray() as $key => $value) {
				$resultados[$value['year']][$key] = $value['nombre'];
			}

			$array_labels = array_column($ausentismos->toArray(), 'nombre');
			$array_labels = array_unique($array_labels);

			$array_labels_ordenado = [];
			foreach ($array_labels as $key => $value) {
				array_push($array_labels_ordenado, $value);
			}


			foreach ($array_labels_ordenado as $key => $value) {
				$labels[$value] = [
					'cantidad' => 0
				];
			}

			$datos = array();
			foreach ($resultados[$anio_actual] as $resultado) {
				$cantidad = 0;
				for ($i=0; $i < count($array_labels_ordenado); $i++) {
					if($resultado == $array_labels_ordenado[$i]){
						$prueba = array_key_exists($resultado, $labels);
						$labels[$resultado]['cantidad'] ++;
					}
				}
			}

			foreach ($labels as $key => $value) {
				$datos[] = [
					'nombre' => $key,
					'cantidad' => $value['cantidad']
				];
			}

			$response = [
				'datos' => $datos
			];
			return response()->json($response);
		} else {
			return response()->json(['datos' => []]);
		}



	}


}
