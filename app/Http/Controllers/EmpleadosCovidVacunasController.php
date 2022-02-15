<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ClienteUser;
use App\CovidVacuna;
use App\Nomina;
use Carbon\Carbon;
use App\CovidVacunaTipo;
use Illuminate\Support\Facades\DB;

class EmpleadosCovidVacunasController extends Controller
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

        $vacunas = CovidVacuna::join('nominas', 'covid_vacunas.id_nomina', 'nominas.id')
        ->join('covid_vacunas_tipo', 'covid_vacunas.id_tipo', 'covid_vacunas_tipo.id')
        ->where('nominas.id_cliente', auth()->user()->id_cliente_actual)
        ->select('nominas.nombre', 'covid_vacunas.fecha', 'covid_vacunas.id_nomina', 'covid_vacunas.id',
        DB::raw('covid_vacunas_tipo.nombre tipo'), 'covid_vacunas.institucion')
        ->get();

        return view('empleados.covid.vacunas', compact('clientes', 'vacunas'));
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

      $covid_vacunas_tipo = CovidVacunaTipo::orderBy('nombre')->get();
      $nominas = Nomina::where('id_cliente', auth()->user()->id_cliente_actual)->orderBy('nombre', 'asc')->get();

      return view('empleados.covid.vacunas.create', compact('clientes', 'nominas', 'covid_vacunas_tipo'));

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
        'nomina' => 'required',
        'tipo' => 'required',
        'fecha' => 'required',
        'institucion' => 'required|string'
      ]);

      $fecha = Carbon::createFromFormat('d/m/Y', $request->fecha);

      //Guardar en base CovidVacuna
      $vacuna = new CovidVacuna();
      $vacuna->id_nomina = $request->nomina;
      $vacuna->id_tipo = $request->tipo;
      $vacuna->fecha = $fecha;
      $vacuna->institucion = $request->institucion;
      $vacuna->save();

      return redirect('empleados/covid/vacunas')->with('success', 'Vacuna de covid guardado con éxito');

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

      $vacuna = CovidVacuna::where('covid_vacunas.id', $id)
      ->join('nominas', 'covid_vacunas.id_nomina', 'nominas.id')
      ->join('covid_vacunas_tipo', 'covid_vacunas.id_tipo', 'covid_vacunas_tipo.id')
      ->select('nominas.nombre', DB::raw('covid_vacunas_tipo.nombre tipo'), 'covid_vacunas.fecha',
      'covid_vacunas.institucion', 'covid_vacunas.id', 'covid_vacunas.id_nomina', 'covid_vacunas.id_tipo')
      ->first();

      $clientes = ClienteUser::join('clientes', 'cliente_user.id_cliente', 'clientes.id')
      ->where('cliente_user.id_user', '=', auth()->user()->id)
      ->select('clientes.nombre', 'clientes.id')
      ->get();

      $covid_vacunas_tipo = CovidVacunaTipo::all();

      $nominas = Nomina::where('id_cliente', auth()->user()->id_cliente_actual)->get();

      return view('empleados.covid.vacunas.edit', compact('clientes', 'nominas', 'vacuna', 'covid_vacunas_tipo'));

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
        'nomina' => 'required',
        'tipo' => 'required',
        'fecha' => 'required',
        'institucion' => 'required|string'
      ]);

      $fecha = Carbon::createFromFormat('d/m/Y', $request->fecha);

      //Actualizar en base
      $vacuna = CovidVacuna::findOrFail($id);
      $vacuna->id_nomina = $request->nomina;
      $vacuna->id_tipo = $request->tipo;
      $vacuna->fecha = $fecha;
      $vacuna->institucion = $request->institucion;
      $vacuna->save();

      return redirect('empleados/covid/vacunas')->with('success', 'Vacuna de Covid actualizada con éxito');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

      $vacuna = CovidVacuna::findOrFail($id)->delete();
      return back()->with('success', 'Vacuna de covid eliminada correctamente');

    }


    public function tipo(Request $request)
    {
        $validatedData = $request->validate([
          'nombre' => 'required|string'
        ]);

        //Guardar en base
        $tipo_dosis = new CovidVacunaTipo();
        $tipo_dosis->nombre = $request->nombre;
        $tipo_dosis->save();

        return back()->with('success', 'Tipo de vacuna para covid creado con éxito');
    }


    public function tipo_destroy($id_tipo)
    {

      $dosis_covid = CovidVacuna::where('id_tipo', $id_tipo)->get();

      if (!empty($dosis_covid) && count($dosis_covid) > 0) {
        return back()->with('error', 'Existen vacunas de covid creados con este tipo de vacuna. No puedes eliminarlo');
      }

        //Eliminar en base
        $tipo_dosis = CovidVacunaTipo::find($id_tipo)->delete();
        return back()->with('success', 'Tipo de vacuna de covid eliminado correctamente');
    }



}
