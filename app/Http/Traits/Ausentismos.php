<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use App\Ausentismo;
use App\Cliente;
use App\Nomina;
use App\NominaHistorial;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

trait Ausentismos {

	/* Búsqueda para DataTable */
	public function searchAusentismos($id_cliente=null,Request $request)
	{

		//dd($request->toArray());

		$now = CarbonImmutable::now();
		DB::statement("SET time_zone = '-03:00'");

		DB::enableQueryLog();


		$today = CarbonImmutable::now();
		$inicio_mes = $today->startOfMonth()->format('Y-m-d');

		$query = Ausentismo::join('nominas','nominas.id','=','ausentismos.id_trabajador')
			/*whereHas('trabajador',function($query) use ($id_cliente){
				return $query->where('id_cliente',$id_cliente);
			})*/
			->join('ausentismo_tipo','ausentismo_tipo.id','=','ausentismos.id_tipo')
			->select(
				'ausentismos.*',

				'nominas.nombre as trabajador_nombre',
				'nominas.legajo as trabajador_legajo',
				'nominas.dni as trabajador_dni',
				'nominas.sector as trabajador_sector',
				'nominas.id_cliente as trabajador_cliente',
				'nominas.estado as trabajador_estado',

				'ausentismo_tipo.nombre as ausentismo_tipo',
				'ausentismo_tipo.incluir_indice as incluir_indice'
			)
			->addSelect(DB::raw(
				"(DATEDIFF(
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
				))+1 as dias_mes_actual,

				(DATEDIFF(
					IFNULL(
						fecha_final,
						DATE(NOW())
					),
					fecha_inicio
				))+1 as total_dias,

				IF(
					IFNULL(fecha_final,DATE(NOW())) >= DATE(NOW()),
					1,
					0
				) ausente"
			))
			->where('ausentismos.id_cliente',$id_cliente)
			->withCount('documentaciones')
			->withCount('comunicaciones')
			->where('nominas.deleted_at',null);

		$total = $query->count();


		if(isset($request->search)){
			$query->where(function($query) use($request) {
				$filtro = '%'.$request->search.'%';
				$query->where('nominas.nombre','like',$filtro)
					->orWhere('nominas.email','like',$filtro)
					->orWhere('nominas.legajo','like',$filtro)
					->orWhere('nominas.dni','like',$filtro)
					->orWhere('nominas.telefono','like',$filtro);
			});
		}



		if($request->ausentes=='hoy'){
			$query->where(function($query) use ($now) {
				$query
					->where('ausentismos.fecha_final',null)
					->orWhere('ausentismos.fecha_final','>=',$now->format('Y-m-d'));
			});
			$query->where('ausentismos.fecha_inicio','<=',$now->format('Y-m-d'));
			$query->where('ausentismo_tipo.incluir_indice',1);
		}


		if($request->ausentes=='mes-actual'){

			$query->where(function($query) use ($now){

				$query->whereBetween('fecha_inicio',[$now->startOfMonth(),$now])
				/*$query->where(function($query) use ($now){
					$query
						->whereBetween('fecha_inicio',[$now->startOfMonth(),$now]);
						->whereDate('fecha_inicio','<=',$now)
						->where(function($query) use($now){
							$query->whereDate('fecha_final','<=',$now)
								->orWhere('fecha_final',null);
						});
				})*/
				->orWhere(function($query) use ($now){
					// los que siguen ausentes fuera del mes actual
					$query
						->where('fecha_inicio','<=',$now->startOfMonth())
						->where(function($query) use($now){
							$query->whereDate('fecha_final','>=',$now->startOfMonth())
								->orWhere('fecha_final',null);
						});
				});

			});
			/*$query
				->whereDate('fecha_inicio','<=',$now)
				->whereDate('fecha_final','>=',$now);*/

		}

		if($request->ausentes=='mes-anterior'){

			// Fecha del primer día del mes anterior
			$startOfMonth = $now->startOfMonth()->subMonth()->startOfMonth();

			// Fecha del último día del mes anterior
			$endOfMonth = $now->startOfMonth()->subMonth()->endOfMonth();

			$query->where(function($query) use ($startOfMonth, $endOfMonth){
				$query
					->whereBetween('fecha_inicio',[$startOfMonth, $endOfMonth])
					->orWhere(function($query) use ($startOfMonth, $endOfMonth){
						$query
							->where('fecha_inicio','<', $startOfMonth)
							->where(function($query) use ($startOfMonth, $endOfMonth){
								$query
									->where('fecha_final','>=', $startOfMonth)
									->orWhere('fecha_final', null);
							});
					});
			});

		}


		if($request->ausentes=='mes-anio-anterior'){

			$query->where(function($query) use ($now){
				$query->where(function($query) use ($now){
					$query
						->whereBetween('fecha_inicio',[$now->startOfMonth()->subYear(),$now->startOfMonth()->subYear()->endOfMonth()])
						->where(function($query) use ($now){
							$query
								->where('fecha_final','<=',$now->startOfMonth()->subMonth()->endOfMonth())
								->orWhere('fecha_final',null);
						});
				})
				// los que estuvieron ausentes durante el curso de ese mes pero iniciaron ausentismo antes de ese mes y volvieron dsp
				->orWhere(function($query) use ($now){
					$query->where('fecha_inicio','<',$now->startOfMonth()->subYear())
						->where(function($query) use ($now){
							$query
								->where('fecha_final','>=',$now->startOfMonth()->subYear()->endOfMonth())
								->orWhere('fecha_final',null);
						});
				});
			});


		}
		if($request->ausentes=='anio-actual'){

			$query->where(function($query) use ($now){
				$query->whereDate('fecha_inicio','>=',$now->startOfYear())
				/*$query->where(function($query) use ($now){
					$query
						->whereBetween('fecha_inicio',[$now->startOfYear(),$now])
						->where(function($query) use ($now){
							$query
								->where('fecha_final','<=',$now)
								->orWhere('fecha_final',null);
						});
				})*/
				// los que estuvieron ausentes durante el curso de ese mes pero iniciaron ausentismo antes de ese mes y volvieron dsp
				->orWhere(function($query) use ($now){
					$query
						->where('fecha_inicio','<=',$now->startOfYear())
						->where(function($query) use ($now){
							$query
								->where('fecha_final','>',$now->startOfYear())
								->orWhere('fecha_final',null);
						});
				});
			});
		}


		if($request->ausentes=='mes-actual-carga'){
			$query->where('ausentismos.fecha_inicio','>=',$now->startOfMonth());
			$query->where('ausentismos.fecha_inicio','<=',$now->endOfMonth());
		}

		if($request->ausentes=='mes-anterior-carga'){
			$query->where('ausentismos.fecha_inicio','>=',$now->subMonth()->startOfMonth());
			$query->where('ausentismos.fecha_inicio','<=',$now->subMonth()->endOfMonth());
		}



		if($request->estado){
			$query->where('nominas.estado','=',$request->estado=='activo' ? 1 : 0);
		}


		if($request->from) {
			$from = Carbon::createFromFormat('d/m/Y',$request->from);
			$query->whereRaw("'{$from->toDateString()}' BETWEEN fecha_inicio AND IFNULL(fecha_final,NOW())");
			//$query->where(function($query) use ($request){
				//->where('fecha_inicio','>=',Carbon::createFromFormat('d/m/Y', $from))
				// los que siguen ausentes fuera rango actual
				/*->orWhere(function($query) use ($request) {
						$query->where('fecha_regreso_trabajar','>=',Carbon::createFromFormat('d/m/Y', $request->from))
							->orWhere('fecha_regreso_trabajar',null);
				});*/
			//});
		}
		if($request->to) {
			$from = $request->from ? Carbon::createFromFormat('d/m/Y',$request->from) : $now;
			$to = Carbon::createFromFormat('d/m/Y',$request->to);
			$query->whereRaw("IFNULL(fecha_final,DATE(NOW())) BETWEEN '{$from->toDateString()}' AND '{$to->toDateString()}'");
			/*$query->where(function($query) use ($request){
				$query->where('fecha_regreso_trabajar','<=',$to)
					->orWhere('fecha_regreso_trabajar',null);
			});*/
		}

		if($request->tipo) $query->where('id_tipo',$request->tipo);


		if($request->order){
			$sort = $request->columns[$request->order[0]['column']]['name'];
			$dir  = $request->order[0]['dir'];
			$query->orderBy($sort,$dir);
		}



		$total_filtered = $query->count();


		return [
			'draw'=>$request->draw,
			'recordsTotal'=>$total,
			'recordsFiltered'=>$total_filtered,
			'data'=>$query->skip($request->start)->take($request->length)->get(),
			'request'=>$request->all(),
			'queries'=>DB::getQueryLog()
		];

	}


	public function exportAusentismos($id_cliente=null,Request $request)
	{

		if(!$id_cliente) dd('Faltan parámetros');
		$cliente = Cliente::findOrFail($id_cliente);

		//$request->search = ['value'=>null];
		$request->start = 0;
		$request->length = 5000;
		$results = $this->searchAusentismos($id_cliente,$request);
		$ausentismos = $results['data'];
		//dd($ausentismos[0]->fecha_inicio->format('d/m/Y'));

		$now = Carbon::now();
		$file_name = 'ausentismos-'.Str::slug($cliente->nombre).'-'.$now->format('YmdHis').'.csv';


		$fp = fopen('php://memory', 'w');
		fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
		fputcsv($fp,[
			'Trabajador',
			'DNI',
			'Legajo',
			'Sector',
			'Tipo',
			'Fecha Inicio',
			'Fecha Final',
			'Fecha en que Regresó',
			'Comentario',
			'Hoy ('.$now->format('d/m/Y').')'
		],';');

		foreach($ausentismos as $ausentismo){
			$hoy = '';
			/*if(is_null($ausentismo->fecha_final)){
				$hoy = 'ausente';
			}else{
				if($ausentismo->fecha_final >= $now->format) $hoy = 'ausente';
			}*/

			if($ausentismo->ausente===1 && $ausentismo->incluir_indice===1) $hoy = 'ausente';
			if($ausentismo->ausente===1 && $ausentismo->incluir_indice===0) $hoy = 'vigente';

			fputcsv($fp,[
				$ausentismo->trabajador_nombre,
				$ausentismo->trabajador_dni,
				$ausentismo->trabajador_legajo,
				$ausentismo->trabajador_sector,
				$ausentismo->ausentismo_tipo,
				$ausentismo->fecha_inicio->format('d/m/Y'),
				$ausentismo->fecha_final ? $ausentismo->fecha_final->format('d/m/Y') : '[no cargada]',
				$ausentismo->fecha_regreso ?? ($ausentismo->fecha_final ? $ausentismo->fecha_final->format('d/m/Y') : '[no cargada]'),
				str_replace(["\n", "\r"], " ", $ausentismo->comentario),
				$hoy
			],';');
		}
		fseek($fp, 0);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="'.$file_name.'";');
		fpassthru($fp);

		return;

	}

	
	public function ausentismosAjax($id_cliente = null)
{
    setlocale(LC_TIME, 'Spanish');
    $today = CarbonImmutable::now();

    // --- NOMINAS (mes actual) ----------------------------------------------
    $q_nomina = NominaHistorial::where('year_month', $today->format('Ym'))
        ->where('cliente_id', $id_cliente)
        ->first();

    if (!$q_nomina) {
        \Artisan::call('db:seed', [
            '--class' => 'NominaHistorialSeeder',
            '--force' => true
        ]);

        $q_nomina = NominaHistorial::where('year_month', $today->format('Ym'))
            ->where('cliente_id', $id_cliente)
            ->first();
    }

    // Evitar nulls en PHP 7.4
    $nomina_actual = (int) (optional($q_nomina)->cantidad ?? 0);

    $nomina_mes_anterior = (int) (
        optional(
            NominaHistorial::where('year_month', $today->firstOfMonth()->subMonth()->format('Ym'))
                ->where('cliente_id', $id_cliente)
                ->first()
        )->cantidad ?? $nomina_actual
    );

    $nomina_mes_anio_anterior = (int) (
        optional(
            NominaHistorial::where('year_month', $today->firstOfMonth()->subYear()->format('Ym'))
                ->where('cliente_id', $id_cliente)
                ->first()
        )->cantidad ?? $nomina_actual
    );

    // Promedio del año actual (usar avg() evita alias y nulos)
    $avg_nomina = NominaHistorial::whereBetween('year_month', [
            $today->startOfYear()->format('Ym'),
            $today->format('Ym')
        ])
        ->where('cliente_id', $id_cliente)
        ->avg('cantidad');

    $nomina_promedio_actual = (int) ceil($avg_nomina ?? 0);


    // --- AUSENTISMOS MES A MES (año actual) --------------------------------
    $months_current_year = CarbonPeriod::create($today->startOfYear(), '1 month', $today);
    $indices_mes_a_mes = [];

    foreach ($months_current_year as $date) {
        $date_immutable = CarbonImmutable::create($date->format('Y'), $date->format('m'), 1);

        $inicio_mes = $date_immutable->firstOfMonth();
        $fin_mes   = ($today->format('Ym') === $date->format('Ym')) ? $today : $date_immutable->lastOfMonth();

        $inicio_mes_formatted = $inicio_mes->format('Y-m-d');
        $fin_mes_formatted    = $fin_mes->format('Y-m-d');

        // Nómina del mes iterado (fallback a 0 si no existe)
        $nomina_mes = (int) (
            optional(
                NominaHistorial::select('cantidad', 'year_month')
                    ->where('year_month', $date->format('Ym'))
                    ->where('cliente_id', $id_cliente)
                    ->first()
            )->cantidad ?? 0
        );

        // Cálculo de días del mes (defensivo ante null)
        $ausentismos = Ausentismo::selectRaw("
                SUM(
                    ABS(DATEDIFF(
                        IF(
                            IFNULL(fecha_final, '{$fin_mes_formatted}') >= '{$fin_mes_formatted}',
                            '{$fin_mes_formatted}',
                            fecha_final
                        ),
                        IF(
                            fecha_inicio >= '{$inicio_mes_formatted}',
                            fecha_inicio,
                            '{$inicio_mes_formatted}'
                        )
                    )) + 1
                ) AS dias
            ")
            ->where(function ($query) use ($inicio_mes, $fin_mes) {
                $query->whereBetween('fecha_inicio', [$inicio_mes, $fin_mes])
                      ->orWhere(function ($query) use ($inicio_mes) {
                          $query->where('fecha_inicio', '<', $inicio_mes)
                                ->where(function ($query) use ($inicio_mes) {
                                    $query->where('fecha_final', '>=', $inicio_mes)
                                          ->orWhereNull('fecha_final');
                                });
                      });
            })
            ->whereIn('id_trabajador', function ($query) {
                $query->select('id')
                    ->from('nominas')
                    ->whereNull('deleted_at')
                    ->where('estado', 1);
            })
            ->whereHas('tipo', function ($query) {
                $query->where('incluir_indice', 1)
                      ->where('nombre', 'NOT LIKE', '%incidente%')
                      ->where(function ($query) {
                          $query->where('agrupamiento', '!=', 'ART')
                                ->orWhereNull('agrupamiento');
                      });
            })
            ->where('ausentismos.id_cliente', $id_cliente)
            ->first();

        $dias_mes = (int) (optional($ausentismos)->dias ?? 0);
        $dias_en_mes = (int) $fin_mes->format('d');

        $indices_mes_a_mes[] = [
            'dias'       => $dias_mes,
            'month'      => Str::ucfirst($date->formatLocalized('%B')),
            'indice'     => $nomina_mes > 0 ? ($dias_mes / ($nomina_mes * $dias_en_mes) * 100) : 0,
            'nomina'     => $nomina_mes,
            'inicio_mes' => $inicio_mes_formatted,
            'fin_mes'    => $fin_mes_formatted,
        ];
    }


    // --- MES ACTUAL ---------------------------------------------------------
    $inicio_mes = $today->startOfMonth()->format('Y-m-d');

    $ausentismos_mes = Ausentismo::selectRaw("
            SUM(
                ABS(DATEDIFF(
                    IF(
                        IFNULL(fecha_final, DATE(NOW())) >= DATE(NOW()),
                        DATE(NOW()),
                        fecha_final
                    ),
                    IF(
                        fecha_inicio >= '{$inicio_mes}',
                        fecha_inicio,
                        '{$inicio_mes}'
                    )
                )) + 1
            ) AS dias,
            COUNT(*) AS total,
            id_tipo
        ")
        ->with(['tipo' => function ($query) {
            $query->select('id', 'nombre', 'color');
        }])
        ->where(function ($query) use ($today) {
            $query->whereBetween('fecha_inicio', [$today->startOfMonth(), $today])
                ->orWhere(function ($query) use ($today) {
                    $query->where('fecha_inicio', '<', $today->startOfMonth())
                          ->where(function ($query) use ($today) {
                              $query->where('fecha_final', '>=', $today->startOfMonth())
                                    ->orWhereNull('fecha_final');
                          });
                });
        })
        ->whereIn('id_trabajador', function ($query) {
            $query->select('id')
                ->from('nominas')
                ->where('estado', 1)
                ->whereNull('deleted_at');
        })
        ->whereHas('tipo', function ($query) {
            $query->where('incluir_indice', 1)
                  ->where('nombre', 'NOT LIKE', '%incidente%')
                  ->where(function ($query) {
                      $query->where('agrupamiento', '!=', 'ART')
                            ->orWhereNull('agrupamiento');
                  });
        })
        ->where('ausentismos.id_cliente', $id_cliente)
        ->groupBy('id_tipo')
        ->orderBy('dias', 'desc')
        ->get();


    // --- MES PASADO ---------------------------------------------------------
    $inicio_mes_pasado = $today->subMonth()->startOfMonth()->format('Y-m-d');
    $fin_mes_pasado    = $today->subMonth()->endOfMonth()->format('Y-m-d');

    $ausentismos_mes_anterior = Ausentismo::selectRaw("
            SUM(
                ABS(DATEDIFF(
                    IF(
                        fecha_final < '{$fin_mes_pasado}',
                        fecha_final,
                        '{$fin_mes_pasado}'
                    ),
                    IF(
                        fecha_inicio < '{$inicio_mes_pasado}',
                        '{$inicio_mes_pasado}',
                        fecha_inicio
                    )
                )) + 1
            ) AS dias,
            COUNT(*) AS total,
            id_tipo
        ")
        ->with(['tipo' => function ($query) {
            $query->select('id', 'nombre', 'color');
        }])
        ->where(function ($query) use ($today) {
            $query->whereBetween('fecha_inicio', [$today->startOfMonth()->subMonth(), $today->startOfMonth()->subMonth()->endOfMonth()])
                  ->orWhere(function ($query) use ($today) {
                      $query->where('fecha_inicio', '<', $today->startOfMonth()->subMonth())
                            ->where(function ($query) use ($today) {
                                $query->where('fecha_final', '>=', $today->startOfMonth()->subMonth())
                                      ->orWhereNull('fecha_final');
                            });
                  });
        })
        ->whereIn('id_trabajador', function ($query) {
            $query->select('id')
                ->from('nominas')
                ->where('estado', 1)
                ->whereNull('deleted_at');
        })
        ->whereHas('tipo', function ($query) {
            $query->where('incluir_indice', 1)
                  ->where('nombre', 'NOT LIKE', '%incidente%')
                  ->where(function ($query) {
                      $query->where('agrupamiento', '!=', 'ART')
                            ->orWhereNull('agrupamiento');
                  });
        })
        ->where('ausentismos.id_cliente', $id_cliente)
        ->groupBy('id_tipo')
        ->get();


    // --- MES AÑO ANTERIOR ---------------------------------------------------
    $inicio_mes_anio_anterior = $today->subYear()->startOfMonth()->format('Y-m-d');
    $fin_mes_anio_anterior    = $today->subYear()->endOfMonth()->format('Y-m-d');

    $ausentismos_mes_anio_anterior = Ausentismo::selectRaw("
            SUM(
                ABS(DATEDIFF(
                    IF(
                        fecha_final < '{$fin_mes_anio_anterior}',
                        fecha_final,
                        '{$fin_mes_anio_anterior}'
                    ),
                    IF(
                        fecha_inicio < '{$inicio_mes_anio_anterior}',
                        '{$inicio_mes_anio_anterior}',
                        fecha_inicio
                    )
                )) + 1
            ) AS dias,
            COUNT(*) AS total,
            id_tipo
        ")
        ->with(['tipo' => function ($query) {
            $query->select('id', 'nombre', 'color');
        }])
        ->where(function ($query) use ($today) {
            $query->where(function ($query) use ($today) {
                    $query->whereBetween('fecha_inicio', [$today->startOfMonth()->subYear(), $today->startOfMonth()->subYear()->endOfMonth()])
                          ->where(function ($query) use ($today) {
                              $query->where('fecha_final', '<=', $today->startOfMonth()->subMonth()->endOfMonth())
                                    ->orWhereNull('fecha_final');
                          });
                })
                ->orWhere(function ($query) use ($today) {
                    $query->where('fecha_inicio', '<', $today->startOfMonth()->subYear())
                          ->where(function ($query) use ($today) {
                              $query->where('fecha_final', '>=', $today->startOfMonth()->subYear())
                                    ->orWhereNull('fecha_final');
                          });
                });
        })
        ->whereIn('id_trabajador', function ($query) {
            $query->select('id')
                ->from('nominas')
                ->where('estado', 1)
                ->whereNull('deleted_at');
        })
        ->whereHas('tipo', function ($query) {
            $query->where('incluir_indice', 1)
                  ->where('nombre', 'NOT LIKE', '%incidente%')
                  ->where(function ($query) {
                      $query->where('agrupamiento', '!=', 'ART')
                            ->orWhereNull('agrupamiento');
                  });
        })
        ->where('ausentismos.id_cliente', $id_cliente)
        ->groupBy('id_tipo')
        ->get();


    // --- AÑO ACTUAL (acumulado) --------------------------------------------
    $inicio_anio = $today->format('Y-01-01');

    $ausentismos_anual = Ausentismo::selectRaw("
            SUM(
                ABS(DATEDIFF(
                    IF(
                        IFNULL(fecha_final, DATE(NOW())) < DATE(NOW()),
                        fecha_final,
                        DATE(NOW())
                    ),
                    IF(
                        fecha_inicio < '{$inicio_anio}',
                        '{$inicio_anio}',
                        fecha_inicio
                    )
                )) + 1
            ) AS dias,
            COUNT(*) AS total,
            id_tipo
        ")
        ->with(['tipo' => function ($query) {
            $query->select('id', 'nombre', 'color');
        }])
        ->where(function ($query) use ($today) {
            $query->whereDate('fecha_inicio', '>=', $today->startOfYear())
                  ->orWhere(function ($query) use ($today) {
                      $query->where('fecha_inicio', '<', $today->startOfYear())
                            ->where(function ($query) use ($today) {
                                $query->where('fecha_final', '>=', $today->startOfYear())
                                      ->orWhereNull('fecha_final');
                            });
                  });
        })
        ->whereIn('id_trabajador', function ($query) {
            $query->select('id')
                ->from('nominas')
                ->where('estado', 1)
                ->whereNull('deleted_at');
        })
        ->whereHas('tipo', function ($query) {
            $query->where('incluir_indice', 1)
                  ->where('nombre', 'NOT LIKE', '%incidente%')
                  ->where(function ($query) {
                      $query->where('agrupamiento', '!=', 'ART')
                            ->orWhereNull('agrupamiento');
                  });
        })
        ->where('ausentismos.id_cliente', $id_cliente)
        ->groupBy('id_tipo')
        ->orderBy('dias', 'desc')
        ->get();


    // --- Extras de retorno --------------------------------------------------
    $status = 'ok';
    $cant_dias_mes              = (int) $today->format('d');
    $cant_dias_mes_anterior     = (int) $today->firstOfMonth()->subMonth()->endOfMonth()->format('d');
    $cant_dias_mes_anio_anterior= (int) $today->firstOfMonth()->subYear()->endOfMonth()->format('d');
    $cant_dias_anio             = (int) $today->dayOfYear();

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

        'months_current_year',

        'status'
    );
}



}