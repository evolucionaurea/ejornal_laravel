<?php

namespace App\Http\Controllers;

use App\Caratula;
use App\Nomina;
use App\Patologia;
use App\Cliente;
use Illuminate\Http\Request;
use App\Http\Traits\Clientes;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

			/* $caratulas = Caratula::with(['nomina', 'cliente', 'patologias'])
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
			$perPage = 2;
			$currentPage = LengthAwarePaginator::resolveCurrentPage();
			$currentItems = $caratulasFiltradas->forPage($currentPage, $perPage);

			$paginatedCaratulas = new LengthAwarePaginator(
					$currentItems,
					$caratulasFiltradas->count(),
					$perPage,
					$currentPage,
					['path' => Paginator::resolveCurrentPath()]
			); */

			return view('empleados.caratulas', compact('clientes'));
	}
	public function busqueda(Request $request)
	{

		DB::enableQueryLog();

		$query = Nomina::select('nominas.*') 			
			->where('id_cliente', auth()->user()->id_cliente_actual)			
			->whereHas('caratulas')
			->orderBy('nominas.nombre', 'asc');

		$total = $query->count();


		if($request->search){
			$query->where(function($query) use($request){
				$filtro = '%'.$request->search.'%';
				$query->where('nombre','like',$filtro)
					->orWhere('email','like',$filtro)
					->orWhere('legajo','like',$filtro)
					->orWhere('dni','like',$filtro)
					->orWhere('telefono','like',$filtro)
					->orWhereHas('caratulas',function($query) use($filtro){
						$query
							->where('medicacion_habitual','like',$filtro)
							->orWhere('antecedentes','like',$filtro)
							->orWhere('alergias','like',$filtro);
					});
			});
		}

		if($request->order){
			$sort = $request->columns[$request->order[0]['column']]['name'];
			///dd($sort);
			$dir  = $request->order[0]['dir'];
			$query->orderBy($sort,$dir);
		}
		if(!is_null($request->estado)) {
			$estado = $request->estado=='1' ? 1 : 0;
			if($estado===1){
				$query->where('estado','=',$estado);
			}else{
				$query->where(function($query){
					$query
						->where('estado','=',0)
						->orWhere('estado','=',2);
				});
			}
		}
		if($request->id_nomina){
			$query->where('id_nomina','=',$request->id_nomina);
		}

		///dd(DB::getQueryLog());

		$total_filtered = $query->count();

		return [
			'draw' => $request->draw,
			'recordsTotal' => $total,
			'recordsFiltered' => $total_filtered,
			'data' => $query->skip($request->start)->take($request->length)->get(),
			'fichada_user'=>auth()->user()->fichada,
			'fichar_user'=>auth()->user()->fichar,
			'request'=>$request->all()
		];

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
		
		return view('empleados.caratulas.create', compact('trabajador', 'patologias', 'clientes', 'nominas'));
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
		$trabajador = Nomina::with('cliente')->where('id',$id)->first();
		return view('empleados.caratulas.show', compact('clientes','trabajador'));
	}
	public function busqueda_trabajador(Request $request)
	{
		$query = Caratula::with('patologias')
			->where('id_nomina', $request->id_nomina);

		$total = $query->count();

		if($request->order){
			$sort = $request->columns[$request->order[0]['column']]['name'];
			$dir  = $request->order[0]['dir'];
			$query->orderBy($sort,$dir);
		}
		if($request->from){
			$query->where('created_at','>=',Carbon::createFromFormat('d/m/Y', $request->from));
		}
		if($request->to){
			$query->where('created_at','<=',Carbon::createFromFormat('d/m/Y', $request->to));
		}

		$total_filtered = $query->count();

		return [
			'draw' => $request->draw,
			'recordsTotal' => $total,
			'recordsFiltered' => $total_filtered,
			'data' => $query->skip($request->start)->take($request->length)->get(),
			'request'=>$request->all(),
		];
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

		return view('empleados.caratulas.edit',compact('caratula','clientes','patologias'));
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
		$isAjax = $request->ajax() || $request->wantsJson();

		try {
			$caratula_old = Caratula::where('id', $id)
				->with(['nomina','patologias'])
				->latest()
				->first();

			// Ajuste: imc nullable; numéricos para peso/altura
			$validatedData = $request->validate([
				'peso'           => 'required|numeric',
				'altura'         => 'required|numeric',
				'imc'            => 'nullable|numeric',
				'id_patologia'   => 'nullable|array',
				'id_patologia.*' => 'exists:patologias,id',
			]);

			$caratula = new Caratula();
			$caratula->id_nomina           = $request->id_nomina;
			$caratula->id_cliente          = auth()->user()->id_cliente_actual;
			$caratula->medicacion_habitual = $request->medicacion_habitual;
			$caratula->antecedentes        = $request->antecedentes;
			$caratula->alergias            = $request->alergias;
			$caratula->peso                = $request->peso;
			$caratula->altura              = $request->altura;

			// Si imc viene vacío, lo calculamos con peso/altura
			$imc = $request->input('imc');
			if ($imc === null || $imc === '') {
				$peso   = (float) str_replace(',', '.', $request->input('peso'));
				$altura = (float) str_replace(',', '.', $request->input('altura'));
				if ($peso > 0 && $altura > 0) {
					$imc = round($peso / pow($altura / 100, 2), 2);
				} else {
					$imc = null;
				}
			}
			$caratula->imc  = $imc;

			$caratula->user = auth()->user()->nombre;
			$caratula->save();

			if ($request->has('id_patologia')) {
				$caratula->patologias()->sync($request->id_patologia);
			}

			if ($isAjax) {
				return response()->json([
					'estado'      => true,
					'message'     => 'Carátula actualizada con éxito',
					'caratula_id' => $caratula->id,
					'id_nomina'   => $caratula->id_nomina,
				]);
			}

			return redirect()
				->route('nominas.show', $request->id_nomina)
				->with('success', 'Carátula actualizada con éxito');

		} catch (\Illuminate\Validation\ValidationException $ve) {
			// En AJAX devolvemos el detalle de errores
			if ($isAjax) {
				return response()->json([
					'estado'  => false,
					'message' => 'Validación fallida',
					'errors'  => $ve->errors(),
				], 422);
			}
			throw $ve;
		} catch (\Throwable $th) {
			if ($isAjax) {
				return response()->json([
					'estado'  => false,
					'message' => $th->getMessage(),
				], 500);
			}
			return back()->with('error', $th->getMessage());
		}
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
	public function exportar(Request $request)
	{
		if (!auth()->user()->id_cliente_actual) {
			return back()->with('error', 'No se encontraron consultas');
		}

		$cliente = Cliente::find(auth()->user()->id_cliente_actual);

		$request->draw = 1;
		$request->start = 0;
		$request->length = 10000;

		$caratulas = $this->busqueda($request)['data'];
		
		if (!$caratulas) {
			return back()->with('error', 'No se han encontrado carátulas');
		}

		$hoy = Carbon::now();
		$file_name = 'caratulas-'.$cliente->nombre.'-'.$hoy->format('YmdHis').'.csv';

		$fp = fopen('php://memory', 'w');
		fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
		fputcsv($fp,[
			'Trabajador',
			'Patologías',
			'Usuario que lo carga',
			'Medicación habitual',
			'Antecedentes',
			'Alergias',
			'Peso',
			'Altura',
			'IMC',
		],';');

		foreach($caratulas as $caratula){

			fputcsv($fp,[
				$caratula->nombre,
				$caratula->ultima_caratula->patologias->pluck('nombre')->implode(', '),
				$caratula->ultima_caratula->user,
				$caratula->ultima_caratula->medicacion_habitual,
				$caratula->ultima_caratula->antecedentes,
				$caratula->ultima_caratula->alergias,
				$caratula->ultima_caratula->peso,
				$caratula->ultima_caratula->altura,
				$caratula->ultima_caratula->imc
			],';');
		}
		fseek($fp, 0);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="'.$file_name.'";');
		fpassthru($fp);
	}	

	public function exportar_trabajador(Request $request, $id_nomina)
	{
		if (!auth()->user()->id_cliente_actual) {
			return back()->with('error', 'No se encontraron consultas');
		}

		$trabajador = Nomina::find($id_nomina);

		$request->draw = 1;
		$request->start = 0;
		$request->length = 10000;

		$caratulas = $this->busqueda_trabajador($request)['data'];
		//dd($caratulas);
		
		if (!$caratulas) {
			return back()->with('error', 'No se han encontrado carátulas');
		}

		$hoy = Carbon::now();
		$file_name = 'caratulas-'.Str::slug($trabajador->nombre).'-'.$hoy->format('YmdHis').'.csv';

		$fp = fopen('php://memory', 'w');
		fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
		fputcsv($fp,[
			'Trabajador',
			'Patologías',
			'Usuario que lo carga',
			'Medicación habitual',
			'Antecedentes',
			'Alergias',
			'Peso',
			'Altura',
			'IMC',
		],';');

		foreach($caratulas as $caratula){

			fputcsv($fp,[
				$trabajador->nombre,
				$caratula->patologias->pluck('nombre')->implode(', '),
				$caratula->user,
				$caratula->medicacion_habitual,
				$caratula->antecedentes,
				$caratula->alergias,
				$caratula->peso,
				$caratula->altura,
				$caratula->imc
			],';');
		}
		fseek($fp, 0);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="'.$file_name.'";');
		fpassthru($fp);
	}

}
