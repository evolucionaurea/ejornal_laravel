<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Medicamento;


class AdminMedicamentosController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		/*$medicamentos = Medicamento::all();
		$medicamentos->forget([2,3]);
		dd($medicamentos);*/

		return view('admin.medicamentos');
	}

	public function busqueda(Request $request)
	{
		$query = Medicamento::select('*');
		//$query = DB::select(DB::raw("SELECT m.*, (SELECT SUM(sm.stock) FROM stock_medicamentos sm WHERE sm.id_medicamento=m.id) stock_total FROM medicamentos m"));
		if(isset($request->medicamento)) $query->where('id',$request->medicamento);

		//app()->call('App\Http\Controllers\AdminMedicamentosController@busqueda',['stock'=>1])


		$medicamentos = $query->get();
		$forget = [];

		if($medicamentos){
			foreach($medicamentos as $k=>$medicamento){

				$stock = $medicamento->stock_medicamento()->sum('stock');
				$medicamentos[$k]->stock_total = $stock;

				if(isset($request->stock)){
					if($request->stock && !$stock) $forget[] = $k;
					if(!$request->stock && $stock) $forget[] = $k;
				}
				///if(isset($request->stock) && !$request->stock && $stock) continue;

				$medicamentos[$k]->suministrados_total = $medicamento->stock_medicamento()->sum('suministrados');
			}
		}

		if($forget) $medicamentos->forget($forget);

		return [
			'results'=>array_values($medicamentos->toArray()),
			'request'=>$request->all(),
			'forget'=>$forget
		];

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

		$validatedData = $request->validate([
			'nombre' => 'required|string'
		]);

		//Guardar en base
		$medicamento = new Medicamento();
		$medicamento->nombre = $request->nombre;
		$medicamento->save();

		return back()->with('success', 'Medicamento guardado con éxito');

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
		$medicamento = Medicamento::findOrFail($id);
		return view('admin.medicamentos.edit', compact('medicamento'));

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
			'nombre' => 'required|string'
		]);

		//Actualizar en base
		$medicamento = Medicamento::findOrFail($id);
		$medicamento->nombre = $request->nombre;
		$medicamento->save();

		return redirect('admin/medicamentos')->with('success', 'Medicamento actualizado con éxito');

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$medicamento = Medicamento::find($id)->delete();
		return back()->with('success', 'Medicamento eliminado correctamente');
	}
}
