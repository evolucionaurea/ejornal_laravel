<?php

namespace App\Http\Controllers;

use App\Caratula;
use App\Nomina;
use App\Patologia;
use Illuminate\Http\Request;
use App\Http\Traits\Clientes;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class EmpleadosCaratulaController extends Controller
{
    use Clientes;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = $this->getClientesUser(); // Obtener los clientes del usuario actual
    
        $caratulas = Caratula::with(['nomina', 'cliente', 'patologias'])
        ->whereIn('id_cliente', $clientes->pluck('id'))
        ->orderBy('created_at', 'desc')
        ->get();
    
        // Agrupar por el campo único del trabajador (nombre en este caso) y obtener la más reciente
        $caratulasUnicas = $caratulas
            ->groupBy('nomina.nombre')
            ->map(function ($group) {
                return $group->sortByDesc('created_at')->first(); // Seleccionar la carátula más reciente
            });
    
        // Convertir en una colección plana
        $caratulasFiltradas = $caratulasUnicas->values();
    
        // Paginación manual
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $caratulasFiltradas->forPage($currentPage, $perPage);
    
        $paginatedCaratulas = new LengthAwarePaginator(
            $currentItems,
            $caratulasFiltradas->count(),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );
    
        return view('empleados.nominas.caratulas', compact('paginatedCaratulas', 'clientes'));
    }
    
    
    
    
    
    
    
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_nomina)
    {
        $clientes = $this->getClientesUser();
        $patologias = Patologia::all();
        $trabajador = Nomina::find($id_nomina);
        return view('empleados.nominas.caratulas.create', compact('trabajador', 'patologias', 'clientes'));
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
            'peso' => 'required',
            'altura' => 'required',
            'imc' => 'required',
            'id_patologia' => 'nullable|array', // Permitir múltiples patologías
            'id_patologia.*' => 'exists:patologias,id' // Validar que existen en la DB
        ]);
 
        $nomina = Nomina::find($request->id_nomina);

        $caratula = new Caratula();
        $caratula->id_nomina = $request->id_nomina;
        $caratula->id_cliente = $nomina->cliente->id;
        $caratula->medicacion_habitual = $request->medicacion_habitual;
        $caratula->antecedentes = $request->antecedentes;
        $caratula->alergias = $request->alergias;
        $caratula->peso = $request->peso;
        $caratula->altura = $request->altura;
        $caratula->imc = $request->imc;
        $caratula->user = auth()->user()->nombre;
        $caratula->save();

         // Guardar la relación en la tabla intermedia
         if ($request->has('id_patologia')) {
            $caratula->patologias()->sync($request->id_patologia);
        }

        return redirect('empleados/nominas/'.$request->id_nomina)->with('success', 'Caratula guardada con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $clientes = $this->getClientesUser();
        $caratulas = Caratula::with('patologias') // Cargar patologías desde la tabla intermedia
            ->where('id_nomina', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('empleados.nominas.caratulas.show', compact('caratulas', 'clientes'));
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
        $caratula = Caratula::find($id);
        
        if ($caratula) {
            $caratula->patologias()->detach(); // Eliminar relaciones en la tabla intermedia
            $caratula->delete();
            return back()->with('success', 'Carátula eliminada con éxito');
        }

        return back()->with('error', 'Carátula no encontrada');
    }
    
}
