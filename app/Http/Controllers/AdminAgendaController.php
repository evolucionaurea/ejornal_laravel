<?php

namespace App\Http\Controllers;

use App\Agenda;
use App\Cliente;
use App\HorarioBloqueo;
use App\Nomina;
use App\User;
use Illuminate\Http\Request;

class AdminAgendaController extends Controller
{
    
    public function index()
    {
        $users   = User::where('id_rol',2)->get();
        $clientes = Cliente::all();
        return view('admin.agendas', compact('users','clientes'));
    }

    public function getBloqueos(Request $request)
    {
        $data = $request->validate([
          'user_id'    => 'required|exists:users,id',
          'cliente_id' => 'required|exists:clientes,id'
        ]);

        $bloqueos = HorarioBloqueo::where('user_id', $data['user_id'])
                ->where('cliente_id', $data['cliente_id'])
                ->get()
                ->groupBy('dia_semana')
                ->map(function($group){
                    return $group->map(fn($b)=>[
                    'id'         => $b->id,
                    'start'      => $b->hora_inicio,
                    'end'        => $b->hora_fin
                    ]);
                });

        return response()->json($bloqueos);
    }


    public function storeBloqueos(Request $request)
    {
        $data = $request->validate([
            'user_id'          =>'required|exists:users,id',
            'cliente_id'       =>'required|exists:clientes,id',
            'dia'              =>'required|integer|between:0,6',
            'bloqueos'         =>'required|array',
            'bloqueos.*.start' =>'required|date_format:H:i',
            'bloqueos.*.end'   =>'required|date_format:H:i|after:bloqueos.*.start',
        ]);

        HorarioBloqueo::where([
          ['user_id',$data['user_id']],
          ['cliente_id',$data['cliente_id']],
          ['dia_semana',$data['dia']],
        ])->delete();

        foreach($data['bloqueos'] as $b){
            HorarioBloqueo::create([
              'user_id'     =>$data['user_id'],
              'cliente_id'  =>$data['cliente_id'],
              'dia_semana'  =>$data['dia'],
              'hora_inicio' =>$b['start'],
              'hora_fin'    =>$b['end'],
            ]);
        }

        return response()->json(['success'=>true]);
    }

    public function destroyBloqueos($id)
    {
        $hb = HorarioBloqueo::findOrFail($id);
        $hb->delete();

        return response()->json(['success' => true]);
    }


    public function getAgendaEvents(Request $request)
    {
        $q = Agenda::with(['cliente', 'trabajador', 'estado', 'user']);

        if ($request->filled('cal_user')) {
            $q->where('user_id', $request->cal_user);
        }
        if ($request->filled('cal_cliente')) {
            $q->where('cliente_id', $request->cal_cliente);
        }

        $eventos = $q->get()->map(function ($a) {
            return [
                'id'    => $a->id,
                'title' => ($a->cliente->nombre ?? 'Sin cliente') . ' – ' . ($a->estado->nombre ?? ''),
                'start' => optional($a->fecha_inicio)->toIso8601String(),
                'end'   => optional($a->fecha_final)->toIso8601String(),
                'extendedProps' => [
                    'usuario'    => $a->user->nombre ?? '',
                    'trabajador' => $a->trabajador->nombre ?? '',
                    'comentarios'=> $a->comentarios ?? '',
                ]
            ];
        });

        return response()->json($eventos);
    }



}
