<?php

namespace App\Http\Controllers;

use App\ConsultaNutricional;
use App\Patologia;
use Illuminate\Http\Request;
use App\Http\Traits\Clientes;
use App\Nomina;
use App\Caratula;
use App\Cliente;
use Carbon\Carbon;


class EmpleadosConsultaNutricionalController extends Controller
{

	use Clientes;
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$clientes = $this->getClientesUser();
		/* $query = ConsultaNutricional::with(['nomina', 'cliente'])
			->where('id_cliente', auth()->user()->id_cliente_actual);

		// Filtros de fechas
		if ($request->filled('fecha_desde')) {
			$fechaDesde = Carbon::createFromFormat('d-m-Y', $request->fecha_desde)->format('Y-m-d');
			$query->whereDate('fecha_atencion', '>=', $fechaDesde);
		}

		if ($request->filled('fecha_hasta')) {
			$fechaHasta = Carbon::createFromFormat('d-m-Y', $request->fecha_hasta)->format('Y-m-d');
			$query->whereDate('fecha_atencion', '<=', $fechaHasta);
		}

		$paginatedNutricion = $query->orderBy('created_at', 'desc')->paginate(10); */

		return view('empleados.consultas.nutricionales', compact('clientes'));
	}
	


	public function busqueda(Request $request)
{
    $search = is_array($request->search) ? ($request->search['value'] ?? null) : $request->search;

    $q = ConsultaNutricional::select('consultas_nutricionales.*')
        ->with([
            'trabajador',
            'cliente'
        ])
        ->leftJoin('nominas','consultas_nutricionales.id_nomina','=','nominas.id')
        ->where('consultas_nutricionales.id_cliente', auth()->user()->id_cliente_actual);

    $total = (clone $q)->distinct('consultas_nutricionales.id')->count('consultas_nutricionales.id');

    if ($request->from) {
        $desde = Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d');
        $q->whereDate('consultas_nutricionales.fecha_atencion','>=',$desde);
    }
    if ($request->to) {
        $hasta = Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d');
        $q->whereDate('consultas_nutricionales.fecha_atencion','<=',$hasta);
    }
    if ($request->filtro === 'mes') {
        $q->whereMonth('consultas_nutricionales.fecha_atencion', now()->month)
          ->whereYear('consultas_nutricionales.fecha_atencion', now()->year);
    }
    if (!empty($search)) {
        $f = "%{$search}%";
        $q->where(function ($qq) use ($f) {
            $qq->where('nominas.nombre', 'like', $f)
               ->orWhere('consultas_nutricionales.tipo', 'like', $f)
               ->orWhere('nominas.legajo', 'like', $f);
        });
    }
    if ($request->filled('estado')) {
        $q->whereHas('trabajador', function ($qq) use ($request) {
            $qq->where('estado', $request->estado);
        });
    }
    if ($request->filled('dni')) {
        $q->whereHas('trabajador', function ($qq) use ($request) {
            $qq->where('dni', $request->dni);
        });
    }

    if ($request->order) {
        $sort = $request->columns[$request->order[0]['column']]['name'] ?? 'consultas_nutricionales.id';
        $dir  = $request->order[0]['dir'] ?? 'desc';
        $allowed = ['id','tipo','fecha_atencion'];
        if (!in_array($sort, $allowed, true)) $sort = 'consultas_nutricionales.id';
        $q->orderBy($sort, $dir);
    } else {
        $q->orderBy('consultas_nutricionales.id','desc');
    }

    $filtered = (clone $q)->distinct('consultas_nutricionales.id')->count('consultas_nutricionales.id');

    return [
        'draw' => (int) $request->draw,
        'recordsTotal' => $total,
        'recordsFiltered' => $filtered,
        'data' => $q->skip((int)$request->start)->take((int)$request->length)->get(),
        'fichada_user' => auth()->user()->fichada,
        'fichar_user'  => auth()->user()->fichar,
        'request' => $request->all(),
    ];
}



	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$clientes = $this->getClientesUser();
		$patologias = Patologia::all();

		// Filtrar por 'estado' y ordenar alfabéticamente por 'nombre'
		$nominas = Nomina::where('id_cliente', auth()->user()->id_cliente_actual)
			->where('estado', 1)  // Filtramos las nominas donde el estado es 1
			->orderBy('nombre', 'asc')  // Ordenamos alfabéticamente por 'nombre'
			->get();

		$cliente = Cliente::find(auth()->user()->id_cliente_actual);

		return view('empleados.consultas.nutricionales.create', compact('patologias', 'clientes', 'nominas', 'cliente'));
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
			'id_nomina' => 'required|exists:nominas,id',
			'id_cliente' => 'required|exists:clientes,id',
			'tipo' => 'required|in:inicial,seguimiento',
			'fecha_atencion' => 'required',
			'peso'=>'required|numeric',
			'altura'=>'required|numeric',
			'imc'=>'required|numeric',
			// Campos opcionales
			'objetivos' => 'nullable|string',
			'gustos_alimentarios' => 'nullable|string',
			'comidas_diarias' => 'nullable|string',
			'descanso' => 'nullable|string',
			'intolerancias_digestivas' => 'nullable|string',
			'alergias_alimentarias' => 'nullable|string',
			'circunferencia_cintura' => 'nullable|numeric',
			'porcent_masa_grasa' => 'nullable|numeric',
			'porcent_masa_muscular' => 'nullable|numeric',
			'prox_cita' => 'nullable',
			'act_fisica' => 'nullable|string',
			'transito_intestinal' => 'nullable|string',
			'evolucion' => 'nullable|string',
			'medicaciones' => 'nullable|string',
		]);

		// Crear una nueva consulta nutricional
		$consultaNutricional = new ConsultaNutricional();

		// Datos Obligatorios
		$consultaNutricional->id_nomina = $validatedData['id_nomina'];
		$consultaNutricional->id_cliente = auth()->user()->id_cliente_actual;
		$consultaNutricional->tipo = $validatedData['tipo'];
		$consultaNutricional->user = auth()->user()->nombre;

		$consultaNutricional->peso = $validatedData['peso'];
		$consultaNutricional->altura = $validatedData['altura'];
		$consultaNutricional->imc = $validatedData['imc'];

		$consultaNutricional->fecha_atencion = Carbon::createFromFormat('d/m/Y', $validatedData['fecha_atencion']);

		// Asignar los campos adicionales según el tipo de consulta
		switch ($validatedData['tipo']) {
			case 'inicial':
				$consultaNutricional->objetivos = $validatedData['objetivos'];
				$consultaNutricional->gustos_alimentarios = $validatedData['gustos_alimentarios'];
				$consultaNutricional->comidas_diarias = $validatedData['comidas_diarias'];
				$consultaNutricional->descanso = $validatedData['descanso'];
				$consultaNutricional->intolerancias_digestivas = $validatedData['intolerancias_digestivas'];
				$consultaNutricional->alergias_alimentarias = $validatedData['alergias_alimentarias'];
				break;

			case 'seguimiento':
				$consultaNutricional->circunferencia_cintura = $validatedData['circunferencia_cintura'];
				$consultaNutricional->porcent_masa_grasa = $validatedData['porcent_masa_grasa'];
				$consultaNutricional->porcent_masa_muscular = $validatedData['porcent_masa_muscular'];
				$consultaNutricional->prox_cita = $validatedData['prox_cita'] ? Carbon::createFromFormat('d/m/Y', $validatedData['prox_cita']) : null;
				$consultaNutricional->act_fisica = $validatedData['act_fisica'];
				$consultaNutricional->transito_intestinal = $validatedData['transito_intestinal'];
				$consultaNutricional->evolucion = $validatedData['evolucion'];
				$consultaNutricional->medicaciones = $validatedData['medicaciones'];
				break;

			default:
				// Si el tipo no es ni 'inicial' ni 'seguimiento', no asignar nada
				break;
		}


		// Guardar la consulta nutricional en la base de datos
		$consultaNutricional->save();


		/// check si tiene carátula y crear un nuevo registro con el nuevo peso
		$caratula = Caratula::with('patologias')
			->where('id_nomina',$request->id_nomina)
			->latest()
			->first();	
				
		if($caratula){
			$patologias_ids = $caratula->patologias->pluck('id');

			$caratula_new = new Caratula();
			$caratula_new->id_nomina = $request->id_nomina;
			$caratula_new->id_cliente = auth()->user()->id_cliente_actual;
			$caratula_new->medicacion_habitual = $caratula->medicacion_habitual;
			$caratula_new->antecedentes = $caratula->antecedentes;
			$caratula_new->alergias = $caratula->alergias;
			$caratula_new->peso = $request->peso;
			$caratula_new->altura = $request->altura;
			$alturaMetros = $request->altura / 100;
			$caratula_new->imc = round($request->peso / ($alturaMetros * $alturaMetros), 2);
			$caratula_new->user = auth()->user()->nombre;
			$caratula_new->save();
			
			// Guardar la relación en la tabla intermedia
			if( !empty($patologias_ids) ){
				$caratula_new->patologias()->sync($patologias_ids);
			}
		}
		/// end carátula



		// Redirigir a la lista de consultas nutricionales con un mensaje de éxito
		return redirect()->route('empleados.consultas.nutricionales')->with('success', 'Consulta nutricional creada exitosamente');


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
		$nutricional = ConsultaNutricional::with(['nomina', 'cliente'])->find($id);

		//dd($nutricional->nomina->ultima_caratula);
		return view('empleados.consultas.nutricionales.show', compact('nutricional', 'clientes'));
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


	public function exportar(Request $request)
	{

		if (!auth()->user()->id_cliente_actual) {
			return back()->with('error', 'No se encontraron consultas');
		}

		$request->draw = 1;
		$request->start = 0;
		$request->length = 10000;

		$consultas = $this->busqueda($request)['data'];
		
		if (!$consultas) {
			return back()->with('error', 'No se han encontrado consultas');
		}

		$hoy = Carbon::now();
		$file_name = 'consultas-nutricionales-'.$hoy->format('YmdHis').'.csv';

		$fp = fopen('php://memory', 'w');
		fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
		fputcsv($fp,[
			'Trabajador',
			'CUIL',
			'Legajo',
			'Fecha de atención',
			'Tipo',
			'Objetivos',
			'Gustos alimentarios',
			'Comidas diarias',
			'Descanso',
			'Intolerancias digestivas',
			'Alergias alimentarias',
			'Circunferencia cintura',
			'Porcentaje masa grasa',
			'Porcentaje masa muscular',
			'Próxima cita',
			'Actividad física',
			'Transito intestinal',
			'Evolución',
			'Medicaciones',
		],';');

		foreach($consultas as $consulta){

			fputcsv($fp,[
				$consulta->trabajador->nombre,
				$consulta->trabajador->dni,
				$consulta->trabajador->legajo,
				$consulta->fecha_atencion->format('d/m/Y'),
				$consulta->tipo,
				str_replace(["\r", "\n"],' ',$consulta->objetivos),
				str_replace(["\r", "\n"],' ',$consulta->gustos_alimentarios),
				str_replace(["\r", "\n"],' ',$consulta->comidas_diarias),
				str_replace(["\r", "\n"],' ',$consulta->descanso),
				str_replace(["\r", "\n"],' ',$consulta->intolerancias_digestivas),
				str_replace(["\r", "\n"],' ',$consulta->alergias_alimentarias),
				$consulta->circunferencia_cintura,
				$consulta->porcent_masa_grasa,
				$consulta->porcent_masa_muscular,
				$consulta->prox_cita,
				str_replace(["\r", "\n"],' ',$consulta->act_fisica),
				str_replace(["\r", "\n"],' ',$consulta->transito_intestinal),
				str_replace(["\r", "\n"],' ',$consulta->evolucion),
				str_replace(["\r", "\n"],' ',$consulta->medicaciones)
			],';');
		}
		fseek($fp, 0);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="'.$file_name.'";');
		fpassthru($fp);


		return;

	}

}
