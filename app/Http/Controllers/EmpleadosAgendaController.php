<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Traits\Clientes;
use App\User;
use App\Agenda;
use App\AgendaEstado;
use App\AgendaMotivo;
use App\HorarioBloqueo;
use Carbon\CarbonImmutable;

class EmpleadosAgendaController extends Controller
{

	use Clientes;

	public function index(){

		$clientes = $this->getClientesUser();

		$now = CarbonImmutable::now();

		$turnos = Agenda::where('cliente_id',auth()->user()->id_cliente_actual)
			//->where('user_id',auth()->user()->id)
			->where('fecha_inicio','>=',$now)
			->with(['trabajador','user','user_registra','estado'])
			->orderBy('fecha_inicio','asc')
			->take(5)->get();


		return view('empleados.agenda',compact('clientes','turnos'));
	}

	public function store(Request $request){

		$validatedData = $request->validate([
			'fecha_inicio' => 'required',
			'horario' => 'required',
			'duracion' => 'required',
			'user_id' => 'required',
			'nomina_id' => 'required'
		]);


		//chequear si tiene cliente asignado
		if( !auth()->user()->id_cliente_actual ){
			return response()->json([
				'success' => false,
				'message' => 'No tienes ningún cliente asociado.'
			], 400);
		}

		
		$fecha_inicio = CarbonImmutable::createFromFormat('d/m/Y H:i', $request->fecha_inicio.' '.$request->horario);
		$fecha_final = $fecha_inicio->addMinutes($request->duracion);
		
		if(!$agenda_estado = AgendaEstado::where('referencia','confirmed')->first()){
			return response()->json([
				'success' => false,
				'message' => 'Estado predeterminado no encontrado'
			], 400);
		}
		
		
		
		if($request->id){
			$agenda = Agenda::find($request->id);
		}else{
			$agenda = new Agenda;
		}
		//dd($agenda->toJson());
		
		//chequear si se superpone con otro turno del mismo usuario y cliente
		$turnos_existentes_user = $this->chequearSuperposicion($fecha_inicio,$fecha_final,'user',$request);
		if($turnos_existentes_user->count()){
			return response()->json([
				'success' => false,
				'message' => 'La fecha y horario seleccionado se superpone con otro turno ya registrado para el usuario seleccionado.',
				'turnos'=>$turnos_existentes_user
			], 400);
		}

		//chequear si se superpone con otro turno del mismo trabajdor
		$turnos_existentes_trabajador = $this->chequearSuperposicion($fecha_inicio,$fecha_final,'trabajador',$request);
		if($turnos_existentes_trabajador->count()){
			return response()->json([
				'success' => false,
				'message' => 'La fecha y horario seleccionado se superpone con otro turno ya registrado para el mismo trabajador.',
				'turnos'=>$turnos_existentes_trabajador
			], 400);
		}


		//$agenda = new Agenda;
		$agenda->estado_id = $agenda_estado->id;
		$agenda->registra_user_id = auth()->user()->id;
		$agenda->user_id = $request->user_id;

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
	public function update(Request $request, $id){

		$turno = Agenda::findOrFail($id);

		if($request->mode=='cancel'){
			if(!$agenda_estado = AgendaEstado::where('referencia','cancelled')->first()){
				return response()->json([
					'success' => false,
					'message' => 'Estado cancelado no encontrado'
				], 400);
			}
			$turno->estado_id = $agenda_estado->id;
		}

		if($request->mode == 'mover'){
			//$fecha_inicio = CarbonImmutable::createFromFormat('d/m/Y H:i', $request->fecha_inicio.' '.$request->horario);
			if($turno->estado->referencia=='cancelled'){
				return response()->json([
					'success' => false,
					'message' => 'El turno ha sido cancelado y no se puede mover'
				], 400);
			}
			$nueva_fecha = CarbonImmutable::parse($request->nueva_fecha)->setTimezone('America/Argentina/Buenos_Aires');
			$fecha_final = $nueva_fecha->addMinutes($turno->duracion);
			$turno->fecha_inicio = $nueva_fecha;
			$turno->fecha_final = $fecha_final;
		}

		$turno->save();

		return response()->json([
			'success'=>true
		]);

	}

	public function chequearSuperposicion($fecha_inicio,$fecha_final,$mode='user',Request $request){

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
		})
		->where('cliente_id',auth()->user()->id_cliente_actual);

		if($request->id){
			$query->where('id','!=',$request->id);
		}

		if($mode=='user'){
			$query->where('user_id',$request->user_id);
		}
		if($mode=='trabajador'){
			$query->where('nomina_id',$request->nomina_id);
		}


		return $query->get();
	}

	public function search(Request $request)
	{

		$from = CarbonImmutable::createFromTimeString($request->from);
		$to = CarbonImmutable::createFromTimeString($request->to);

		$query = Agenda::with(['cliente','trabajador','estado','user','user_registra'])
			->where('fecha_inicio','>=',$from)
			->where('fecha_inicio','<=',$to)
			->where('cliente_id',auth()->user()->id_cliente_actual)
			->get();

		return response()->json([
			'from' => $from,
			'to' => $to,
			'results' => $query
		]);

	}
	public function find($id){

		$turno = Agenda::with(['trabajador','cliente','estado','user'])->find($id);
		return response()->json([
			'turno' => $turno,
			'user' => auth()->user()
		]);
	}


	public function getMotivosAgenda()
	{
		try {
			$motivos = AgendaMotivo::select('id', 'nombre')
				->orderBy('nombre', 'asc')
				->get();
			return response()->json([
					'estado' => true,
					'data' => $motivos
				]);
		} catch (\Throwable $th) {
			return response()->json([
				'estado' => false,
				'data' => $th->getMessage()
			]);
		}

	}

	public function getHorariosBloqueados($id_cliente = null, $id_user = null)
    {
        // Validar que al menos uno esté presente
        if (empty($id_cliente) && empty($id_user)) {
            return response()->json([
				'estado' => false,
                'data' => 'Debe enviar al menos un ID de cliente o de usuario.'
            ], 400);
        }

        $query = HorarioBloqueo::query();

        if (!empty($id_cliente)) {
            $query->where('cliente_id', $id_cliente);
        }

        if (!empty($id_user)) {
            $query->where('user_id', $id_user);
        }

        $resultados = $query->with(['user', 'cliente'])->get();

        return response()->json([
            'estado' => true,
            'data' => $resultados
        ]);
    }

}
