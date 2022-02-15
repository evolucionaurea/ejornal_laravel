<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ClienteUser;
use App\Nomina;
use App\Cliente;
use App\Ausentismo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
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


      $inicio_mes_pasado = new Carbon('first day of last month');
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

      $ausentismos_mes_pasado = Ausentismo::join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
      ->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
      ->where('nominas.id_cliente', auth()->user()->id_cliente_relacionar)
      ->whereDate('ausentismos.fecha_inicio', '>=', $inicio_mes_pasado)
      ->whereDate('ausentismos.fecha_final', '<=', $final_mes_pasado)
      ->select('ausentismos.*', 'nominas.nombre', 'nominas.email', 'nominas.telefono', 'nominas.dni', 'nominas.estado', DB::raw('ausentismo_tipo.nombre nombre_ausentismo'))
      ->count();

      $ausentismos_mes_actual = Ausentismo::join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
      ->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
      ->where('nominas.id_cliente', auth()->user()->id_cliente_relacionar)
      ->whereDate('ausentismos.fecha_inicio', '>=', $inicio_mes_actual)
      ->whereDate('ausentismos.fecha_final', '<=', $final_mes_actual)
      ->select('ausentismos.*', 'nominas.nombre', 'nominas.email', 'nominas.telefono', 'nominas.dni', 'nominas.estado', DB::raw('ausentismo_tipo.nombre nombre_ausentismo'))
      ->count();

      $accidentes_mes_pasado = Ausentismo::join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
      ->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
      ->where('ausentismo_tipo.id', 12)
      ->where('nominas.id_cliente', auth()->user()->id_cliente_relacionar)
      ->whereDate('ausentismos.fecha_inicio', '>=', $inicio_mes_pasado)
      ->whereDate('ausentismos.fecha_final', '<=', $final_mes_pasado)
      ->select('ausentismos.*', 'nominas.nombre', 'nominas.email', 'nominas.telefono', 'nominas.dni', 'nominas.estado', DB::raw('ausentismo_tipo.nombre nombre_ausentismo'))
      ->count();

      $accidentes_mes_actual = Ausentismo::join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
      ->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
      ->where('ausentismo_tipo.id', 12)
      ->where('nominas.id_cliente', auth()->user()->id_cliente_relacionar)
      ->whereDate('ausentismos.fecha_inicio', '>=', $inicio_mes_actual)
      ->whereDate('ausentismos.fecha_inicio', '<=', $final_mes_actual)
      ->select('ausentismos.*', 'nominas.nombre', 'nominas.email', 'nominas.telefono', 'nominas.dni', 'nominas.estado', DB::raw('ausentismo_tipo.nombre nombre_ausentismo'))
      ->count();


      $ausentismos_top_10 = Ausentismo::join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
      ->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
      ->where('nominas.id_cliente', '=', auth()->user()->id_cliente_relacionar)
      ->select('ausentismo_tipo.nombre', 'ausentismos.fecha_inicio', 'ausentismos.fecha_regreso_trabajar',
      DB::raw('nominas.nombre trabajador'),
      DB::raw('YEAR(ausentismos.fecha_inicio) year, MONTH(ausentismos.fecha_inicio) month'))
      ->get();

      $array_top_10_ausentismos = [];

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
      $faltas_final = array_splice($faltas_array, 0, 10);

      // Tabla de cantidad de de veces que se cargó una falta para un trabajador //

      // Tabla Top 10 dias que falto una persona //
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
        $top_10_ausentismos = array_splice($array_top_10_ausentismos, 0, 10);
// Tabla Top 10 dias que falto una persona //

        $ausencia_covid = Ausentismo::join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
        ->where('nominas.id_cliente', auth()->user()->id_cliente_relacionar)
        ->where('ausentismos.fecha_regreso_trabajar', '!=', null)
        ->where('ausentismos.id_tipo', 8)
        ->orWhere('ausentismos.id_tipo', 9)
        ->whereDate('ausentismos.fecha_inicio', '>=', $fecha_actual)
        ->whereDate('ausentismos.fecha_final', '<=', $fecha_actual)
        ->whereDate('ausentismos.fecha_regreso_trabajar', '<=', $fecha_actual)
        ->select('ausentismos.*', 'nominas.nombre', 'nominas.email', 'nominas.telefono', 'nominas.dni', 'nominas.estado')
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
        }

      return view('clientes.resumen', compact('cliente', 'ausentismos_mes_pasado', 'ausentismos_mes_actual',
      'accidentes_mes_pasado', 'accidentes_mes_actual', 'top_10_ausentismos', 'faltas_final', 'ausencia_covid',
    'cant_vacunados_una_dosis', 'cant_vacunados_dos_dosis', 'cant_vacunados_tres_dosis'));

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
