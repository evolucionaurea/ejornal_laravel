<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ConsultaEnfermeria;
use App\DiagnosticoConsulta;
use Illuminate\Support\Facades\DB;
use App\ClienteUser;
use App\Nomina;
use App\StockMedicamento;
use App\ConsultaMedicacion;
use App\StockMedicamentoHistorial;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class EmpleadoConsultaEnfermeriaController extends Controller
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

      $consultas = ConsultaEnfermeria::join('nominas', 'consultas_enfermerias.id_nomina', 'nominas.id')
      ->join('diagnostico_consulta', 'consultas_enfermerias.id_diagnostico_consulta', 'diagnostico_consulta.id')
      ->where('nominas.id_cliente', auth()->user()->id_cliente_actual)
      ->select('nominas.nombre', 'consultas_enfermerias.*', DB::raw('diagnostico_consulta.nombre diagnostico'))
      ->orderBy('consultas_enfermerias.fecha', 'desc')
      ->get();

      return view('empleados.consultas.enfermeria', compact('clientes', 'consultas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

      $clientes = ClienteUser::join('clientes', 'cliente_user.id_cliente', 'clientes.id')
      ->where('cliente_user.id_user', '=', auth()->user()->id)
      ->select('clientes.nombre', 'clientes.id')
      ->get();

      $diagnostico_consultas = DiagnosticoConsulta::all();

      $stock_medicamentos = StockMedicamento::join('medicamentos', 'stock_medicamentos.id_medicamento', 'medicamentos.id')
      ->join('clientes', 'stock_medicamentos.id_cliente', 'clientes.id')
      ->where('id_cliente', auth()->user()->id_cliente_actual)
      ->select('medicamentos.nombre', 'stock_medicamentos.stock', 'stock_medicamentos.id')
      ->get();

      $nominas = Nomina::where('id_cliente', auth()->user()->id_cliente_actual)->orderBy('nombre', 'asc')->get();

      return view('empleados.consultas.enfermeria.create', compact('clientes', 'nominas', 'diagnostico_consultas', 'stock_medicamentos'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Si existen medicaciones se validan aqui
        $suministrados = [];
        if (isset($request->medicaciones) && !empty($request->medicaciones)) {
          foreach ($request->medicaciones as $key => $value) {
            $medicaciones = explode(",", $value);
            if ($medicaciones[1] !== '') {
              $suministrados[] = [
                'id_medicamento' => $medicaciones[0],
                'suministrados' => $medicaciones[1]
              ];
            }
          }
        }

        if (!isset($request->tipo) || empty($request->tipo) || $request->tipo == '' || $request->tipo == null) {
          return back()->withInput($request->input())->with('error', 'Debes ingresar un diagnostico');
        }


        if (isset($request->fecha)) {
          $fecha = Carbon::createFromFormat('d/m/Y', $request->fecha);
        }

        if (!isset($fecha) || empty($fecha) || $fecha == '' || $fecha == null) {
          return back()->withInput($request->input())->with('error', 'Debes ingresar una fecha');
        }

        if (!isset($request->observaciones) || empty($request->observaciones) || $request->observaciones == '' || $request->observaciones == null) {
          return back()->withInput($request->input())->with('error', 'Debes completar el campo observaciones');
        }

        if (isset($request->peso) && !empty($request->peso) && !isset($request->altura)) {
          if ($request->peso == 0 || $request->peso < 0) {
            return back()->withInput($request->input())->with('error', 'En el campo peso vemos un valor inválido');
          }else {
            return back()->withInput($request->input())->with('error', 'Si completas el campo Peso debes complatar Altura');
          }
        }

        if (isset($request->altura) && !empty($request->altura) && !isset($request->peso)) {
          if ($request->altura == 0 || $request->altura < 0) {
            return back()->withInput($request->input())->with('error', 'En el campo altura vemos un valor inválido');
          }else {
            return back()->withInput($request->input())->with('error', 'Si completas el campo Altura debes complatar Peso');
          }
        }


        if (isset($suministrados) && !empty($suministrados)) {
          $todos_los_stocks_disponibles = 0;
          foreach ($suministrados as $value) {
            $stock_medicacion = StockMedicamento::where('id', intval($value['id_medicamento']))
            ->where('id_cliente', auth()->user()->id_cliente_actual)
            ->first();

            $stock_disponible = $stock_medicacion->stock - intval($value['suministrados']);

            if ($stock_disponible > 0) {
              $todos_los_stocks_disponibles++;
            }

          }
          if (count($suministrados) == $todos_los_stocks_disponibles) {
            $todos_los_stocks_disponibles = true;
          }else {
            $todos_los_stocks_disponibles = false;
            return back()->withInput($request->input())->with('error', 'No puedes suministrar mas medicamentos que los disponibles en el stock');
          }
        }



        //Guardar en base una Nueva Consulta
        $consulta = new ConsultaEnfermeria();
        $consulta->id_nomina = $request->nomina;

        if (isset($request->fecha)) {
        $consulta->fecha = $fecha;
        }

        if (isset($request->peso) && !empty($request->peso) && isset($request->altura) && !empty($request->altura)) {
          $consulta->peso = $request->peso;
          $consulta->altura = $request->altura;
          $consulta->imc = $request->imc;
        }

        if (isset($request->tipo) && !empty($request->tipo)) {
          $consulta->id_diagnostico_consulta = $request->tipo;
        }

        if (isset($request->glucemia) && !empty($request->glucemia)) {
          $consulta->glucemia = $request->glucemia;
        }

        if (isset($request->saturacion_oxigeno) && !empty($request->saturacion_oxigeno)) {
          $consulta->saturacion_oxigeno = $request->saturacion_oxigeno;
        }

        if (isset($request->tension_arterial) && !empty($request->tension_arterial)) {
          $consulta->tension_arterial = $request->tension_arterial;
        }

        if (isset($request->frec_cardiaca) && !empty($request->frec_cardiaca)) {
          $consulta->frec_cardiaca = $request->frec_cardiaca;
        }

        $consulta->derivacion_consulta = $request->derivacion_consulta;
        $consulta->amerita_salida = $request->amerita_salida;
        $consulta->observaciones = $request->observaciones;
        $consulta->user = auth()->user()->nombre;
        $consulta->id_user = auth()->user()->id;
        $consulta->save();

        if (isset($todos_los_stocks_disponibles) && $todos_los_stocks_disponibles == true) {
          foreach ($suministrados as $value) {

            //Guardar en base
            $consulta_medicacion = new ConsultaMedicacion();
            $consulta_medicacion->id_consulta_enfermeria = $consulta->id;
            $consulta_medicacion->id_medicamento = $value['id_medicamento'];
            $consulta_medicacion->suministrados = $value['suministrados'];
            $consulta_medicacion->id_cliente = auth()->user()->id_cliente_actual;
            $consulta_medicacion->save();


            //Actualizar el Stock
            $stock_medicacion = StockMedicamento::where('id', $value['id_medicamento'])
            ->where('id_cliente', auth()->user()->id_cliente_actual)
            ->first();

            $stock_medicacion->suministrados = $stock_medicacion->suministrados + $value['suministrados'];
            $stock_medicacion->stock = $stock_medicacion->stock - $value['suministrados'];
            $stock_medicacion->save();


            // Actualizar la tabla Historial
            $historial_stock_medicamentos = new StockMedicamentoHistorial();
            $historial_stock_medicamentos->id_stock_medicamentos = $stock_medicacion->id;
            $historial_stock_medicamentos->suministrados = $value['suministrados'];
            if (isset($request->fecha)) {
              $historial_stock_medicamentos->fecha_ingreso = $fecha;
            }
            $historial_stock_medicamentos->save();

          }
        }

        return redirect('empleados/consultas/enfermeria')->with('success', 'Consulta de enfermería guardada con éxito');



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

      $consulta_enfermeria = ConsultaEnfermeria::join('nominas', 'consultas_enfermerias.id_nomina', 'nominas.id')
      ->join('diagnostico_consulta', 'consultas_enfermerias.id_diagnostico_consulta', 'diagnostico_consulta.id')
      ->where('consultas_enfermerias.id', $id)
      ->select('consultas_enfermerias.*', 'nominas.nombre', 'nominas.telefono', 'nominas.dni', 'nominas.estado', 'nominas.email', DB::raw('diagnostico_consulta.nombre diagnostico'))
      ->first();

      $clientes = ClienteUser::join('clientes', 'cliente_user.id_cliente', 'clientes.id')
      ->where('cliente_user.id_user', '=', auth()->user()->id)
      ->select('clientes.nombre', 'clientes.id')
      ->get();

      return view('empleados.consultas.enfermeria.show', compact('consulta_enfermeria', 'clientes'));

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
        $diagnostico = new DiagnosticoConsulta();
        $diagnostico->nombre = $request->nombre;
        $diagnostico->save();

        return back()->with('success', 'Tipo de diagnóstico creado con éxito');
    }


    public function tipo_destroy($id_tipo)
    {

      $diagnostico_consulta = ConsultaEnfermeria::where('id_diagnostico_consulta', $id_tipo)->get();

      if (!empty($diagnostico_consulta) && count($diagnostico_consulta) > 0) {
        return back()->with('error', 'Existen consultas de enfermería creadas con este tipo de diagnostico. No puedes eliminarlo');
      }

        //Eliminar en base
        $tipo_diagnostico_consulta = DiagnosticoConsulta::find($id_tipo)->delete();
        return back()->with('success', 'Tipo de diagnostico de consulta eliminado correctamente');
    }



}
