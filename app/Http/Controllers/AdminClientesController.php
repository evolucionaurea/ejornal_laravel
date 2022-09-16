<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Nomina;
use App\ClienteUser;
use App\User;
use App\Grupo;
use App\ClienteGrupo;
use Illuminate\Support\Str;

class AdminClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = Cliente::all();
        return view('admin.clientes', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.clientes.create');
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
        'nombre' => 'required|string',
        'direccion' => 'required|string'
      ]);

      //Guardar en base
      $cliente = new Cliente();
      $cliente->nombre = $request->nombre;
      $cliente->direccion = $request->direccion;
      $cliente->save();

      return redirect('admin/clientes')->with('success', 'Cliente guardado con éxito');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $cliente = Cliente::findOrFail($id);
        $trabajadores = Nomina::where('id_cliente', $id)->get();
        $cliente_user = ClienteUser::where('id_cliente', $id)->get();
        $cliente_grupo = ClienteGrupo::where('id_cliente', $id)->first();
        if ($cliente_grupo != null) {
          $grupo = Grupo::findOrFail($cliente_grupo->id_grupo);
        }else {
          $grupo = null;
        }

        if (!empty($cliente_user) && count($cliente_user) > 0) {
          foreach ($cliente_user as $user) {
            $buscar_user = User::where('id', $user->id_user)
            ->where('estado', 1)
            ->where('id_rol', 2)
            ->select('nombre')
            ->first();
            if (!empty($buscar_user) && !is_null($buscar_user)) {
              $empleados[] = [
                'empleado' => $buscar_user->nombre
              ];
            }
          }
        }else {
          $empleados = null;
        }


        return view('admin.clientes.show', compact('cliente', 'trabajadores', 'empleados', 'grupo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

      $cliente = Cliente::findOrFail($id);

      return view('admin.clientes.edit', compact('cliente'));

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
        'nombre' => 'required|string',
        'direccion' => 'required|string'
      ]);

      //Actualizar en base
      $cliente = Cliente::findOrFail($id);
      $cliente->nombre = $request->nombre;
      $cliente->direccion = $request->direccion;
      $cliente->save();

      return redirect('admin/clientes')->with('success', 'Cliente actualizado con éxito');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $cliente = Cliente::find($id)->delete();
      return redirect('admin/clientes')->with('success', 'Cliente eliminado correctamente');
    }


    public function cargar_excel(Request $request)
    {
      // dd($request->hasFile('archivo'));

      if ($request->hasFile('archivo')) {

        $file = $request->file('archivo');

        $registros = array();
        $fichero = fopen($file, "r");
            // Lee los nombres de los campos
            $nombres_campos = fgetcsv($fichero, 0 , ";" , '"');
            $num_campos = count($nombres_campos);
            // Lee los registros
            while (($datos = fgetcsv($fichero, 0 , ";" , '"')) !== FALSE) {
                // Crea un array asociativo con los nombres y valores de los campos
                for ($icampo = 0; $icampo < $num_campos; $icampo++) {
                  if ($datos[$icampo] !== '') {
                    $registro[$nombres_campos[$icampo]] = $datos[$icampo];
                  }else {
                    $registro[$nombres_campos[$icampo]] = '';
                  }
                }

                // Añade el registro leido al array de registros
                $registros[] = $registro;
            }
            fclose($fichero);

            $errores = false;
            foreach ($registros as $registro) {
              if (isset($registro['nombre']) == false) {
                return back()->with('error', 'El excel tiene datos mal cargados. Le recomendamos completarlo cuidadosamente.');
              }
              if ($registro['nombre'] == '' || $registro['direccion'] == '') {
                $errores = true;
              }else {
                $errores = false;
              }
            }

            if ($errores) {
              return back()->with('error', 'El excel tiene datos incompletos o mal cargados');
            }else {

              foreach ($registros as $registro) {
                //Guardar en base
                $cliente = new Cliente();
                $cliente->nombre = $registro['nombre'];
                $cliente->direccion = $registro['direccion'];
                $cliente->save();
              }

              return redirect('admin/clientes')->with('success', 'Carga masiva de cliente exitosa');
            }


      }else {
        return back()->with('error', 'No has subido ningún archivo');
      }

    }

    public function generarToken(Request $request)
    {
        $string = Str::random(60);
        $token = hash('sha256', $string);

        $cliente = Cliente::findOrFail($request->id_cliente);
        $cliente->token = $token;
        $cliente->save();

        return back()->with('success', 'Token generado exitosamente');
    }

    public function deleteToken(Request $request)
    {

      $cliente = Cliente::findOrFail($request->id_cliente);
      $cliente->token = null;
      $cliente->save();

      return back()->with('success', 'Token eliminado exitosamente. El cliente ya no tendrá acceso a utilizar la API');
    }


}
