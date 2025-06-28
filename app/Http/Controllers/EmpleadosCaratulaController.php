<?php

namespace App\Http\Controllers;

use App\Caratula;
use App\Nomina;
use App\Patologia;
use Illuminate\Http\Request;
use App\Http\Traits\Clientes;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;

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
			->whereIn('caratulas.id_cliente', $clientes->pluck('id'))
			//->orderBy('created_at', 'desc')
			->join('nominas', 'caratulas.id_nomina', '=', 'nominas.id')
			->orderBy('nominas.nombre', 'asc')
			->select('caratulas.*', 'nominas.nombre as nomina_nombre')
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
	public function create($id_nomina = null)
	{
		$clientes = $this->getClientesUser();
		$patologias = Patologia::all();
		$nominas = false;
		
		// Si se proporciona id_nomina, buscar el trabajador
		if ($id_nomina) {
			$trabajador = Nomina::find($id_nomina);
			if (!$trabajador) {
				return redirect()->route('empleados.caratulas')->with('error', 'Trabajador no encontrado');
			}
		} else {
			$trabajador = null;
			
			// Cargar las nóminas de los clientes para el selector
			//dd(auth()->user()->id_cliente_actual);
			$nominas = Nomina::where('id_cliente', auth()->user()->id_cliente_actual)
				->whereDoesntHave('caratulas')
				->orderBy('nombre')
				->get();
		}
		
		return view('empleados.nominas.caratulas.create', compact('trabajador', 'patologias', 'clientes', 'nominas'));
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
		
		//dd($request->all());
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

		return redirect()->route('nominas.show',$request->id_nomina)->with('success', 'Carátula creada con éxito'); 
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, $id)
	{
			$clientes = $this->getClientesUser();

			$query = Caratula::with('patologias')
					->where('id_nomina', $id)
					->orderBy('created_at', 'desc');

			// Filtros por fecha
			if ($request->filled('fecha_desde')) {
					$fechaDesde = Carbon::createFromFormat('d-m-Y', $request->fecha_desde)->startOfDay()->toDateTimeString();
					$query->where('created_at', '>=', $fechaDesde);
			}

			if ($request->filled('fecha_hasta')) {
					$fechaHasta = Carbon::createFromFormat('d-m-Y', $request->fecha_hasta)->endOfDay()->toDateTimeString();
					$query->where('created_at', '<=', $fechaHasta);
			}

			$caratulas = $query->get();

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
		$clientes = $this->getClientesUser();
		//$trabajador = Nomina::find($id);
		$caratula = Caratula::where('id_nomina', $id)
			->with(['patologias','nomina'])
			->latest()
			->first();


		$patologias = Patologia::all();

		//dd($caratula->patologias->count());

		return view('empleados.nominas.caratulas.edit',compact('caratula','clientes','patologias'));
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
		$caratula_old = Caratula::where('id', $id)
			->with(['nomina','patologias'])
			->latest()
			->first();
		///$caratula->update($request->all());

		$validatedData = $request->validate([
			'peso' => 'required',
			'altura' => 'required',
			'imc' => 'required',
			'id_patologia' => 'nullable|array', // Permitir múltiples patologías
			'id_patologia.*' => 'exists:patologias,id' // Validar que existen en la DB
		]);

		$caratula = new Caratula();		

		$caratula->id_nomina = $request->id_nomina;
		$caratula->id_cliente = auth()->user()->id_cliente_actual;
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


		return redirect()->route('nominas.show',$request->id_nomina)->with('success', 'Carátula actualizada con éxito');
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
