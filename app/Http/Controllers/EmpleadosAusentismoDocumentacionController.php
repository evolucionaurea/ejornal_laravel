<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\ClienteUser;
use App\Ausentismo;
use App\AusentismoDocumentacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class EmpleadosAusentismoDocumentacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        'institucion' => 'required',
        'medico' => 'required',
        'diagnostico' => 'required',
        'fecha_documento' => 'required'
      ]);

      // Si viene de Extension de Ausentismo (icono listado de ausentismos), validar fechas
      if (isset($request->fecha_final) && !empty($request->fecha_final)) {
        $ausentismo = Ausentismo::findOrFail($request->id_ausentismo);
        $fecha_final = Carbon::createFromFormat('d/m/Y', $request->fecha_final);
        if ($fecha_final->lessThan($ausentismo->fecha_inicio)) {
          return back()->with('error', 'La fecha final es menor a la de inicio');
        }
        if ($ausentismo->fecha_regreso_trabajar != null) {
          if ($fecha_final->greaterThan($ausentismo->fecha_regreso_trabajar)) {
            return back()->with('error', 'La fecha final es mayor a la de regreso');
          }
        }
      }

      $fecha_documento = Carbon::createFromFormat('d/m/Y', $request->fecha_documento);
      // Carbon\Carbon::createFromFormat('d/m/Y', '10/01/2019')->toDateTimeString();

        // Si hay un archivo adjunto se va a guardar todo
        if ($request->hasFile('archivo') && $request->file('archivo') > 0) {

          //Guardar en base AusentismoDocumentacion
          $documentacion = new AusentismoDocumentacion();
          $documentacion->id_ausentismo = $request->id_ausentismo;
          $documentacion->institucion = $request->institucion;
          $documentacion->medico = $request->medico;
          if (isset($request->matricula_provincial) && !empty($request->matricula_provincial)) {
            $documentacion->matricula_provincial = $request->matricula_provincial;
          }else {
            $documentacion->matricula_provincial = null;
          }
          if (isset($request->matricula_nacional) && !empty($request->matricula_nacional)) {
            $documentacion->matricula_nacional = $request->matricula_nacional;
          }else {
            $documentacion->matricula_nacional = null;
          }
          if (isset($request->observaciones) && !empty($request->observaciones)) {
            $documentacion->observaciones = $request->observaciones;
          }else {
            $documentacion->observaciones = null;
          }
          $documentacion->fecha_documento = $fecha_documento;
          $documentacion->diagnostico = $request->diagnostico;
          $archivo = $request->file('archivo');
          $nombre = $archivo->getClientOriginalName();
          $documentacion->archivo = $nombre;
          $documentacion->user = auth()->user()->nombre;
          $documentacion->save();

          Storage::disk('local')->put('documentacion_ausentismo/'.$documentacion->id, $archivo);


          // Completar en base el hash del archivo guardado
          $documentacion = AusentismoDocumentacion::findOrFail($documentacion->id);
          $documentacion->hash_archivo = $archivo->hashName();
          $documentacion->save();

          // Si es una documentacion de ausentismo extendida (se carga en el listado de ausentismos)
          if (isset($request->fecha_final) && !empty($request->fecha_final)) {
            $ausentismo->fecha_final = $fecha_final;
            $ausentismo->save();
          }


        }else {
          return back()->with('error', 'Debes adjuntar un archivo');
        }

        return redirect('empleados/documentaciones/'.$request->id_ausentismo)->with('success', 'Guardado con éxito');

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

      $documentacion_ausentismo = AusentismoDocumentacion::where('id_ausentismo', $id)->get();

      $clientes = ClienteUser::join('clientes', 'cliente_user.id_cliente', 'clientes.id')
      ->where('cliente_user.id_user', '=', auth()->user()->id)
      ->select('clientes.nombre', 'clientes.id')
      ->get();

      return view('empleados.ausentismos.documentaciones', compact('ausencia', 'clientes', 'documentacion_ausentismo'));

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

        $validatedData = $request->validate([
          'institucion' => 'required',
          'medico' => 'required',
          'diagnostico' => 'required'
        ]);

        // Si hay un archivo adjunto se va a guardar todo
        if ($request->hasFile('archivo') && $request->file('archivo') > 0) {

          //Actualizar en base
          $documentacion = AusentismoDocumentacion::findOrFail($request->id_doc);
          $documentacion->institucion = $request->institucion;
          $documentacion->medico = $request->medico;
          if (isset($request->matricula_provincial) && !empty($request->matricula_provincial)) {
            $documentacion->matricula_provincial = $request->matricula_provincial;
          }else {
            $documentacion->matricula_provincial = null;
          }
          if (isset($request->matricula_nacional) && !empty($request->matricula_nacional)) {
            $documentacion->matricula_nacional = $request->matricula_nacional;
          }else {
            $documentacion->matricula_nacional = null;
          }
          if (isset($request->observaciones) && !empty($request->observaciones)) {
            $documentacion->observaciones = $request->observaciones;
          }else {
            $documentacion->observaciones = null;
          }
          $documentacion->diagnostico = $request->diagnostico;
          $archivo = $request->file('archivo');
          $nombre = $archivo->getClientOriginalName();
          $documentacion->archivo = $nombre;
          $documentacion->user = auth()->user()->nombre;
          $documentacion->save();

          $ruta = storage_path("app/documentacion_ausentismo/{$documentacion->id}");
          $ruta_archivo = storage_path("app/documentacion_ausentismo/{$documentacion->id}/{$documentacion->hash_archivo}");
          unlink($ruta_archivo);
          rmdir($ruta);

          Storage::disk('local')->put('documentacion_ausentismo/'.$documentacion->id, $archivo);


          // Completar en base el hash del archivo guardado
          $documentacion = AusentismoDocumentacion::findOrFail($documentacion->id);
          $documentacion->hash_archivo = $archivo->hashName();
          $documentacion->save();

        }else {

          //Actualizar en base
          $documentacion = AusentismoDocumentacion::findOrFail($request->id_doc);
          $documentacion->institucion = $request->institucion;
          $documentacion->medico = $request->medico;
          if (isset($request->matricula_provincial) && !empty($request->matricula_provincial)) {
            $documentacion->matricula_provincial = $request->matricula_provincial;
          }else {
            $documentacion->matricula_provincial = null;
          }
          if (isset($request->matricula_nacional) && !empty($request->matricula_nacional)) {
            $documentacion->matricula_nacional = $request->matricula_nacional;
          }else {
            $documentacion->matricula_nacional = null;
          }
          if (isset($request->observaciones) && !empty($request->observaciones)) {
            $documentacion->observaciones = $request->observaciones;
          }else {
            $documentacion->observaciones = null;
          }
          $documentacion->diagnostico = $request->diagnostico;
          $documentacion->user = auth()->user()->nombre;
          $documentacion->save();

        }

        return back()->with('success', 'Documentación actualizada con éxito');


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


    public function descargar_archivo($id)
    {

      $ausentismo_documentacion = AusentismoDocumentacion::find($id);
      $ruta = storage_path("app/documentacion_ausentismo/{$ausentismo_documentacion->id}/{$ausentismo_documentacion->hash_archivo}");
      return response()->download($ruta);
      return back();

    }


    public function getDocumentacion($id)
    {
      $doc_ausentismo = AusentismoDocumentacion::find($id);
      return response()->json($doc_ausentismo);
    }


    public function validarMatricula(Request $request)
    {

      if ($request->matricula == '' || !isset($request->matricula) || empty($request->matricula)) {
        return response()->json(
          ['mensaje' => 'Debes ingresar un valor para validar una matrícula']
        );
      }

      $client = new \GuzzleHttp\Client();
      $response = $client->request('GET', "https://sisa.msal.gov.ar/sisa/services/rest/profesional/obtener", [
          "query" => [
              "usuario"       => "jrpichot",
              "clave" => "JavierPichot00",
              "nombre"               => "Juan",
              "apellido"                 => "lopez",
              "codigo"            => "511",
              "nrodoc"           => "5050",
          ],
      ]);

      dump($response->getBody());
      return $response->getBody();

    }



}
