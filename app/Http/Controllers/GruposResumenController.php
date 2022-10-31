<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Grupo;
use App\User;
use App\ClienteGrupo;
use App\Ausentismo;
use App\Http\Traits\ClientesGrupo;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class GruposResumenController extends Controller
{
	use ClientesGrupo;
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		setlocale(LC_TIME, 'Spanish');

		//DB::enableQueryLog();

		// el metodo getClientesGrupo heredada de App\Http\Traits\ClientesGrupo
		$clientes_grupo = $this->getClientesGrupo();


		$today = CarbonImmutable::now();

		//dd($clientes_grupo['grupo']);

		// todos las empresas del grupo
		$clientes_nominas = $clientes_grupo['grupo']->fresh([


			'clientes'=>function($query) use ($today){

			$query->select('clientes.id','clientes.nombre')

				//total nómina
				->withCount('nominas')

				//ausentes hoy
				->withCount([
					'ausentismos'=>function($query) use ($today) {
						$query
							->where('fecha_regreso_trabajar',null)
							->orWhere('fecha_regreso_trabajar','>=',$today);
					},
					'ausentismos as ausentismos_mes_count'=>function($query) use ($today) {
						$query
							->where('fecha_inicio','>=',$today->startOfMonth());
					},
					'ausentismos as ausentismos_mes_pasado_count'=>function($query) use ($today) {
						$query
							->where('fecha_inicio','>=',$today->startOfMonth()->subMonth())
							->where('fecha_inicio','<=',$today->endOfMonth()->subMonth());
					},
					'ausentismos as ausentismos_year_count'=>function($query) use ($today) {
						$query
							->where('fecha_inicio','>=',$today->firstOfYear());
					},
					'ausentismos as accidentes_mes_count'=>function($query) use ($today) {
						$query
							->where('fecha_inicio','>=',$today->startOfMonth())
							->whereHas('tipo',function($query){
								$query
									->where('nombre','LIKE','%ART%')
									->orWhere('nombre','LIKE','%accidente%');
							});
					},
					'ausentismos as accidentes_mes_pasado_count'=>function($query) use ($today) {
						$query
							->where('fecha_inicio','>=',$today->startOfMonth()->subMonth())
							->where('fecha_inicio','<=',$today->endOfMonth()->subMonth())
							->whereHas('tipo',function($query){
								$query
									->where('nombre','LIKE','%ART%')
									->orWhere('nombre','LIKE','%accidente%');
							});
					}
				]);

			}
		]);

		//dd( $clientes_nominas );

		$output = array_merge($clientes_grupo,[
			'clientes_nominas'=>$clientes_nominas
		]);

		return view('grupos.resumen',$output);
	}

	public function index_ajax()
	{
		$today = CarbonImmutable::now();

		DB::enableQueryLog();

		$ausentismos_mes = Ausentismo::
			selectRaw('count(*) as total, id_tipo')
			->with('tipo')
			->where('fecha_inicio','>=',$today->startOfMonth())
			->whereIn('id_trabajador',function($query){
				$query
					->select('id')
					->from('nominas')
					->whereIn('id_cliente',function($query){
						$query
							->select('id_cliente')
							->from('cliente_grupo')
							->where('id_grupo',auth()->user()->id_grupo);
					});
			})
			/*->with(['trabajador'=>function($query){
				$query
					->with(['cliente'=>function($query){
						$query
							->with(['cliente_grupo'=>function($query){
								$query->where('id_grupo',auth()->user()->id_grupo);
							}]);
					}]);
			}])*/
			->groupBy('id_tipo')
			->get();

		$query = DB::getQueryLog();


		$ausentismos_anual = Ausentismo::
			selectRaw('count(*) as total, id_tipo')
			->with('tipo')
			->where('fecha_inicio','>=',$today->firstOfYear())
			->whereIn('id_trabajador',function($query){
				$query
					->select('id')
					->from('nominas')
					->whereIn('id_cliente',function($query){
						$query
							->select('id_cliente')
							->from('cliente_grupo')
							->where('id_grupo',auth()->user()->id_grupo);
					});
			})
			->groupBy('id_tipo')
			->get();

		return [
			'status'=>'ok',
			'ausentismos_mes'=>$ausentismos_mes,
			'ausentismos_anual'=>$ausentismos_anual,
			'query'=>$query
		];

	}

	public function index_cliente()
	{

		setlocale(LC_TIME, 'Spanish');

		$clientes_grupo = $this->getClientesGrupo();


		$today = CarbonImmutable::now();

		$ausentismos_mes = Ausentismo::
			where('fecha_inicio','>=',$today->startOfMonth())
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_actual);
			})
			->count();


		$ausentismos_mes_pasado = Ausentismo::
			where('fecha_inicio','>=',$today->startOfMonth()->subMonth())
			->where('fecha_inicio','<=',$today->endOfMonth()->subMonth())
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_actual);
			})
			->count();


		$accidentes_mes = Ausentismo::
			whereHas('tipo',function($query){
				$query
					->where('nombre','LIKE','%ART%')
					->orWhere('nombre','LIKE','%accidente%');
			})
			->where('fecha_inicio','>=',$today->startOfMonth())
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_actual);
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
					->where('id_cliente',auth()->user()->id_cliente_actual);
			})
			->count();


		$ausentismos_top_10 = Ausentismo::
			selectRaw('count(*) as total, id_trabajador')
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_actual);
			})
			->with(['trabajador'=>function($query){
				$query->select('id','nombre');
			}])
			->groupBy('id_trabajador')
			->orderBy('total','desc')
			->limit(10)
			->get();



		$ausentismos_top_10_dias = Ausentismo::
			selectRaw('DATEDIFF( IFNULL(fecha_regreso_trabajar,DATE(NOW())),fecha_inicio ) total_dias, id_trabajador')
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_actual);
			})
			->with(['trabajador'=>function($query){
				$query->select('id','nombre');
			}])
			->groupBy('id_trabajador')
			->orderBy('total_dias','desc')
			->limit(10)
			->get();

		//dd($ausentismos_top_10);


		$output = array_merge($clientes_grupo,[
			'ausentismos_mes'=>$ausentismos_mes,
			'ausentismos_mes_pasado'=>$ausentismos_mes_pasado,
			'accidentes_mes'=>$accidentes_mes,
			'accidentes_mes_pasado'=>$accidentes_mes_pasado,

			'ausentismos_top_10'=>$ausentismos_top_10,
			'ausentismos_top_10_dias'=>$ausentismos_top_10_dias
		]);


		return view('grupos.resumen_cliente',$output);
	}

	public function index_cliente_ajax()
	{

		$today = CarbonImmutable::now();

		//DB::enableQueryLog();

		$ausentismos_mes = Ausentismo::selectRaw('count(*) as total, id_tipo')
			->with('tipo')
			->where('fecha_inicio','>=',$today->startOfMonth())
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_actual);
			})
			->groupBy('id_tipo')
			->get();

		//$query = DB::getQueryLog();

		$ausentismos_anual = Ausentismo::groupBy('id_tipo')
			->with('tipo')
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_actual);
			})
			->selectRaw('count(*) as total, id_tipo')
			->where('fecha_inicio','>=',$today->firstOfYear())
			->get();

		return [
			'status'=>'ok',
			'ausentismos_mes'=>$ausentismos_mes,
			'ausentismos_anual'=>$ausentismos_anual,
			//'query'=>$query
		];
	}

	public function clienteActual(Request $request)
	{
		$user = User::findOrFail(auth()->user()->id);
		$user->id_cliente_actual = intval($request->id_cliente);
		$user->save();
		return ['status'=>'ok'];
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
}
