<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Fichada;
use Carbon\Carbon;
use DateTime;

class AdminFichadasController extends Controller
{

  public function fichadas()
  {
    // $fichadas = Fichada::join('users', 'fichadas.id_user', 'users.id')
    // ->join('clientes', 'fichadas.id_cliente', 'clientes.id')
    // ->select('users.nombre', 'users.estado', DB::raw('clientes.nombre cliente'), 'fichadas.horario_ingreso', 'fichadas.horario_egreso', 'fichadas.created_at')
    // ->get();

    $results =  Fichada::join('users', 'fichadas.id_user', 'users.id')
    ->join('clientes', 'fichadas.id_cliente', 'clientes.id')
    ->select('fichadas.*', DB::raw('clientes.nombre cliente'), DB::raw('users.nombre user'))
    ->orderBy('fichadas.id_user', 'desc')
    ->orderBy('fichadas.created_at', 'desc')
    ->get();

    $fichadas = [];
    foreach ($results as $key => $result) {

        $egreso_hallado = null;
        $ingreso_hallago = null;

        if ($result->horario_ingreso != null) {
          $ingreso_hallago = $result->created_at;
          if (isset($results[$key-1]->id_user) && $results[$key-1]->id_user == $result->id_user) {
            // Cargar el egreso
            $egreso_hallado = $results[$key-1]->created_at;
          }else {
            $egreso_hallado = null;
          }

          $fecha_ingreso = Carbon::createFromFormat('Y-m-d H:i:s', $ingreso_hallago)->format('d-m-Y H:i:s');

          if ($egreso_hallado != null) {
            $fecha_egreso = Carbon::createFromFormat('Y-m-d H:i:s', $egreso_hallado)->format('d-m-Y H:i:s');
          }

          $fichadas[] = [
            'id' => $result->id,
            'fecha_actual' => $result->fecha_actual,
            'created_at' => $result->created_at,
            'cliente' => $result->cliente,
            'id_user' => $result->id_user,
            'user' => $result->user,
            'fecha_ingreso' => $fecha_ingreso,
            'fecha_egreso' => ($egreso_hallado != null) ? $fecha_egreso : 'Aún trabajando'
          ];

        }

      }

    return view('admin.fichadas', compact('fichadas'));
  }

}
