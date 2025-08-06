<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\StockMedicamento;
use App\Medicamento;
use App\StockMedicamentoHistorial;
use App\ClienteUser;
use App\Cliente;

use Carbon\Carbon;

use App\Http\Traits\Clientes;
use App\Http\Traits\StockMedicamentos;


class EmpleadosStockMedicamentoController extends Controller
{
	use Clientes,StockMedicamentos;

	public function index()
	{
		$clientes = $this->getClientesUser();
		return view('empleados.medicamentos', compact('clientes'));
	}
	public function busqueda(Request $request)
	{
		return $this->searchStock($request);
	}


	public function movimientos()
	{

	  $clientes = $this->getClientesUser();
	  return view('empleados.medicamentos_movimientos', compact('clientes'));

	}
	public function busquedaMovimientos(Request $request)
	{
		return $this->searchHistorial($request);
	}



	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{

	  $clientes = $this->getClientesUser();

	  $medicamentos = Medicamento::orderBy('nombre', 'asc')->get();
	  return view('empleados.medicamentos.create', compact('clientes', 'medicamentos'));

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
		'medicamento' => 'required',
		'ingreso' => 'required|numeric',
		'fecha_ingreso' => 'required',
		'motivo' => 'required|string'
	  ]);

	  //dd(auth()->user()->id_cliente_actual);

	  $fecha_ingreso = Carbon::createFromFormat('d/m/Y', $request->fecha_ingreso);

	  $medicamento_existente = StockMedicamento::where('id_medicamento', $request->medicamento)
	  ->where('id_cliente', auth()->user()->id_cliente_actual)
	  ->first();

	  if (isset($medicamento_existente) && !empty($medicamento_existente) && !is_null($medicamento_existente)) {

			//Actualizar en base uno existente
			$medicamento_existente->ingreso = $medicamento_existente->ingreso + $request->ingreso;
			$medicamento_existente->stock = $medicamento_existente->stock + $request->ingreso;
			$medicamento_existente->save();

			//Actualizar en base el historial
			$stock_medicamentos_historial = new StockMedicamentoHistorial();
			$stock_medicamentos_historial->id_stock_medicamentos = $medicamento_existente->id;
			$stock_medicamentos_historial->ingreso = $request->ingreso;
			$stock_medicamentos_historial->fecha_ingreso = $fecha_ingreso;
			$stock_medicamentos_historial->motivo = $request->motivo;
			$stock_medicamentos_historial->save();

		  }else {
				//Guardar en base La primera vez que se crea
				$stock_medicamento = new StockMedicamento();
				$stock_medicamento->id_medicamento = $request->medicamento;
				$stock_medicamento->id_user = auth()->user()->id;
				$stock_medicamento->id_cliente = auth()->user()->id_cliente_actual;
				$stock_medicamento->ingreso = $request->ingreso;
				$stock_medicamento->stock = $request->ingreso;
				$stock_medicamento->suministrados = 0;
				$stock_medicamento->egreso = 0;
				$stock_medicamento->fecha_ingreso = $fecha_ingreso;
				$stock_medicamento->motivo = $request->motivo;
				$stock_medicamento->save();


				//Guardar en base
				$stock_medicamentos_historial = new StockMedicamentoHistorial();
				$stock_medicamentos_historial->id_stock_medicamentos = $stock_medicamento->id;
				$stock_medicamentos_historial->ingreso = $request->ingreso;
				$stock_medicamentos_historial->stock = $request->ingreso;
				$stock_medicamentos_historial->suministrados = 0;
				$stock_medicamentos_historial->egreso = 0;
				$stock_medicamentos_historial->fecha_ingreso = $fecha_ingreso;
				$stock_medicamentos_historial->motivo = $request->motivo;
				$stock_medicamentos_historial->save();
	  }


	  return redirect('empleados/medicamentos')->with('success', 'Medicamento cargado con éxito');

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


	  //Actualizar en base
	  $stock_medicamento = StockMedicamento::findOrFail($id);
		///dd($stock_medicamento);

		////CAMBIAR POR STOCK EN VEZ DE INGRESO?
	  if ($request->egreso > $stock_medicamento->stock) {
			return back()->withInput()->with('error', 'No pueden egresar mas unidades que las disponibles en el stock.');
	  }

		//dd($stock_medicamento);

	  $stock_medicamento->egreso = $stock_medicamento->egreso + $request->egreso;
	  $stock_medicamento->stock = $stock_medicamento->stock - $request->egreso;
	  $stock_medicamento->save();

	  $stock_medicamento_historial = new StockMedicamentoHistorial();
	  $stock_medicamento_historial->id_stock_medicamentos = $id;
	  $stock_medicamento_historial->egreso = $request->egreso;
	  $stock_medicamento_historial->fecha_ingreso = $stock_medicamento->fecha_ingreso;
	  $stock_medicamento_historial->motivo = $request->motivo;
	  $stock_medicamento_historial->user_id = auth()->user()->id;
	  $stock_medicamento_historial->save();

	  return redirect('empleados/medicamentos')->with('success', 'Stock del medicamento actualizado correctamente.');

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{

	  $stock_medicamento = StockMedicamento::find($id)->delete();
	  return redirect('empleados/medicamentos')->with('success', 'Medicamento eliminado correctamente');

	}

	public function stock_actual($medicamento_id)
	{
		return StockMedicamento::select('*')
			->where('id_cliente',auth()->user()->id_cliente_actual)
			->whereHas('medicamento',function($query) use($medicamento_id){
			$query->where('id',$medicamento_id);
			})->first();
	}



	public function exportarHistorial(Request $request){

		if (!auth()->user()->id_cliente_actual) {
			return back()->with('error', 'Debes seleccionar un cliente!');
		}

		$cliente = Cliente::findOrFail(auth()->user()->id_cliente_actual);

		$request->start = 0;
		$request->length = 15000;
		$request->draw = 1;

		$request->columns = [
			[
				'name'=>'motivo'
			]
		];
		$request->order = [
			[
				'column'=>0,
				'dir'=>'desc'
			]
		];

		$response = $this->searchHistorial($request);
		//dd($response['data']->toArray());

		$historial = $response['data'];

		//dd($historial[0]);


		$hoy = Carbon::now();
		$file_name = 'movimiento_medicamentos_'.Str::slug($cliente->nombre,'_','es').'_'.$hoy->format('YmdHis').'.csv';

		$fp = fopen('php://memory', 'w');
		fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
		fputcsv($fp,[
			'Medicamento',
			'Tipo Consulta',
			'Cargado por',
			'Empresa',
			'Para',
			'Ingreso',
			'Suministrados',
			'Egreso',
			'Motivo',
			'Registro Creado'
		],';');

		foreach($historial as $history){
			///$estado = $nomina->estado ? 'activo' : 'inactivo';
			$tipo = '[Ingreso/Egreso]';
			if($history->id_consulta_enfermeria) $tipo = 'Enfermería';
			if($history->id_consulta_medica) $tipo = 'Médica';

			fputcsv($fp,[
				$history->stock_medicamento->medicamento->nombre,
				$tipo,
				$history->user ?? $history->stock_medicamento->user->nombre,
				$history->stock_medicamento->cliente->nombre,
				($history->trabajador ?? '[no aplica]'),
				(is_null($history->ingreso) || $history->ingreso==0 ? '' : $history->ingreso),
				(is_null($history->suministrados) || $history->suministrados==0 ? '' : $history->suministrados),
				(is_null($history->egreso) || $history->egreso==0 ? '' : $history->egreso),
				$history->motivo,
				$history->created_at
			],';');
		}
		fseek($fp, 0);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="'.$file_name.'";');
		fpassthru($fp);

	}

	public function exportar(Request $request){

		if (!auth()->user()->id_cliente_actual) {
			return back()->with('error', 'Debes seleccionar un cliente!');
		}

		$cliente = Cliente::findOrFail(auth()->user()->id_cliente_actual);
		///dd($cliente);

		$response = $this->searchStock($request);
		$medicamentos = $response['data'];

		///dd($medicamentos);


		$hoy = Carbon::now();
		$file_name = 'listado_medicamentos_'.Str::slug($cliente->nombre,'_','es').'_'.$hoy->format('YmdHis').'.csv';

		$fp = fopen('php://memory', 'w');
		fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
		fputcsv($fp,[
			'Medicamento',
			'Ingreso',
			'Suministrados',
			'Egreso',
			'Stock'
		],';');

		foreach($medicamentos as $medicamento){
			//var_dump($medicamento->medicamento);
			fputcsv($fp,[
				$medicamento->nombre,
				$medicamento->ingreso,
				$medicamento->suministrados,
				$medicamento->egreso,
				$medicamento->stock
			],';');
		}
		fseek($fp, 0);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="'.$file_name.'";');
		fpassthru($fp);

	}


}
