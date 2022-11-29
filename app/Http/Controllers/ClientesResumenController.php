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


		$today = CarbonImmutable::now();


		/// AUSENTISMOS
		/// Mes actual
		$ausentismos_mes_actual = Ausentismo::
			where('fecha_inicio','>=',$today->startOfMonth())
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_relacionar)
					->where('deleted_at',null);
			})
			->count();


		/// Mes pasado
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

		/// Mes año anterior
		$ausentismos_mes_anio_anterior = Ausentismo::
			where('fecha_inicio','>=',$today->subYear()->startOfMonth())
			->where('fecha_inicio','<=',$today->subYear()->endOfMonth())
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_relacionar)
					->where('deleted_at',null);
			})
			->count();

		/// Año actual
		$ausentismos_anio_actual = Ausentismo::
			where('fecha_inicio','>=',$today->firstOfYear())
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_relacionar)
					->where('deleted_at',null);
			})
			->count();





		/// ACCIDENTES
		/// Mes actual
		$accidentes_mes_actual = Ausentismo::
			whereHas('tipo',function($query){
				$query
					->where('nombre','=','ART')
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

		/// Mes pasado
		$accidentes_mes_pasado = Ausentismo::
			whereHas('tipo',function($query){
				$query
					->where('nombre','=','ART')
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


		/// Mes año anterior
		$accidentes_mes_anio_anterior = Ausentismo::
			whereHas('tipo',function($query){
				$query
					->where('nombre','=','ART')
					->orWhere('nombre','LIKE','%accidente%');
			})
			->where('fecha_inicio','>=',$today->subYear()->startOfMonth())
			->where('fecha_inicio','<=',$today->subYear()->endOfMonth())
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_relacionar)
					->where('deleted_at',null);
			})
			->count();

		/// Mes año anterior
		$accidentes_anio_actual = Ausentismo::
			whereHas('tipo',function($query){
				$query
					->where('nombre','=','ART')
					->orWhere('nombre','LIKE','%accidente%');
			})
			->where('fecha_inicio','>=',$today->firstOfYear())
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_relacionar)
					->where('deleted_at',null);
			})
			->count();




		/// INCIDENTES
		/// Mes actual
		$incidentes_mes_actual = Ausentismo::
			whereHas('tipo',function($query){
				$query
					->where('nombre','LIKE','%incidente%');
			})
			->where('fecha_inicio','>=',$today->startOfMonth())
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_relacionar)
					->where('deleted_at',null);
			})
			->count();

		/// Mes pasado
		$incidentes_mes_pasado = Ausentismo::
			whereHas('tipo',function($query){
				$query
					->where('nombre','LIKE','%incidente%');
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


		/// Mes año anterior
		$incidentes_mes_anio_anterior = Ausentismo::
			whereHas('tipo',function($query){
				$query
					->where('nombre','LIKE','%incidente%');
			})
			->where('fecha_inicio','>=',$today->subYear()->startOfMonth())
			->where('fecha_inicio','<=',$today->subYear()->endOfMonth())
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_relacionar)
					->where('deleted_at',null);
			})
			->count();

		/// Mes año anterior
		$incidentes_anio_actual = Ausentismo::
			whereHas('tipo',function($query){
				$query
					->where('nombre','LIKE','%incidente%');
			})
			->where('fecha_inicio','>=',$today->firstOfYear())
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_relacionar)
					->where('deleted_at',null);
			})
			->count();





		/// TOP 10
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

		return view('clientes.resumen', compact(
			'cliente',

			'ausentismos_mes_actual',
			'ausentismos_mes_pasado',
			'ausentismos_mes_anio_anterior',
			'ausentismos_anio_actual',

			'accidentes_mes_actual',
			'accidentes_mes_pasado',
			'accidentes_mes_anio_anterior',
			'accidentes_anio_actual',

			'incidentes_mes_actual',
			'incidentes_mes_pasado',
			'incidentes_mes_anio_anterior',
			'incidentes_anio_actual',

			'ausentismos_top_10',
			'ausentismos_top_10_solicitudes'
		));

	}
	public function index_ajax()
	{
		$today = CarbonImmutable::now();

		$ausentismos_mes = Ausentismo::selectRaw('count(*) as total, id_tipo')
			->with('tipo')
			->where('fecha_inicio','>=',$today->startOfMonth())
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_relacionar);
			})
			->groupBy('id_tipo')
			->get();

		//DB::enableQueryLog();
		$ausentismos_mes_anterior = Ausentismo::selectRaw('count(*) as total, id_tipo')
			->with('tipo')
			->where('fecha_inicio','>=',$today->subMonth()->startOfMonth())
			->where(function($query) use ($today){
				$query
					->where('fecha_regreso_trabajar',null)
					->orwhere('fecha_regreso_trabajar','<=',$today->subMonth()->endOfMonth());
			})
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_relacionar);
			})
			->groupBy('id_tipo')
			->get();
		//$query = DB::getQueryLog();


		$ausentismos_mes_anio_anterior = Ausentismo::selectRaw('count(*) as total, id_tipo')
			->with('tipo')
			->where('fecha_inicio','>=',$today->subYear()->startOfMonth())
			->where(function($query) use ($today){
				$query
					->where('fecha_regreso_trabajar',null)
					->orwhere('fecha_regreso_trabajar','<=',$today->subYear()->lastOfYear());
			})
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_relacionar);
			})
			->groupBy('id_tipo')
			->get();



		$ausentismos_anual = Ausentismo::selectRaw('count(*) as total, id_tipo')
			->with('tipo')
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_relacionar);
			})
			->groupBy('id_tipo')
			->where('fecha_inicio','>=',$today->firstOfYear())
			->get();

		return [
			'status'=>'ok',
			'ausentismos_mes'=>$ausentismos_mes,
			'ausentismos_mes_anterior'=>$ausentismos_mes_anterior,
			'ausentismos_mes_anio_anterior'=>$ausentismos_mes_anio_anterior,
			'ausentismos_anual'=>$ausentismos_anual,
			//'query'=>$query
		];
	}


	/*public function getAccidentesMesActual()
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
	}*/

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





}
