<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Traits\Clientes;
use App\User;
use App\Agenda;
use App\AgendaEstado;

use Carbon\CarbonImmutable;

class EmpleadosAgendaController extends Controller
{

	use Clientes;

	public function index(){

		$clientes = $this->getClientesUser();
		return view('empleados.agenda',compact('clientes'));
	}

	public function store(Request $request){

		//chequear si tiene cliente asignado
		if( !auth()->user()->id_cliente_actual ){
			return response()->json([
				'success' => false,
				'message' => 'No tienes ningún cliente asociado.'
			], 400);
		}

		$fecha_inicio = CarbonImmutable::createFromFormat('d/m/Y H:i', $request->fecha_inicio.' '.$request->hora.':'.$request->minutos);
		$fecha_final = $fecha_inicio->addMinutes($request->duracion);

		//chequear si se superpone con otro turno del mismo usuario
		$turnos_existentes_user = $this->chequear_superposicion($fecha_inicio,$fecha_final,'user',$request);
		if($turnos_existentes_user->count()){
			return response()->json([
				'success' => false,
				'message' => 'La fecha y horario seleccionado se superpone con otro turno ya registrado.',
				'turnos'=>$turnos_existentes_user
			], 400);
		}

		//chequear si se superpone con otro turno del mismo trabajdor
		$turnos_existentes_trabajador = $this->chequear_superposicion($fecha_inicio,$fecha_final,'trabajdor',$request);
		if($turnos_existentes_trabajador->count()){
			return response()->json([
				'success' => false,
				'message' => 'La fecha y horario seleccionado se superpone con otro turno ya registrado para el mismo trabajador.',
				'turnos'=>$turnos_existentes_trabajador
			], 400);
		}




		if(!$agenda_estado = AgendaEstado::where('referencia','confirmed')->first()){
			return response()->json([
				'success' => false,
				'message' => 'Estado predeterminado no encontrado'
			], 400);
		}



		$agenda = new Agenda;
		$agenda->estado_id = $agenda_estado->id;
		$agenda->user_id = auth()->user()->id;
		$agenda->cliente_id = auth()->user()->id_cliente_actual;
		$agenda->nomina_id = $request->nomina_id;
		$agenda->fecha_inicio = $fecha_inicio;
		$agenda->fecha_final = $fecha_final;
		$agenda->comentarios = $request->comentarios;
		$agenda->save();


		return response()->json([
			'success' => true,
			'message'=>'Turno agendado correctamente'
		]);

	}


	public function chequear_superposicion($fecha_inicio,$fecha_final,$mode='user',Request $request){

		$query = Agenda::where(function($query) use ($fecha_inicio,$fecha_final){
			$query
				->where(function($query) use ($fecha_inicio){
					$query
						->where('fecha_inicio','<=',$fecha_inicio)
						->where('fecha_final','>',$fecha_inicio);
				})

				->orWhere(function($query) use ($fecha_final){
					$query
						->where('fecha_inicio','<=',$fecha_final)
						->where('fecha_final','>=',$fecha_final);
				})

				->orWhere(function($query) use ($fecha_inicio,$fecha_final){
					$query
						->where('fecha_inicio','>',$fecha_inicio)
						->where('fecha_final','<',$fecha_final);
				});
		})
		->whereHas('estado',function($query){
			$query->where('referencia','=','confirmed');
		});

		if($mode=='user'){
			$query->where('user_id',auth()->user()->id);
		}
		if($mode=='trabajador'){
			$query->where('nomina_id',$request->nomina_id);
		}


		return $query->get();
	}

}
