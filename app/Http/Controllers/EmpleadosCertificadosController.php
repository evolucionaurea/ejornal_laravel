<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ClienteUser;
use App\AusentismoDocumentacion;
use Illuminate\Support\Facades\DB;

class EmpleadosCertificadosController extends Controller
{

    public function listado()
    {
      $clientes = ClienteUser::join('clientes', 'cliente_user.id_cliente', 'clientes.id')
      ->where('cliente_user.id_user', '=', auth()->user()->id)
      ->select('clientes.nombre', 'clientes.id')
      ->get();

      $certificados = AusentismoDocumentacion::join('ausentismos', 'ausentismo_documentacion.id_ausentismo', 'ausentismos.id')
      ->join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
      ->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
      ->where('nominas.id_cliente', auth()->user()->id_cliente_actual)
      ->select('nominas.nombre', DB::raw('ausentismo_tipo.nombre tipo'), 'ausentismos.fecha_inicio',
      'ausentismos.fecha_final', 'ausentismos.fecha_regreso_trabajar', 'ausentismo_documentacion.medico',
      'ausentismo_documentacion.matricula_nacional', 'ausentismo_documentacion.institucion')
      ->get();

      return view('empleados.ausentismos.certificados', compact('clientes', 'certificados'));
    }


}
