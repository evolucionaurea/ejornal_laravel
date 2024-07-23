<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Grupo;
use App\User;
use App\ClienteGrupo;
use App\Ausentismo;
use App\Nomina;
use App\NominaHistorial;
use App\Http\Traits\ClientesGrupo;
use App\Http\Traits\Clientes;
use App\Http\Traits\Ausentismos;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

		// el metodo getClientesGrupo heredada de App\Http\Traits\ClientesGrupo
		// todos las empresas del grupo
		$clientes_grupo = $this->getClientesGrupo();
		$today = CarbonImmutable::now();


		$clientes_nominas = $clientes_grupo['grupo']->fresh([

			'clientes'=>function($query) use ($today){

				$query->select('clientes.id','clientes.nombre')

					/*->with(['nominas_historial'=>function($query) use ($today){
						$query->select('cliente_id','cantidad','year_month')->where('year_month','=',$today->format('Ym'))->first();
					}])*/

					//total nóminas
					->withCount('nominas')
					->withCount(['nominas as nominas_mes_anterior'=>function($query) use ($today){
						$query->whereDate('created_at','<=',$today->firstOfMonth()->subMonth()->endOfMonth()->toDateString());
					}])
					->withCount(['nominas as nominas_mes_anio_anterior'=>function($query) use ($today){
						$query->whereDate('created_at','<=',$today->firstOfMonth()->subYear()->endOfMonth()->toDateString());
					}]);

			}
		]);

		//dd($clientes_nominas->clientes);


		//DB::enableQueryLog();
		foreach($clientes_nominas->clientes as $kc=>$cliente){
			$clientes_nominas->clientes[$kc]->ausentismos = (object) $this->resumen($cliente->id);
		}
		//dd( $clientes_nominas->clientes[3]->ausentismos );
		$output = array_merge($clientes_grupo,[
			'clientes_nominas'=>$clientes_nominas
		]);

		return view('grupos.resumen',$output);
	}

	public function index_ajax()
	{

		$newLocale = setlocale(LC_TIME, 'Spanish');

		$today = CarbonImmutable::now();

		$id_grupo = auth()->user()->id_grupo;

		$q_nomina = NominaHistorial::selectRaw("SUM(cantidad) cantidad")
			->where('year_month',$today->format('Ym'))
			->whereIn('cliente_id',function($query) use ($id_grupo){
				$query
					->select('id_cliente')
					->from('cliente_grupo')
					->where('id_grupo',$id_grupo);
			})
			->first();

		if(!$q_nomina){
			\Artisan::call('db:seed', [
				'--class' => 'NominaHistorialSeeder',
				'--force' => true
			]);
			$q_nomina = NominaHistorial::selectRaw("SUM(cantidad) cantidad")
			->where('year_month',$today->format('Ym'))
			->whereIn('cliente_id',function($query) use ($id_grupo){
				$query
					->select('id_cliente')
					->from('cliente_grupo')
					->where('id_grupo',$id_grupo)
					->first();
			});
		}
		$nomina_actual = $q_nomina->cantidad;

		$nomina_mes_anterior = NominaHistorial::selectRaw("SUM(cantidad) cantidad")
			->where('year_month',$today->firstOfMonth()->subMonth()->format('Ym'))
			->whereIn('cliente_id',function($query) use ($id_grupo){
				$query
					->select('id_cliente')
					->from('cliente_grupo')
					->where('id_grupo',$id_grupo);
			})
			->first()
			->cantidad;
		$nomina_mes_anio_anterior = NominaHistorial::selectRaw("SUM(cantidad) cantidad")
			->where('year_month',$today->firstOfMonth()->subYear()->format('Ym'))
			->whereIn('cliente_id',function($query) use ($id_grupo){
				$query
					->select('id_cliente')
					->from('cliente_grupo')
					->where('id_grupo',$id_grupo);
			})
			->first()
			->cantidad;
		///$nomina_promedio_actual = NominaHistorial::selectRaw("CEIL(AVG(cantidad)) as cantidad")
		$nomina_promedio_actual = NominaHistorial::selectRaw("CEIL(SUM(cantidad)/{$today->format('m')}) as cantidad")
			->whereBetween('year_month',[$today->startOfYear()->format('Ym'),$today->format('Ym')])
			->whereIn('cliente_id',function($query) use ($id_grupo){
				$query
					->select('id_cliente')
					->from('cliente_grupo')
					->where('id_grupo',$id_grupo);
			})
			->first()
			->cantidad;
		//dd($nomina_promedio_actual->cantidad);

		/***************************************************************************************************/
		/*** Unificar queries desde Traits/Ausentismos ya que son las mismas pero cambia un solo where  ****/
		/***************************************************************************************************/

		/// ausentismos mes a mes
		$months_current_year = CarbonPeriod::create($today->startOfYear(),'1 month', $today);
		$indices_mes_a_mes = [];
		foreach($months_current_year as $date){

			$date_immutable = CarbonImmutable::create($date->format('Y'),$date->format('m'),1);

			$inicio_mes = $date_immutable->firstOfMonth();
			$inicio_mes_formatted = $inicio_mes->format('Y-m-d');
			$fin_mes = $today->format('Ym')==$date->format('Ym') ? $today : $date_immutable->lastOfMonth();
			$fin_mes_formatted = $fin_mes->format('Y-m-d');

			///
			$nomina = NominaHistorial::selectRaw("SUM(cantidad) cantidad, nominas_historial.year_month")
				->where('year_month',$date->format('Ym'))
				->whereIn('cliente_id',function($query) use ($id_grupo){
					$query
						->select('id_cliente')
						->from('cliente_grupo')
						->where('id_grupo',$id_grupo);
				})
				->first()
				->cantidad;

			//DB::enableQueryLog();
			$ausentismos = Ausentismo::selectRaw("
				SUM(
					ABS(DATEDIFF(
						IF(
							IFNULL(
								fecha_final,
								'{$fin_mes_formatted}'
							) >= '{$fin_mes_formatted}',
							'{$fin_mes_formatted}',
							fecha_final
						),
						IF(
							fecha_inicio>='{$inicio_mes_formatted}',
							fecha_inicio,
							'{$inicio_mes_formatted}'
						)
					))+1
				) dias
			")
				->where(function($query) use ($inicio_mes,$fin_mes,$date_immutable){
					$query->whereBetween('fecha_inicio',[$inicio_mes,$fin_mes])
					->orWhere(function($query) use ($inicio_mes,$fin_mes,$date_immutable){
						$query->where('fecha_inicio','<',$inicio_mes)
							->where(function($query) use ($fin_mes,$inicio_mes,$date_immutable){
								$query->where('fecha_final','>=',$inicio_mes)
									->orWhere('fecha_final',null);
							});
					});
				})
				->whereIn('id_trabajador',function($query) use ($id_grupo){
					$query
						->select('id')
						->from('nominas')
						->where('deleted_at',null)
						->where('estado',1)
						->whereIn('id_cliente',function($query) use ($id_grupo){
							$query
								->select('id_cliente')
								->from('cliente_grupo')
								->where('id_grupo',$id_grupo);
						});
				})
				->whereHas('tipo',function($query){
					$query
						->where('incluir_indice',1)
						->where('nombre','NOT LIKE','%incidente%')
						->where(function($query){
							$query
								->where('agrupamiento','!=','ART')
								->orWhere('agrupamiento',null);
						});
				})
				->first();

			$indices_mes_a_mes[] = [
				//'ausentismos'=>$ausentismos->total,
				//'q'=>DB::getQueryLog(),
				'dias'=>$ausentismos->dias,
				'month'=>Str::ucfirst($date->formatLocalized('%B')),
				'indice'=>$nomina ? ($ausentismos->dias/($nomina*( $fin_mes->format('d') ))*100) : 0,
				'nomina'=>$nomina,
				'inicio_mes'=>$inicio_mes_formatted,
				'fin_mes'=>$fin_mes_formatted
				///'dia'=>$today->format('Ym')==$date->format('Ym') ? $today->format('d') : $date->endOfMonth()->format('d')
			];
		}



		DB::enableQueryLog();
		$inicio_mes = $today->startOfMonth()->format('Y-m-d');
		$ausentismos_mes = Ausentismo::selectRaw("
			SUM(
				ABS(DATEDIFF(

					IF(
						IFNULL(
							fecha_final,
							DATE(NOW())
						) >= DATE(NOW()),
						DATE(NOW()),
						fecha_final
					),

					IF(
						fecha_inicio>='{$inicio_mes}',
						fecha_inicio,
						'{$inicio_mes}'
					)
				))+1
			) dias,

			count(*) as total,
			id_tipo"
		)
			->with(['tipo'=>function($query){
				$query->select('id','nombre','color');
			}])
			->where(function($query) use ($today){
				$query->where(function($query) use ($today){
					$query
						->whereBetween('fecha_inicio',[$today->startOfMonth(),$today]);
				})
				->orWhere(function($query) use ($today){
					// los que siguen ausentes fuera del mes actual
					$query
						->where('fecha_inicio','<',$today->startOfMonth())
						->where(function($query) use($today){
							$query
								->where('fecha_final','>=',$today->startOfMonth())
								->orWhere('fecha_final',null);
						});
				});
			})
			->whereIn('id_trabajador',function($query) use ($id_grupo){
				$query
					->select('id')
					->from('nominas')
					->where('deleted_at',null)
					->where('estado',1)
					->whereIn('id_cliente',function($query) use ($id_grupo){
						$query
							->select('id_cliente')
							->from('cliente_grupo')
							->where('id_grupo',$id_grupo);
					});
			})
			->whereHas('tipo',function($query){
				$query
					->where('incluir_indice',1)
					->where('nombre','NOT LIKE','%incidente%')
					->where(function($query){
						$query
							->where('agrupamiento','!=','ART')
							->orWhere('agrupamiento',null);
					});
			})
			->groupBy('id_tipo')
			->orderBy('dias','desc')
			->get();
		$query_log = DB::getQueryLog();
		///dd($query_log);



		/// MES PASADO
		$inicio_mes_pasado = $today->subMonth()->startOfMonth()->format('Y-m-d');
		$fin_mes_pasado = $today->subMonth()->endOfMonth()->format('Y-m-d');
		$ausentismos_mes_anterior = Ausentismo::selectRaw("
			SUM(
				ABS(DATEDIFF(
					IF(
						fecha_final<'{$fin_mes_pasado}',
						fecha_final,
						'{$fin_mes_pasado}'
					),
					IF(
						fecha_inicio<'{$inicio_mes_pasado}',
						'{$inicio_mes_pasado}',
						fecha_inicio
					)
				))+1
			) dias,
			count(*) as total,
			id_tipo
		")
			->with(['tipo'=>function($query){
				$query->select('id','nombre','color');
			}])
			->where(function($query) use ($today){
				$query->whereBetween('fecha_inicio',[$today->startOfMonth()->subMonth(),$today->startOfMonth()->subMonth()->endOfMonth()])
				/*$query->where(function($query) use ($today){
					$query
						->whereBetween('fecha_inicio',[$today->subMonth()->startOfMonth(),$today->subMonth()->endOfMonth()])
						->where(function($query) use ($today){
							$query->where('fecha_final','<=',$today->subMonth()->endOfMonth())
								->orWhere('fecha_final',null);
						});
				})*/
				// los que estuvieron ausentes durante el curso de ese mes pero iniciaron ausentismo antes de ese mes y volvieron dsp
				->orWhere(function($query) use ($today){
					$query->where('fecha_inicio','<',$today->startOfMonth()->subMonth())
						->where(function($query) use ($today){
							$query->where('fecha_final','>=',$today->startOfMonth()->subMonth())
								->orWhere('fecha_final',null);
						});
				});
			})
			->whereIn('id_trabajador',function($query) use ($id_grupo){
				$query
					->select('id')
					->from('nominas')
					->where('deleted_at',null)
					->where('estado',1)
					->whereIn('id_cliente',function($query) use ($id_grupo){
						$query
							->select('id_cliente')
							->from('cliente_grupo')
							->where('id_grupo',$id_grupo);
					});
			})
			->whereHas('tipo',function($query){
				$query
					->where('incluir_indice',1)
					->where('nombre','NOT LIKE','%incidente%')
					->where(function($query){
						$query
							->where('agrupamiento','!=','ART')
							->orWhere('agrupamiento',null);
					});
			})
			->groupBy('id_tipo')
			->get();


		/// MES AÑO ANTERIOR
		$inicio_mes_anio_anterior = $today->subYear()->startOfMonth()->format('Y-m-d');
		$fin_mes_anio_anterior = $today->subYear()->endOfMonth()->format('Y-m-d');
		$ausentismos_mes_anio_anterior = Ausentismo::selectRaw("
			SUM(
				ABS(DATEDIFF(
					IF(
						fecha_final<'{$fin_mes_anio_anterior}',
						fecha_final,
						'{$fin_mes_anio_anterior}'
					),
					IF(
						fecha_inicio<'{$inicio_mes_anio_anterior}',
						'{$inicio_mes_anio_anterior}',
						fecha_inicio
					)
				))+1
			) dias,
			count(*) as total,
			id_tipo"
		)
			->with(['tipo'=>function($query){
				$query->select('id','nombre','color');
			}])
			->where(function($query) use ($today){
				$query->where(function($query) use ($today){
					$query
						->whereBetween('fecha_inicio',[$today->startOfMonth()->subYear(),$today->startOfMonth()->subYear()->endOfMonth()])
						->where(function($query) use ($today){
							$query
								->where('fecha_final','<=',$today->startOfMonth()->subMonth()->endOfMonth())
								->orWhere('fecha_final',null);
						});
				})
				// los que estuvieron ausentes durante el curso de ese mes pero iniciaron ausentismo antes de ese mes y volvieron dsp
				->orWhere(function($query) use ($today){
					$query->where('fecha_inicio','<',$today->startOfMonth()->subYear())
						->where(function($query) use ($today){
							$query
								->where('fecha_final','>=',$today->startOfMonth()->subYear())
								->orWhere('fecha_final',null);
						});
				});
			})
			->whereIn('id_trabajador',function($query) use ($id_grupo){
				$query
					->select('id')
					->from('nominas')
					->where('deleted_at',null)
					->where('estado',1)
					->whereIn('id_cliente',function($query) use ($id_grupo){
						$query
							->select('id_cliente')
							->from('cliente_grupo')
							->where('id_grupo',$id_grupo);
					});
			})
			->whereHas('tipo',function($query){
				$query
					->where('incluir_indice',1)
					->where('nombre','NOT LIKE','%incidente%')
					->where(function($query){
						$query
							->where('agrupamiento','!=','ART')
							->orWhere('agrupamiento',null);
					});
			})
			->groupBy('id_tipo')
			->get();


		/// AÑO ACTUAL
		DB::enableQueryLog();
		$inicio_anio = $today->format('Y-01-01');
		$ausentismos_anual = Ausentismo::selectRaw("
			SUM(
				ABS(DATEDIFF(

					IF(
						IFNULL(
							fecha_final,
							DATE(NOW())
						) < DATE(NOW()),
						fecha_final,
						DATE(NOW())
					),

					IF(
						fecha_inicio<'{$inicio_anio}',
						'{$inicio_anio}',
						fecha_inicio
					)
				))+1
			) dias,
			count(*) as total,
			id_tipo
		")
			->with(['tipo'=>function($query){
				$query->select('id','nombre','color');
			}])
			->where(function($query) use ($today){
				$query->whereDate('fecha_inicio','>=',$today->startOfYear())
				// los que estuvieron ausentes durante el curso del año pero iniciaron ausentismo antes de este año y volvieron dsp
				->orWhere(function($query) use ($today){
					$query
						->where('fecha_inicio','<',$today->startOfYear())
						->where(function($query) use ($today){
							$query
								->where('fecha_final','>=',$today->startOfYear())
								->orWhere('fecha_final',null);
						});
				});
			})
			->whereIn('id_trabajador',function($query) use ($id_grupo){
				$query
					->select('id')
					->from('nominas')
					->where('deleted_at',null)
					->where('estado',1)
					->whereIn('id_cliente',function($query) use ($id_grupo){
						$query
							->select('id_cliente')
							->from('cliente_grupo')
							->where('id_grupo',$id_grupo);
					});
			})
			->whereHas('tipo',function($query){
				$query
					->where('incluir_indice',1)
					->where('nombre','NOT LIKE','%incidente%')
					->where(function($query){
						$query
							->where('agrupamiento','!=','ART')
							->orWhere('agrupamiento',null);
					});
			})
			->groupBy('id_tipo')
			->orderBy('dias','desc')
			->get();
		$query = DB::getQueryLog();
		//dd($query);

		$status = 'ok';

		$cant_dias_mes = (int) $today->format('d');
		$cant_dias_mes_anterior = (int) $today->firstOfMonth()->subMonth()->endOfMonth()->format('d');
		$cant_dias_mes_anio_anterior = (int) $today->firstOfMonth()->subYear()->endOfMonth()->format('d');
		$cant_dias_anio = (int) $today->dayOfYear();

		return compact(

			'indices_mes_a_mes',

			'ausentismos_mes',
			'ausentismos_mes_anterior',
			'ausentismos_mes_anio_anterior',
			'ausentismos_anual',

			'nomina_actual',
			'nomina_mes_anterior',
			'nomina_mes_anio_anterior',
			'nomina_promedio_actual',

			'cant_dias_mes',
			'cant_dias_mes_anterior',
			'cant_dias_mes_anio_anterior',
			'cant_dias_anio',

			'status'
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


}
