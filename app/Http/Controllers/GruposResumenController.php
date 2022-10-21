<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Grupo;
use App\User;
use App\ClienteGrupo;
use App\Ausentismo;
use App\Http\Traits\ClientesGrupo;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class GruposResumenController extends Controller
{
	use ClientesGrupo;
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		setlocale(LC_TIME, 'Spanish');

		//DB::enableQueryLog();

		// el metodo getClientesGrupo heredada de App\Http\Traits\ClientesGrupo
		$clientes_grupo = $this->getClientesGrupo();


		$today = CarbonImmutable::now();

		// todos las empresas del grupo
		$clientes_nominas = $clientes_grupo['grupo']->fresh(['clientes'=>function($query) use ($today){
			$query->select('clientes.id','clientes.nombre')

				//total nómina
				->withCount('nominas')

				//ausentes hoy
				->withCount(['ausentismos'=>function($query) use ($today) {
					$query
						->where('fecha_regreso_trabajar',null)
						->orWhere('fecha_regreso_trabajar','>=',$today);
				}])

				//ausentismos este mes
				->withCount(['ausentismos_mes'=>function($query) use ($today) {
					$query
						->where('fecha_inicio','>=',$today->startOfMonth());
				}])

				//ausentismos este año
				->withCount(['ausentismos_year'=>function($query) use ($today) {
					$query
						->where('fecha_inicio','>=',$today->firstOfYear());
				}]);


		}]);

		///dd( $clientes_grupo['cliente_actual'] );


		$output = array_merge($clientes_grupo,[
			'clientes_nominas'=>$clientes_nominas
		]);

		return view('grupos.resumen',$output);
	}

	public function index_cliente()
	{

		setlocale(LC_TIME, 'Spanish');

		$clientes_grupo = $this->getClientesGrupo();
		return view('grupos.resumen_cliente',$clientes_grupo);
	}

	public function ausentismos_resumen()
	{

		$today = CarbonImmutable::now();

		DB::enableQueryLog();

		$ausentismos_mes = Ausentismo::selectRaw('count(*) as total, id_tipo')
			->with('tipo')
			->where('fecha_inicio','>=',$today->startOfMonth())
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_actual);
			})
			->groupBy('id_tipo')
			->get();

		$query = DB::getQueryLog();

		$ausentismos_year = Ausentismo::groupBy('id_tipo')
			->with('tipo')
			->whereIn('id_trabajador',function($query){
				$query->select('id')
					->from('nominas')
					->where('id_cliente',auth()->user()->id_cliente_actual);
			})
			->selectRaw('count(*) as total, id_tipo')
			->where('fecha_inicio','>=',$today->firstOfYear())
			->get();

		return [
			'status'=>'ok',
			'ausentismos_mes'=>$ausentismos_mes,
			'ausentismos_anual'=>$ausentismos_year,
			'query'=>$query
		];
	}

	public function clienteActual(Request $request)
	{
		$user = User::findOrFail(auth()->user()->id);
		$user->id_cliente_actual = intval($request->id_cliente);
		$user->save();
		return ['status'=>'ok'];
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
		//
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
