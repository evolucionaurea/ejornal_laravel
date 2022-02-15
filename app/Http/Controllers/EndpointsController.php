<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Nomina;
use App\Ausentismo;
use App\Cliente;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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




}
