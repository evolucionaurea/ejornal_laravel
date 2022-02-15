<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comunicacion;
use App\Cliente;
use App\ClienteUser;
use App\Ausentismo;
use App\AusentismoDocumentacion;
use App\TipoComunicacion;
use Carbon\Carbon;
use App\Nomina;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class EmpleadosComunicacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = ClienteUser::join('clientes', 'cliente_user.id_cliente', 'clientes.id')
        ->where('cliente_user.id_user', '=', auth()->user()->id)
        ->select('clientes.nombre', 'clientes.id')
        ->get();

        $comunicaciones = Comunicacion::join('ausentismos', 'comunicaciones.id_ausentismo', 'ausentismos.id')
        ->join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
        ->join('tipo_comunicacion', 'comunicaciones.id_tipo', 'tipo_comunicacion.id')
        ->where('nominas.id_cliente', auth()->user()->id_cliente_actual)
        ->select('nominas.nombre', 'nominas.email', DB::raw('tipo_comunicacion.nombre tipo'), 'comunicaciones.created_at', 'nominas.estado')
        ->get();

        return view('empleados.comunicaciones', compact('clientes', 'comunicaciones'));
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

      $validatedData = $request->validate([
        'descripcion' => 'required'
      ]);

      //Guardar en base Comunicaciones
      $comunicacion = new Comunicacion();
      $comunicacion->id_ausentismo = $request->id_ausentismo;
      $comunicacion->id_tipo = $request->id_tipo;
      $comunicacion->descripcion = $request->descripcion;
      $comunicacion->user = auth()->user()->nombre;
      $comunicacion->save();

      return redirect('empleados/comunicaciones/'.$request->id_ausentismo)->with('success', 'Comunicación guardada con éxito');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $ausencia = Ausentismo::join('nominas', 'ausentismos.id_trabajador', 'nominas.id')
      ->join('ausentismo_tipo', 'ausentismos.id_tipo', 'ausentismo_tipo.id')
      ->where('ausentismos.id', $id)
      ->where('nominas.id_cliente', auth()->user()->id_cliente_actual)
      ->select('nominas.nombre', 'nominas.email', 'nominas.estado', 'nominas.telefono', DB::raw('ausentismo_tipo.nombre nombre_ausentismo'), 'ausentismos.fecha_inicio', 'ausentismos.fecha_final', 'ausentismos.fecha_regreso_trabajar', 'ausentismos.archivo', 'ausentismos.id')
      ->first();

      $comunicaciones_ausentismo = Comunicacion::join('tipo_comunicacion', 'comunicaciones.id_tipo', 'tipo_comunicacion.id')
      ->where('id_ausentismo', $id)
      ->select('comunicaciones.id', 'tipo_comunicacion.nombre', 'comunicaciones.descripcion', 'comunicaciones.updated_at')
      ->get();

      $tipo_comunicaciones = TipoComunicacion::all();


      $clientes = ClienteUser::join('clientes', 'cliente_user.id_cliente', 'clientes.id')
      ->where('cliente_user.id_user', '=', auth()->user()->id)
      ->select('clientes.nombre', 'clientes.id')
      ->get();

      return view('empleados.comunicaciones.show', compact('ausencia', 'clientes', 'comunicaciones_ausentismo', 'tipo_comunicaciones'));

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


    public function tipo(Request $request)
    {

        $validatedData = $request->validate([
          'nombre' => 'required|string'
        ]);

        //Guardar en base
        $tipo_comunicacion = new TipoComunicacion();
        $tipo_comunicacion->nombre = $request->nombre;
        $tipo_comunicacion->save();

        return back()->with('success', 'Tipo de comunicación creado con éxito');
    }


    public function tipo_destroy($id_tipo)
    {

      $comunicacion = Comunicacion::where('id_tipo', $id_tipo)->get();

      if (!empty($comunicacion) && count($comunicacion) > 0) {
        return back()->with('error', 'Existen comunicaciones creadas con este tipo de comunicacion. No puedes eliminarla');
      }

        //Eliminar en base
        $tipo_comunicacion = TipoComunicacion::find($id_tipo)->delete();
        return back()->with('success', 'Tipo de comunicacion eliminada correctamente');
    }



    public function getComunicacion($id)
    {
      $comunicacion_de_ausentismo = Comunicacion::find($id);
      return response()->json($comunicacion_de_ausentismo);
    }




}
