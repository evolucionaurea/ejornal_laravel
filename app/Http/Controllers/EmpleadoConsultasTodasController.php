<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\Clientes;
use App\ConsultaMedica;
use App\ConsultaEnfermeria;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class EmpleadoConsultasTodasController extends Controller
{
    use Clientes;


    public function index(Request $request)
	{

		$ahora = Carbon::now();

		switch ($request->filtro) {
			case 'mes':
				$fecha_inicio = $ahora->format('01/m/Y');
				$fecha_final = $ahora->format('d/m/Y');
				break;

			case 'hoy':
				$fecha_inicio = $ahora->format('d/m/Y');
				$fecha_final = $ahora->format('d/m/Y');
				break;

			default:
				$fecha_inicio = false;
				$fecha_final = false;
				break;
		}

		$clientes = $this->getClientesUser();
		return view('empleados.consultas.todas', compact('clientes','fecha_inicio','fecha_final'));
	}


    public function busqueda(Request $request)
    {
        $medicas = ConsultaMedica::select(
            'nominas.nombre',
            'consultas_medicas.id',
            'consultas_medicas.id_nomina',
            'consultas_medicas.id_diagnostico_consulta',
            'consultas_medicas.fecha',
            'consultas_medicas.derivacion_consulta',
            'consultas_medicas.tratamiento',
            'diagnostico_consulta.nombre as diagnostico',
            DB::raw('"medica" as tipo') // Agregamos un campo tipo para identificar consultas médicas
        )
        ->join('nominas', 'consultas_medicas.id_nomina', 'nominas.id')
        ->join('diagnostico_consulta', 'consultas_medicas.id_diagnostico_consulta', 'diagnostico_consulta.id')
        ->where('nominas.id_cliente', auth()->user()->id_cliente_actual)
        ->orderBy('consultas_medicas.fecha', 'desc');

        if ($request->from) {
            $medicas->whereDate('consultas_medicas.fecha', '>=', Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
        }
        if ($request->to) {
            $medicas->whereDate('consultas_medicas.fecha', '<=', Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
        }

        if ($request->search) {
            $filtro = '%' . $request->search['value'] . '%';
            $medicas->where(function ($query) use ($filtro) {
                $query->where('nominas.nombre', 'like', $filtro)
                    ->orWhere('consultas_medicas.derivacion_consulta', 'like', $filtro)
                    ->orWhere('consultas_medicas.tratamiento', 'like', $filtro)
                    ->orWhere('diagnostico_consulta.nombre', 'like', $filtro);
            });
        }

        $enfermerias = ConsultaEnfermeria::select(
            'nominas.nombre',
            'consultas_enfermerias.id',
            'consultas_enfermerias.id_nomina',
            'consultas_enfermerias.id_diagnostico_consulta',
            'consultas_enfermerias.fecha',
            'consultas_enfermerias.derivacion_consulta',
            'diagnostico_consulta.nombre as diagnostico',
            DB::raw('"enfermeria" as tipo') // Agregamos un campo tipo para identificar consultas de enfermería
        )
        ->join('nominas', 'consultas_enfermerias.id_nomina', 'nominas.id')
        ->join('diagnostico_consulta', 'consultas_enfermerias.id_diagnostico_consulta', 'diagnostico_consulta.id')
        ->where('nominas.id_cliente', auth()->user()->id_cliente_actual)
        ->orderBy('consultas_enfermerias.fecha', 'desc');

        if ($request->from) {
            $enfermerias->whereDate('consultas_enfermerias.fecha', '>=', Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
        }
        if ($request->to) {
            $enfermerias->whereDate('consultas_enfermerias.fecha', '<=', Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
        }

        if ($request->search) {
            $filtro = '%' . $request->search['value'] . '%';
            $enfermerias->where(function ($query) use ($filtro) {
                $query->where('nominas.nombre', 'like', $filtro)
                    ->orWhere('consultas_enfermerias.derivacion_consulta', 'like', $filtro)
                    ->orWhere('diagnostico_consulta.nombre', 'like', $filtro);
            });
        }

        $totalMedicas = $medicas->count();
        $totalEnfermerias = $enfermerias->count();

        $total = $totalMedicas + $totalEnfermerias;

        $dataMedicas = $medicas->skip($request->start)->take($request->length)->get();
        $dataEnfermerias = $enfermerias->skip($request->start)->take($request->length)->get();

        $data = $dataMedicas->concat($dataEnfermerias);

        return [
            'draw' => $request->draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data,
            'fichada_user' => auth()->user()->fichada,
            'fichar_user' => auth()->user()->fichar,
            'request' => $request->all()
        ];
    }




    public function exportar(Request $request) {
        if (!auth()->user()->id_cliente_actual) {
            return back()->with('error', 'Debes trabajar para algun cliente para utilizar esta funcionalidad');
        }

        $queryMedicas = ConsultaMedica::select('consultas_medicas.*', 'nominas.nombre', 'nominas.email', 'diagnostico_consulta.nombre as diagnostico')
            ->join('nominas', 'nominas.id', 'consultas_medicas.id_nomina')
            ->join('diagnostico_consulta', 'diagnostico_consulta.id', 'consultas_medicas.id_diagnostico_consulta')
            ->where('nominas.id_cliente', auth()->user()->id_cliente_actual)
            ->orderBy('consultas_medicas.fecha', 'desc');

        $queryEnfermerias = ConsultaEnfermeria::select('consultas_enfermerias.*', 'nominas.nombre', 'nominas.email', 'diagnostico_consulta.nombre as diagnostico')
            ->join('nominas', 'nominas.id', 'consultas_enfermerias.id_nomina')
            ->join('diagnostico_consulta', 'diagnostico_consulta.id', 'consultas_enfermerias.id_diagnostico_consulta')
            ->where('nominas.id_cliente', auth()->user()->id_cliente_actual)
            ->orderBy('consultas_enfermerias.fecha', 'desc');

        if ($request->from) {
            $queryMedicas->whereDate('consultas_medicas.fecha', '>=', Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
            $queryEnfermerias->whereDate('consultas_enfermerias.fecha', '>=', Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d'));
        }
        if ($request->to) {
            $queryMedicas->whereDate('consultas_medicas.fecha', '<=', Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
            $queryEnfermerias->whereDate('consultas_enfermerias.fecha', '<=', Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d'));
        }

        $consultasMedicas = $queryMedicas->get();
        $consultasEnfermerias = $queryEnfermerias->get();

        if ($consultasMedicas->isEmpty() && $consultasEnfermerias->isEmpty()) {
            return back()->with('error', 'No se encontraron consultas');
        }

        $hoy = Carbon::now();
        $file_name = 'consultas-' . $hoy->format('YmdHis') . '.csv';

        $fp = fopen('php://memory', 'w');
        fprintf($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($fp, [
            'Tipo',
            'Trabajador',
            'Email',
            'Fecha',
            'Diagnóstico',
            'Derivación',
            'Amerita Salida',
            'Peso',
            'Altura',
            'IMC',
            'Glucemia',
            'Saturación Oxígeno',
            'Tensión Arterial',
            'Frec. Cardíaca',
            'Tratamiento',
            'Observaciones',
        ], ';');

        foreach ($consultasMedicas as $consulta) {
            fputcsv($fp, [
                'Medica',
                $consulta->nombre,
                $consulta->email,
                $consulta->fecha,
                $consulta->diagnostico,
                $consulta->derivacion_consulta,
                ($consulta->amerita_salida ? 'Si' : 'No'),
                $consulta->peso,
                $consulta->altura,
                $consulta->imc,
                $consulta->glucemia,
                $consulta->saturacion_oxigeno,
                $consulta->tension_arterial,
                $consulta->frec_cardiaca,
                $consulta->tratamiento,
                $consulta->observaciones
            ], ';');
        }

        foreach ($consultasEnfermerias as $consulta) {
            fputcsv($fp, [
                'Enfermeria',
                $consulta->nombre,
                $consulta->email,
                $consulta->fecha,
                $consulta->diagnostico,
                $consulta->derivacion_consulta,
                ($consulta->amerita_salida ? 'Si' : 'No'),
                $consulta->peso,
                $consulta->altura,
                $consulta->imc,
                $consulta->glucemia,
                $consulta->saturacion_oxigeno,
                $consulta->tension_arterial,
                $consulta->frec_cardiaca,
                $consulta->tratamiento,
                $consulta->observaciones
            ], ';');
        }

        fseek($fp, 0);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $file_name . '";');
        fpassthru($fp);

        return;
    }









}
