<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Grupo;
use App\User;
use App\ClienteGrupo;
use App\Ausentismo;
use App\Http\Traits\ClientesGrupo;
use App\Http\Traits\Clientes;
use App\Http\Traits\Ausentismos;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class GruposResumenController extends Controller
{
	use ClientesGrupo,Clientes,Ausentismos;
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

		// todos las empresas del grupo

		DB::enableQueryLog();
		$clientes_nominas = $clientes_grupo['grupo']->fresh([


			'clientes'=>function($query) use ($today){

				$query->select('clientes.id','clientes.nombre')

				//total nómina
				->withCount(['nominas'=>function($query){
					$query->where('estado',1);
				}])

				->withCount([

					//ausentes hoy
					'ausentismos'=>function($query) use ($today) {

						$query->where(function($query) use ($today) {
							$query->
								where('fecha_regreso_trabajar',null)
								->orWhere('fecha_regreso_trabajar','>',$today);
						})
						->whereHas('trabajador',function($query){
							$query->where('estado',1);
						});

					},

					//ausentes mes
					'ausentismos as ausentismos_mes_count'=>function($query) use ($today) {
						$query
							->where('fecha_inicio','>=',$today->startOfMonth())
							->whereHas('trabajador',function($query){
								$query->where('estado',1);
							});
					},

					//ausentes mes pasado
					'ausentismos as ausentismos_mes_pasado_count'=>function($query) use ($today) {
						$query
							->where('fecha_inicio','>=',$today->subMonth()->startOfMonth())
							->where('fecha_inicio','<=',$today->subMonth()->endOfMonth())
							->whereHas('trabajador',function($query){
								$query->where('estado',1);
							});
					},

					//ausentes año actual
					'ausentismos as ausentismos_year_count'=>function($query) use ($today) {
						$query
							->where('fecha_inicio','>=',$today->firstOfYear())
							->whereHas('trabajador',function($query){
								$query->where('estado',1);
							});
					},

					/*//accidentes mes actual
					'ausentismos as accidentes_mes_count'=>function($query) use ($today) {
						$query
							->where('fecha_inicio','>=',$today->startOfMonth())
							->whereHas('tipo',function($query){
								$query
									->where('nombre','LIKE','%ART%')
									->orWhere('nombre','LIKE','%accidente%');
							})
							->whereHas('trabajador',function($query){
								$query->where('estado',1);
							});
					},

					//accidentes mes pasado
					'ausentismos as accidentes_mes_pasado_count'=>function($query) use ($today) {
						$query
							->where('fecha_inicio','>=',$today->startOfMonth()->subMonth())
							->where('fecha_inicio','<=',$today->endOfMonth()->subMonth())
							->whereHas('tipo',function($query){
								$query
									->where('nombre','LIKE','%ART%')
									->orWhere('nombre','LIKE','%accidente%');
							})
							->whereHas('trabajador',function($query){
								$query->where('estado',1);
							});
					}*/
				]);

			}
		]);
		//dd(DB::getQueryLog());

		//dd( $clientes_nominas->clientes[0]->toArray() );

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

			->where(function($query) use ($today){

				$query->where('fecha_inicio','>=',$today->startOfMonth())
					->orWhere(function($query) use ($today){
						$query->where('fecha_inicio','<',$today->startOfMonth())
							->where('fecha_regreso_trabajar',null);
					});

			})

			->whereIn('id_trabajador',function($query){
				$query
					->select('id')
					->from('nominas')
					->where('deleted_at',null)
					->where('estado',1)
					->whereIn('id_cliente',function($query){
						$query
							->select('id_cliente')
							->from('cliente_grupo')
							->where('id_grupo',auth()->user()->id_grupo);
					});
			})
			->groupBy('id_tipo')
			->orderBy('total','desc')
			->get();

		$query_log = DB::getQueryLog();



		$ausentismos_mes_anterior = Ausentismo::
			selectRaw('count(*) as total, id_tipo')
			->with('tipo')
			->where('fecha_inicio','>=',$today->subMonth()->startOfMonth())
			->where('fecha_inicio','<=',$today->subMonth()->endOfMonth())
			/*->where(function($query) use ($today){
				$query
					->where('fecha_regreso_trabajar',null)
					->orwhere('fecha_regreso_trabajar','<=',$today->subMonth()->endOfMonth());
			})*/
			->whereIn('id_trabajador',function($query){
				$query
					->select('id')
					->from('nominas')
					->where('deleted_at',null)
					->where('estado',1)
					->whereIn('id_cliente',function($query){
						$query
							->select('id_cliente')
							->from('cliente_grupo')
							->where('id_grupo',auth()->user()->id_grupo);
					});
			})
			->groupBy('id_tipo')
			->get();


		$ausentismos_mes_anio_anterior = Ausentismo::
			selectRaw('count(*) as total, id_tipo')
			->with('tipo')
			->where('fecha_inicio','>=',$today->subYear()->startOfMonth())
			->where('fecha_inicio','<=',$today->subYear()->endOfMonth())
			/*->where(function($query) use ($today){
				$query
					->where('fecha_regreso_trabajar',null)
					->orwhere('fecha_regreso_trabajar','<=',$today->subYear()->lastOfYear());
			})*/
			->whereIn('id_trabajador',function($query){
				$query
					->select('id')
					->from('nominas')
					->where('deleted_at',null)
					->where('estado',1)
					->whereIn('id_cliente',function($query){
						$query
							->select('id_cliente')
							->from('cliente_grupo')
							->where('id_grupo',auth()->user()->id_grupo);
					});
			})
			->groupBy('id_tipo')
			->get();


		$ausentismos_anual = Ausentismo::
			selectRaw('count(*) as total, id_tipo')
			->with('tipo')
			->where('fecha_inicio','>=',$today->subYear())
			////->where('fecha_inicio','<=',$today)
			->whereIn('id_trabajador',function($query){
				$query
					->select('id')
					->from('nominas')
					->where('deleted_at',null)
					->where('estado',1)
					->whereIn('id_cliente',function($query){
						$query
							->select('id_cliente')
							->from('cliente_grupo')
							->where('id_grupo',auth()->user()->id_grupo);
					});
			})
			->groupBy('id_tipo')
			->orderBy('total','desc')
			->get();

		$status = 'ok';

		return compact(
			'status',
			'ausentismos_mes',
			'ausentismos_mes_anterior',
			'ausentismos_mes_anio_anterior',
			'ausentismos_anual',
			'query_log'
		);

	}

	public function index_cliente()
	{

		setlocale(LC_TIME, 'Spanish');

		$clientes_grupo = $this->getClientesGrupo();

		$id_cliente = auth()->user()->id_cliente_actual;

		// Traits > Clientes
		$output = array_merge($clientes_grupo,$this->resumen($id_cliente));

		return view('grupos.resumen_cliente', $output);
	}

	public function index_cliente_ajax()
	{
		// Traits > Ausentismos
		return $this->ausentismosAjax(auth()->user()->id_cliente_actual);

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
