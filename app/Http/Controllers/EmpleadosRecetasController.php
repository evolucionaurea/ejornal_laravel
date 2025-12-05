<?php

namespace App\Http\Controllers;

use App\Receta;
use App\Nomina;
use App\Traits\Qbi2\BuildReceta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Qbi2Client;
use App\Http\Traits\Clientes;
use Illuminate\Support\Str;
use App\ProvinciaReceta;
use Illuminate\Support\Arr;
use Carbon\Carbon;


class EmpleadosRecetasController extends Controller
{
    use Clientes, BuildReceta;

    public function index(Request $request)
    {
        $user = Auth::user();
        $clienteActualId = $user->id_cliente_actual;
        $clientes = $this->getClientesUser();

        // Estados posibles
        $estados = ['emitida', 'anulada', 'error'];

        // Trabajadores: sólo nómina del cliente actual
        $nominas = Nomina::query()
            ->where('estado', 1)
            ->when($clienteActualId, function ($q) use ($clienteActualId) {
                $q->where('id_cliente', $clienteActualId);
            })
            ->orderBy('nombre', 'asc')
            ->get(['id', 'nombre']);

        $q = Receta::with(['nomina', 'cliente'])
            ->where('id_user', $user->id);

        // Sólo recetas del cliente donde fichó el empleado
        if ($clienteActualId) {
            $q->where('id_cliente', $clienteActualId);
        }

        // ---- Filtros ----
        if ($request->filled('f_estado')) {
            $q->where('estado', $request->input('f_estado'));
        }

        if ($request->filled('f_nomina')) {
            $q->where('id_nomina', (int) $request->input('f_nomina'));
        }

        if ($request->filled('f_desde')) {
            try {
                $desde = \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('f_desde'))->startOfDay();
                $q->where('created_at', '>=', $desde);
            } catch (\Throwable $e) {
                // ignoramos formato inválido
            }
        }

        if ($request->filled('f_hasta')) {
            try {
                $hasta = \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('f_hasta'))->endOfDay();
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
            return view('empleados.recetas._tabla', compact('recetas'))->render();
        }

