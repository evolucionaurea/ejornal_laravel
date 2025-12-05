<?php

namespace App\Http\Controllers;

use App\Receta;
use App\Cliente;
use App\Nomina;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminRecetasController extends Controller
{
    public function index(Request $request)
    {
        // Listas para los filtros
        $clientes = Cliente::orderBy('nombre', 'asc')
            ->get(['id', 'nombre']);

        $nominas = Nomina::query()
            ->where('estado', 1)
            ->orderBy('nombre', 'asc')
            ->get(['id', 'nombre']);

        // Estados posibles
        $estados = ['emitida', 'anulada', 'error'];

        $q = Receta::with(['nomina', 'cliente']);

        // ---- Filtros ----
        if ($request->filled('f_cliente')) {
            $q->where('id_cliente', (int) $request->input('f_cliente'));
        }

        if ($request->filled('f_nomina')) {
            $q->where('id_nomina', (int) $request->input('f_nomina'));
        }

        if ($request->filled('f_estado')) {
            $q->where('estado', $request->input('f_estado'));
        }

        if ($request->filled('f_desde')) {
            try {
                $desde = Carbon::createFromFormat('Y-m-d', $request->input('f_desde'))->startOfDay();
                $q->where('created_at', '>=', $desde);
            } catch (\Throwable $e) {
                // ignoramos formato inválido
            }
        }

        if ($request->filled('f_hasta')) {
            try {
                $hasta = Carbon::createFromFormat('Y-m-d', $request->input('f_hasta'))->endOfDay();
                $q->where('created_at', '<=', $hasta);
            } catch (\Throwable $e) {
                // ignoramos formato inválido
            }
        }

        // Orden del más nuevo al más viejo
        $recetas = $q
            ->orderByDesc('id')
            ->paginate(20)
            ->appends($request->query());

        // Respuesta parcial para AJAX (filtros / paginación)
        if ($request->ajax()) {
            return view('admin.recetas._tabla', compact('recetas'))->render();
        }

        return view('admin.recetas', compact(
            'recetas',
            'clientes',
            'nominas',
            'estados'
        ));
    }

    public function show($id)
    {
        // Admin puede ver cualquier receta
        $receta = Receta::with(['nomina', 'cliente'])
            ->where('id', $id)
            ->firstOrFail();

        // Normalizar payload / response a arrays
        $payload  = is_string($receta->payload)
            ? json_decode($receta->payload, true)
            : (is_array($receta->payload) ? $receta->payload : []);

        $response = is_string($receta->response)
            ? json_decode($receta->response, true)
            : (is_array($receta->response) ? $receta->response : []);

        // Payload "crudo"
        $payloadPac   = $payload['paciente'] ?? [];
        $payloadMed   = $payload['medico'] ?? [];
        $payloadDiag  = $payload['diagnostico'] ?? null;
        $payloadLugar = $payload['lugarAtencion']['domicilio'] ?? [];

        // Respuesta enriquecida de QBI
        $apiRoot   = $response['response'][0] ?? [];
        $apiPac    = $apiRoot['paciente'] ?? [];
        $apiMed    = $apiRoot['medico'] ?? [];
        $apiMeds   = $apiRoot['medicamentos'] ?? [];
        $apiLugar  = $apiRoot['lugarAtencion']['domicilio'] ?? [];
        $apiCob    = $apiPac['cobertura'] ?? [];
        $apiReceta = $response['recetas'][0] ?? [];

        // Diagnóstico "lindo" (texto completo del repositorio)
        $diagTexto = $apiRoot['diagnostico'] ?? null;

        // Lista de medicamentos a mostrar: priorizamos los enriquecidos
        $listaMeds = !empty($apiMeds)
            ? $apiMeds
            : ($payload['medicamentos'] ?? []);

        // Domicilio definitivo (prioriza lo que responde QBI)
        $domLugar = !empty($apiLugar) ? $apiLugar : $payloadLugar;

        // Estado → clase Bootstrap de badge
        $estadoClass = $receta->estado === 'anulada'
            ? 'danger'
            : ($receta->estado === 'error' ? 'warning' : 'success');

        // Texto ya formateado para la fecha de creación
        $createdAtFormatted = $receta->created_at
            ? $receta->created_at->format('d/m/Y H:i')
            : null;

        return view('admin.recetas.show', compact(
            'receta',
            'payload',
            'response',
            'payloadPac',
            'payloadMed',
            'payloadDiag',
            'payloadLugar',
            'apiRoot',
            'apiPac',
            'apiMed',
            'apiMeds',
            'apiLugar',
            'apiCob',
            'apiReceta',
            'diagTexto',
            'listaMeds',
            'domLugar',
            'estadoClass',
            'createdAtFormatted'
        ));
    }
}
