<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\ClienteUser;
use App\TareaLiviana;
use App\TareaLivianaDocumentacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class EmpleadosTareasLivianasDocumentacion extends Controller
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
    
          $fecha_documento = Carbon::createFromFormat('d/m/Y', $request->fecha_documento);
    
            // Si hay un archivo adjunto se va a guardar todo
            if ($request->hasFile('archivo') && $request->file('archivo') > 0) {
    
              //Guardar en base TareaLivianaDocumentacion
              $documentacion = new TareaLivianaDocumentacion();
              $documentacion->id_tarea_liviana = $request->id_tarea_liviana;
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
    
              Storage::disk('local')->put('documentacion_tarea_liviana/'.$documentacion->id, $archivo);
    
    
              // Completar en base el hash del archivo guardado
              $documentacion = TareaLivianaDocumentacion::findOrFail($documentacion->id);
              $documentacion->hash_archivo = $archivo->hashName();
              $documentacion->save();
    
    
            }else {
              return back()->with('error', 'Debes adjuntar un archivo');
            }
    
            return redirect('empleados/documentaciones_livianas/'.$request->id_tarea_liviana)->with('success', 'Documentacion guardada con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // dd($id);
      $tarea_liviana = TareaLiviana::join('nominas', 'tareas_livianas.id_trabajador', 'nominas.id')
      ->join('tareas_livianas_tipos', 'tareas_livianas.id_tipo', 'tareas_livianas_tipos.id')
      ->where('tareas_livianas.id', $id)
      ->where('nominas.id_cliente', auth()->user()->id_cliente_actual)
      ->select('nominas.nombre', 'nominas.email', 'nominas.estado', 'nominas.telefono', 
      DB::raw('tareas_livianas_tipos.nombre nombre_tarea_liviana'), 'tareas_livianas.fecha_inicio', 
      'tareas_livianas.fecha_final', 'tareas_livianas.fecha_regreso_trabajar', 'tareas_livianas.archivo', 
      'tareas_livianas.id')
      ->first();
      $documentacion_tarea_liviana = TareaLivianaDocumentacion::where('id_tarea_liviana', $id)->get();

      $clientes = ClienteUser::join('clientes', 'cliente_user.id_cliente', 'clientes.id')
      ->where('cliente_user.id_user', '=', auth()->user()->id)
      ->select('clientes.nombre', 'clientes.id')
      ->get();

      return view('empleados.tareas_livianas.documentaciones', compact('tarea_liviana', 'clientes', 'documentacion_tarea_liviana'));
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
            $documentacion = TareaLivianaDocumentacion::findOrFail($request->id_doc);
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
  
            $ruta = storage_path("app/documentacion_tarea_liviana/{$documentacion->id}");
            $ruta_archivo = storage_path("app/documentacion_tarea_liviana/{$documentacion->id}/{$documentacion->hash_archivo}");
            unlink($ruta_archivo);
            rmdir($ruta);
  
            Storage::disk('local')->put('documentacion_tarea_liviana/'.$documentacion->id, $archivo);
  
  
            // Completar en base el hash del archivo guardado
            $documentacion = TareaLivianaDocumentacion::findOrFail($documentacion->id);
            $documentacion->hash_archivo = $archivo->hashName();
            $documentacion->save();
  
          }else {
  
            //Actualizar en base
            $documentacion = TareaLivianaDocumentacion::findOrFail($request->id_doc);
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

      $tarea_liviana = TareaLivianaDocumentacion::find($id);
      $ruta = storage_path("app/documentacion_tarea_liviana/{$tarea_liviana->id}/{$tarea_liviana->hash_archivo}");
      return response()->download($ruta);
      return back();

    }


    public function getDocumentacion($id)
    {
      $doc_tarea_liviana = TareaLivianaDocumentacion::find($id);
      return response()->json($doc_tarea_liviana);
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