        return view('empleados.recetas', compact(
            'recetas',
            'nominas',
            'estados',
            'clientes'
        ));
    }



    public function create()
    {
        $user = Auth::user();
        $clienteActualId = $user->id_cliente_actual;
        $clientes = $this->getClientesUser();

        // Trabajadores sólo del cliente donde está fichado
        $nominas = Nomina::with('cliente')
            ->where('estado', 1)
            ->when($clienteActualId, function ($q) use ($clienteActualId) {
                $q->where('id_cliente', $clienteActualId);
            })
            ->orderBy('nombre', 'asc')
            ->get();

        $provincias = ProvinciaReceta::orderBy('nombre','asc')->get(['id','nombre']);

        return view('empleados.recetas.create', compact('nominas', 'clientes', 'provincias'));
    }


    public function provincias()
    {
        $items = ProvinciaReceta::orderBy('nombre','asc')->get(['id','nombre']);
        return response()->json(['ok'=>true, 'results'=>$items]);
    }


    public function show($id)
    {
        $clientes = $this->getClientesUser();

        $receta = Receta::with(['nomina', 'cliente'])
            ->where('id', $id)
            ->where('id_user', auth()->id())
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

        return view('empleados.recetas.show', compact(
            'receta',
            'clientes',
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



    public function store(Request $req, Qbi2Client $qbi)
    {
        // Habria que decidir si queremos estos logs online o si los sacamos para no llenar el disco
        $data   = $this->validarReceta($req);
        $nomina = Nomina::with('cliente')->findOrFail($data['id_nomina']);

        $payload = $this->buildPayload($req, $nomina);
        \Log::info('[QBI2] Crear Receta request', ['payload' => $payload]);

        $api = $qbi->crearReceta($payload);
        \Log::info('[QBI2] Crear Receta response', ['api' => $api]);

        if (!$api['ok']) {
            return $this->respuestaErrorQbi($api, $req);
        }

        // Fuente oficial: data.recetas[0]
        $first  = data_get($api, 'data.recetas.0', []);
        $hash   = data_get($first, 'id', '') ?: (data_get($api, 'data.id') ?: '');
        $idRec  = data_get($first, 'idReceta');
        $pdfUrl = data_get($first, 's3Link');

        $receta = Receta::create([
            'id_user'    => auth()->id(),
            'id_nomina'  => $nomina->id,
            'id_cliente' => $nomina->id_cliente,
            'hash_id'    => $hash,
            'id_receta'  => $idRec,
            'estado'     => 'emitida',
            'pdf_url'    => $pdfUrl,
            'payload'    => $payload,
            'response'   => $api['data'] ?? null,
        ]);

        // Devuelvo ruta RELATIVA segura (el front la resuelve sobre location.origin)
        $indexPath = '/empleados/recetas';

        if ($req->ajax() || $req->wantsJson()) {
            return response()->json([
                'ok'     => true,
                'id'     => $receta->id,
                'url'    => $indexPath,                           // principal
                'show'   => "/empleados/recetas/{$receta->id}",
                'pdfUrl' => $pdfUrl,
            ]);
        }

        // Redirect server-side sin usar APP_URL (para evitar problemas de cambios de dominio)
        return redirect($indexPath)->with('success', 'Receta generada correctamente.');
    }


    public function getFinanciadores(Request $req, Qbi2Client $qbi)
    {
        $api = $qbi->financiadores();

        if (!$api['ok'] || !is_array($api['data'])) {
            return response()->json(['results' => []]);
        }

        $q      = mb_strtolower(trim($req->query('q', '')));
        $items  = Arr::get($api, 'data.financiadores', []) ?: [];
        $results = [];

        foreach ($items as $f) {
            // idfinanciador es el que después vamos a usar como idFinanciador en cobertura
            $id   = Arr::get($f, 'idfinanciador');
            $num  = Arr::get($f, 'nrofinanciador', '');
            $name = Arr::get($f, 'nombreComercial', '');

            // Texto que ve el usuario en el select
            $text = trim(($num ? $num.' - ' : '').$name);

            if (!$id) {
                // si por algún motivo no trae id, lo salteamos
                continue;
            }

            // Filtro por búsqueda
            if ($q !== '') {
                $hay = mb_strtolower($text);
                if (mb_strpos($hay, $q) === false) {
                    $legal = mb_strtolower((string) Arr::get($f, 'razonSocial', ''));
                    if (mb_strpos($legal, $q) === false) {
                        continue;
                    }
                }
            }

            // Normalizamos planes para el front
            $planes = collect(Arr::get($f, 'planes', []))
                ->map(function ($p) {
                    return [
                        'id'     => Arr::get($p, 'id') ??
                                    Arr::get($p, 'planId') ??
                                    Arr::get($p, 'planid'),
                        'nombre' => Arr::get($p, 'nombre') ??
                                    Arr::get($p, 'descripcion') ??
                                    Arr::get($p, 'name'),
                    ];
                })
                ->filter(function ($p) {
                    return !empty($p['id']) && !empty($p['nombre']);
                })
                ->values()
                ->all();

            $results[] = [
                'id'   => $id,
                'text' => $text,
                'raw'  => array_replace($f, ['planes' => $planes]),
            ];
        }

        return response()->json(['results' => $results]);
    }


    public function getDiagnosticos(Request $req, Qbi2Client $qbi)
    {
        $text = trim($req->query('q',''));
        if (mb_strlen($text) < 3) {
            return response()->json(['results' => []]);
        }
        $api = $qbi->buscarDiagnosticos($text);
        if (!$api['ok'] || !is_array($api['data'])) {
            return response()->json(['results' => []]);
        }
        $out = [];
        foreach (($api['data']['diagnosticos'] ?? []) as $d) {
            $cod  = \Illuminate\Support\Arr::get($d,'coddiagnostico');
            $desc = \Illuminate\Support\Arr::get($d,'descdiagnostico');
            $out[] = ['id' => $cod, 'text' => $cod.' — '.$desc, 'raw' => $d];
        }
        return response()->json(['results' => $out]);
    }

    public function getMedicamentos(Request $req, Qbi2Client $qbi)
    {
        $search = trim($req->query('q',''));
        if (mb_strlen($search) < 3) { // subi a 3 para evitar 404 por consultas muy cortas
            return response()->json(['results' => [], 'pagination' => ['more' => false]]);
        }
        $page = (int) $req->query('page', 1);
        $numeroPagina = max(0, $page - 1);

        $extra = [];
        foreach (['idFinanciador','afiliadoDni','afiliadoCredencial','planid','plan'] as $k) {
            if ($req->filled($k)) $extra[$k] = $req->query($k);
        }

        $api = $qbi->buscarMedicamentos($search, $numeroPagina, $extra);

        // ⬇si el backend respondió 404 “no encontrado”, devolvemos vacío (no lo tratamos como error)
        if (!$api['ok'] && (($api['status'] ?? 0) === 404)) {
            return response()->json(['results' => [], 'pagination' => ['more' => false]]);
        }

        if (!$api['ok'] || !is_array($api['data'])) {
            return response()->json(['results' => [], 'pagination' => ['more' => false]]);
        }

        $results = [];
        foreach (($api['data']['medicamentos'] ?? []) as $m) {
            $text = trim(
                ($m['nombreProducto'] ?? '') .
                (isset($m['presentacion']) ? ' — '.$m['presentacion'] : '') .
                (isset($m['nombreDroga']) ? ' ('.$m['nombreDroga'].')' : '')
            );
            $results[] = [
                'id'   => $m['regNo'] ?? \Illuminate\Support\Str::uuid()->toString(),
                'text' => $text ?: 'Producto sin nombre',
                'raw'  => $m,
            ];
        }

        $pageInfo = $api['data']['pageInfo'] ?? [];
        $more = (bool)($pageInfo['tieneMasResultados'] ?? false);

        return response()->json(['results' => $results, 'pagination' => ['more' => $more]]);
    }


    public function getPracticas(Request $req, Qbi2Client $qbi)
    {
        $params = [
            'search'       => trim((string)$req->query('search','')),
            'tipo'         => trim((string)$req->query('tipo','')),
            'categoria'    => trim((string)$req->query('categoria','')),
            'numeroPagina' => max(1, (int)$req->query('page', 1)),
        ];
        $api = $qbi->buscarPracticas(array_filter($params, fn($v)=>$v!==''));

        if (!$api['ok'] || !is_array($api['data'])) {
            return response()->json(['results' => [], 'pagination' => ['more' => false]]);
        }

        $results = [];
        foreach (($api['data']['practicas'] ?? []) as $p) {
            $txt = $p['practica'] ?? 'Práctica';
            if (!empty($p['tipo']))      $txt .= ' — '.$p['tipo'];
            if (!empty($p['categoria'])) $txt .= ' / '.$p['categoria'];
            $results[] = [
                'id'   => $p['id'] ?? Str::uuid()->toString(),
                'text' => $txt,
                'raw'  => $p,
            ];
        }

        $pageInfo = $api['data']['pageInfo'] ?? [];
        $more = (bool)($pageInfo['tieneMasResultados'] ?? false);

        return response()->json(['results' => $results, 'pagination' => ['more' => $more]]);
    }


    public function anular($id, Qbi2Client $qbi, Request $req)
    {
        $receta = Receta::where('id', $id)
            ->where('id_user', auth()->id())
            ->firstOrFail();

        if ($receta->estado === 'anulada') {
            if ($req->ajax() || $req->wantsJson()) {
                return response()->json([
                    'ok'      => true,
                    'message' => 'La receta ya estaba anulada.',
                    'estado'  => 'anulada',
                ]);
            }

            return back()->with('warning', 'La receta ya estaba anulada.');
        }

        // Si hay hash, intentamos anular en QBI2
        if ($receta->hash_id) {
            $api = $qbi->anularReceta($receta->hash_id);

            if (!$api['ok']) {
                $msg    = 'No se pudo anular en el servicio.';
                $parsed = json_decode($api['error'] ?? '', true);

                if (is_array($parsed) && !empty($parsed['mensaje'])) {
                    $msg = $parsed['mensaje'];
                }

                if ($req->ajax() || $req->wantsJson()) {
                    return response()->json([
                        'ok'      => false,
                        'message' => $msg,
                    ], 400);
                }

                return back()->withErrors(['anular' => $msg]);
            }
        }

        $receta->estado = 'anulada';
        $receta->save();

        if ($req->ajax() || $req->wantsJson()) {
            return response()->json([
                'ok'      => true,
                'message' => 'Receta anulada correctamente.',
                'estado'  => 'anulada',
            ]);
        }

        return back()->with('success', 'Receta anulada correctamente.');
    }





}
