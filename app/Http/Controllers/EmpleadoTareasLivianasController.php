<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\Clientes;
use App\TareaLivianaTipo;
use App\TareaLiviana;
use Carbon\Carbon;
use App\TipoComunicacionLiviana;
use App\Nomina;
use Illuminate\Support\Facades\DB;

class EmpleadoTareasLivianasController extends Controller
{

    use Clientes;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$fecha_actual = Carbon::now();
		$clientes = $this->getClientesUser();
		$tipos = TareaLivianaTipo::get();

		return view('empleados.tareas_livianas', compact('clientes','tipos'));
    }


    public function busqueda(Request $request)
	{
	  $query = TareaLiviana::select(
	  	'tareas_livianas.*',
	  	'nominas.nombre',
	  	'nominas.email',
	  	'nominas.telefono',
	  	'nominas.dni',
	  	'nominas.estado',
	  	'nominas.sector',
	  	'tareas_livianas_tipos.nombre as nombre_ausentismo'
	  )
	  ->join('nominas', 'tareas_livianas.id_trabajador', 'nominas.id')
	  ->join('tareas_livianas_tipos', 'tareas_livianas.id_tipo', 'tareas_livianas_tipos.id')
	  ->where('nominas.id_cliente', auth()->user()->id_cliente_actual);

	  $query->where(function($query) use ($request) {
			$filtro = '%'.$request->search['value'].'%';
			$query->where('nominas.nombre','like',$filtro)
				->orWhere('nominas.email','like',$filtro)
				->orWhere('nominas.dni','like',$filtro)
				->orWhere('nominas.telefono','like',$filtro);
		});

		if($request->from) $query->whereDate('tareas_livianas.fecha_inicio','>=',Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
		if($request->to) $query->whereDate('tareas_livianas.fecha_final','<=',Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
		if($request->tipo) $query->where('id_tipo',$request->tipo);

		if($request->order){
			$sort = $request->columns[$request->order[0]['column']]['name'];
			$dir  = $request->order[0]['dir'];
			$query->orderBy($sort,$dir);
		}

		return [
			'draw'=>$request->draw,
			'recordsTotal'=>$query->count(),
			'recordsFiltered'=>$query->count(),
			'data'=>$query->skip($request->start)->take($request->length)->get(),
			'fichada_user'=>auth()->user()->fichada,
			'request'=>$request->all()
		];

	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $trabajadores = Nomina::where('id_cliente', auth()->user()->id_cliente_actual)->orderBy('nombre', 'asc')->get();
		$tareas_livianas_tipo = TareaLivianaTipo::orderBy('nombre', 'asc')->get();
		$clientes = $this->getClientesUser();
		$tipo_comunicacion_liviana = TipoComunicacionLiviana::orderBy('nombre', 'asc')->get();

		return view('empleados.tareas_livianas.create', compact('trabajadores', 'tareas_livianas_tipo', 'clientes', 'tipo_comunicacion_liviana'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $validatedData = $request->validate([
        //     'trabajador' => 'required',
        //     'tipo' => 'required',
        //     'fecha_inicio' => 'required',
        //     'tipo_comunicacion' => 'required',
        //     'descripcion' => 'required|string'
        //   ]);
        //   $fecha_actual = Carbon::now();
        //   $fecha_inicio = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio);
    
    
        //     $dos_dias_adelante = Carbon::now()->addDays(2);
        //     $un_anio_atras = Carbon::now()->subYear(1);
        //     if ($fecha_inicio > $dos_dias_adelante) {
        //       return back()->withInput()->with('error', 'La fecha de inicio no puede ser superior a dos dias adelante de la fecha actual');
        //     }
        //     if ($fecha_inicio->lessThan($un_anio_atras)) {
        //       return back()->withInput()->with('error', 'La fecha de inicio puede ser hasta un año atrás, no mas');
        //     }
    
        //   if (isset($request->fecha_final) && !empty($request->fecha_final) && !is_null($request->fecha_final)) {
        //     $fecha_final = Carbon::createFromFormat('d/m/Y', $request->fecha_final);
    
        //     if ($fecha_inicio->greaterThan($fecha_final)) {
        //       return back()->withInput()->with('error', 'La fecha de inicio no puede ser superior a la fecha final o quizás lo dejó vacío');
        //     }
        //   }
    
        //   if (isset($request->fecha_regreso_trabajar) && !empty($request->fecha_regreso_trabajar) && !is_null($request->fecha_regreso_trabajar)) {
        //     $fecha_regreso_trabajar = Carbon::createFromFormat('d/m/Y', $request->fecha_regreso_trabajar);
    
        //     if (!isset($request->fecha_final) && empty($request->fecha_final) && !is_null($request->fecha_final)) {
        //         return back()->withInput()->with('error', 'No puedes ingresar una fecha de regreso al trabajo sin cargar una fecha final');
        //       }
    
        //     if ($fecha_final->greaterThan($fecha_regreso_trabajar)) {
        //       return back()->withInput()->with('error', 'La fecha final no puede ser mayor que la fecha de regreso al trabajo');
        //     }
        //   }
    
    
        //     //Guardar en base Ausentismo
        //     $ausentismo = new Ausentismo();
        //     $ausentismo->id_trabajador = $request->trabajador;
        //     $ausentismo->id_tipo = $request->tipo;
        //     $ausentismo->fecha_inicio = $fecha_inicio;
        //     if (isset($request->fecha_final) && !empty($request->fecha_final)) {
        //       $ausentismo->fecha_final = $fecha_final;
        //     }else {
        //       $ausentismo->fecha_final = null;
        //     }
        //     if (isset($request->fecha_regreso_trabajar) && !empty($request->fecha_regreso_trabajar)) {
        //       $ausentismo->fecha_regreso_trabajar = $fecha_regreso_trabajar;
        //     }else {
        //       $ausentismo->fecha_regreso_trabajar = null;
        //     }
    
        //     // Si hay un archivo adjunto
        //     if ($request->hasFile('archivo') && $request->file('archivo') > 0) {
    
        //       $archivo = $request->file('archivo');
        //       $nombre = $archivo->getClientOriginalName();
        //       $ausentismo->archivo = $nombre;
    
        //     }
        //     $ausentismo->user = auth()->user()->nombre;
        //     $ausentismo->save();
    
        //     // Si hay un archivo adjunto
        //     if ($request->hasFile('archivo') && $request->file('archivo') > 0) {
    
        //     Storage::disk('local')->put('ausentismos/trabajador/'.$ausentismo->id, $archivo);
    
        //     // Completar el base el hasg del archivo guardado
        //     $ausentismo = Ausentismo::findOrFail($ausentismo->id);
        //     $ausentismo->hash_archivo = $archivo->hashName();
        //     $ausentismo->save();
    
        //     }
    
    
        //     //Guardar en base Comunicacion
        //     $comunicacion = new Comunicacion();
        //     $comunicacion->id_ausentismo = $ausentismo->id;
        //     $comunicacion->id_tipo = $request->tipo_comunicacion;
        //     $comunicacion->descripcion = $request->descripcion;
        //     $comunicacion->save();
    
    
          return redirect('empleados/tareas_livianas')->with('success', 'Tarea Liviana y Comunicación guardados con éxito');
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
