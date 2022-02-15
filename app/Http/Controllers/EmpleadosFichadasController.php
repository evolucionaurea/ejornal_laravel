<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fichada;
use Carbon\Carbon;
use App\User;

class EmpleadosFichadasController extends Controller
{

/////////////////////////////

// ESTE CONTROLADOR YA NO FUNCIONA. SE ESTA USANDO EMPLEADOSFICHADASNUEVASCONTROLLER

///////////////////////////

    public function clienteActual(Request $request)
    {
      $user = User::findOrFail(auth()->user()->id);
      $user->id_cliente_actual = intval(request()->all()['cliente']);
      $user->save();
    }


    public function horarioUltimaFichada()
    {
      $ultima_fichada = Fichada::where('id_user', auth()->user()->id)
      ->where('horario_egreso', null)
      ->latest('id')
      ->first();
      return response($ultima_fichada);
    }



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
      if (auth()->user()->fichada == 0) {
        $horario_ingreso = Carbon::now();

        //Actualizar usuario para que figure que esta trabajando
        $user = User::findOrFail(auth()->user()->id);
        $user->fichada = 1;
        $user->save();

      }else {
        $horario_egreso = Carbon::now();

        //Actualizar usuario para que figure que no está trabajando
        $user = User::findOrFail(auth()->user()->id);
        $user->fichada = 0;
        $user->save();
      }

        //Guardar en base
        $fichada = new Fichada();
        if (isset($horario_ingreso) && !empty($horario_ingreso)) {
          $fichada->horario_ingreso = $horario_ingreso;
        }
        if (isset($horario_egreso) && !empty($horario_egreso)) {
          $fichada->horario_egreso = $horario_egreso;
        }
        $fichada->fecha_actual = Carbon::now();
        $fichada->id_user = $request->id_user;
        $fichada->id_cliente = auth()->user()->id_cliente_actual;
        $fichada->save();

        return back();
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
}
