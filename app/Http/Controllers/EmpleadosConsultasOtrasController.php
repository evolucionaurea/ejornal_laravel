<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\Clientes;
use App\Cliente;
use App\Nomina;
use App\ProfesionalTipo;
use App\ConsultaOtra;
use Carbon\Carbon;


class EmpleadosConsultasOtrasController extends Controller
{

	use Clientes;
    
	public function index()
	{

		$clientes = $this->getClientesUser();
		return view('empleados.consultas.otras', compact('clientes'));
		
	}

	public function create()
	{

		$clientes = $this->getClientesUser();
		$cliente = Cliente::find(auth()->user()->id_cliente_actual);

		$nominas = Nomina::where('id_cliente', auth()->user()->id_cliente_actual)
			->where('estado', 1)
			->orderBy('nombre', 'asc')
			->get();
		
		$profesionales_tipos = ProfesionalTipo::orderBy('nombre', 'asc')->get();

		return view('empleados.consultas.otras.create', compact('clientes','nominas','cliente','profesionales_tipos'));
		
	}

	public function store(Request $request)
	{

		$validated = $request->validate([
			'motivo' => 'required',
			'desarrollo' => 'required',
			'fecha' => 'required',
			'nomina_id' => 'required',
			'tipo_profesional_id' => 'required'			
		]);
		
		$ConsultaOtra = new ConsultaOtra;
		$ConsultaOtra->nomina_id = $request->nomina_id;
		$ConsultaOtra->user_id = auth()->user()->id;
		$ConsultaOtra->tipo_profesional_id = $request->tipo_profesional_id;
		$ConsultaOtra->cliente_id = auth()->user()->id_cliente_actual;
		$ConsultaOtra->fecha = Carbon::createFromFormat('d/m/Y', $request->fecha)->format('Y-m-d');
		$ConsultaOtra->motivo = $request->motivo;
		$ConsultaOtra->desarrollo = $request->desarrollo;
		$ConsultaOtra->save();

		return redirect()->route('empleados.consultas.otras')->with('success', 'Consulta creada exitosamente.');
		
	}

	public function busqueda(Request $request){

		$search = is_array($request->search) ? ($request->search['value'] ?? null) : $request->search;

		$q = ConsultaOtra::select('consultas_otras.*')
				->with([
						'trabajador',
						'cliente',
						'user',
						'profesional_tipo'
				])
				->leftJoin('nominas','consultas_otras.nomina_id','=','nominas.id')
				->leftJoin('profesionales_tipos','consultas_otras.tipo_profesional_id','=','profesionales_tipos.id')
				->where('consultas_otras.cliente_id', auth()->user()->id_cliente_actual);

		$total = (clone $q)->distinct('consultas_otras.id')->count('consultas_otras.id');

		if ($request->from) {
				$desde = Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d');
				$q->whereDate('consultas_otras.fecha','>=',$desde);
		}
		if ($request->to) {
				$hasta = Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d');
				$q->whereDate('consultas_otras.fecha','<=',$hasta);
		}
		
		if (!empty($search)) {
				$f = "%{$search}%";
				$q->where(function ($qq) use ($f) {
						$qq->where('nominas.nombre', 'like', $f)
								->orWhere('consultas_otras.motivo', 'like', $f)
								->orWhere('profesionales_tipos.nombre', 'like', $f)
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
				$sort = $request->columns[$request->order[0]['column']]['name'] ?? 'consultas_otras.id';
				$dir  = $request->order[0]['dir'] ?? 'desc';
				$allowed = ['id','profesional_tipo','fecha'];
				if (!in_array($sort, $allowed, true)) $sort = 'consultas_otras.id';
				$q->orderBy($sort, $dir);
		} else {
				$q->orderBy('consultas_otras.id','desc');
		}

		$filtered = (clone $q)->distinct('consultas_otras.id')->count('consultas_otras.id');

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

	public function show($id){
		$clientes = $this->getClientesUser();
		$otra_consulta = ConsultaOtra::with([
			'trabajador',
			'cliente',
			'user',
			'profesional_tipo'
		])->find($id);
		
		return view('empleados.consultas.otras.show', compact('otra_consulta', 'clientes'));
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
		$file_name = 'otras-consultas-'.$hoy->format('YmdHis').'.csv';

		$fp = fopen('php://memory', 'w');
		fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
		fputcsv($fp,[
			'Trabajador',
			'CUIL',
			'Legajo',
			'Fecha de atención',
			'Tipo',			
			'Motivo',
			'Desarrollo'
		],';');

		foreach($consultas as $consulta){

			fputcsv($fp,[
				$consulta->trabajador->nombre,
				$consulta->trabajador->dni,
				$consulta->trabajador->legajo,
				$consulta->fecha->format('d/m/Y'),
				$consulta->profesional_tipo->nombre,
				str_replace(["\r", "\n"],' ',$consulta->motivo),
				str_replace(["\r", "\n"],' ',$consulta->desarrollo)
			],';');
		}
		fseek($fp, 0);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="'.$file_name.'";');
		fpassthru($fp);


		return;

	}
}
