<?php

namespace App\Http\Controllers;

use App\Receta;
use App\Nomina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Qbi2Client;
use App\Http\Traits\Clientes;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class EmpleadosRecetasController extends Controller
{
    use Clientes;

    public function index()
    {
        $clientes = $this->getClientesUser();

        // Listado de recetas emitidas por el user actual (empleado)
        $recetas = Receta::with(['nomina','cliente'])
            ->where('id_user', Auth::id())
            ->orderBy('id','desc')
            ->paginate(20);

        return view('empleados.recetas', compact('recetas', 'clientes'));
    }

    public function create()
    {
        $clientes = $this->getClientesUser();
        
        $nominas = Nomina::with('cliente')
        ->where('estado', 1)
        ->orderBy('nombre','asc')
        ->get();

        return view('empleados.recetas.create', compact('nominas', 'clientes'));
    }


    public function store(Request $req, \App\Services\Qbi2Client $qbi)
    {
        // VALIDACIÓN (ajustada, pero no hiper-restrictiva)
        $data = $this->validate($req, [
            'id_nomina' => 'required|exists:nominas,id',

            // Médico
            'medico.apellido' => 'required|string|max:80',
            'medico.nombre'   => 'required|string|max:80',
            'medico.tipoDoc'  => 'required|string|max:20',
            'medico.nroDoc'   => 'required|string|max:20',
            'medico.sexo'     => 'required|in:F,M,X,O',

            // Matrícula dentro de médico
            'medico.matricula.tipo'      => 'required|in:MN,MP',
            'medico.matricula.numero'    => 'required|string|max:30',
            'medico.matricula.provincia' => 'nullable|string|max:60',

            // (algunos tenants piden CUIT en medico.idTributario o subemisor.cuit)
            'medico.idTributario' => 'nullable|string|max:20',

            // Paciente (overrides opcionales)
            'paciente.apellido'        => 'nullable|string|max:80',
            'paciente.nombre'          => 'nullable|string|max:80',
            'paciente.tipoDoc'         => 'nullable|string|max:20',
            'paciente.nroDoc'          => 'nullable|string|max:20',
            'paciente.sexo'            => 'nullable|in:F,M,X,O',
            'paciente.fechaNacimiento' => 'nullable|date', // aceptamos Y-m-d del input

            // Diagnóstico
            'diagnostico' => 'nullable|string|max:2000',

            // Domicilio/cobertura/subemisor/lugarAtencion opcionales
            'domicilio'       => 'array',
            'cobertura'       => 'array',
            'subemisor'       => 'array',
            'lugarAtencion'   => 'array',

            // Medicamentos
            'medicamentos'                      => 'required|array|min:1',
            'medicamentos.*.cantidad'           => 'required|integer|min:1',
            'medicamentos.*.regNo'              => 'nullable',
            'medicamentos.*.nombre'             => 'nullable|string|max:150',
            'medicamentos.*.presentacion'       => 'nullable|string|max:150',
            'medicamentos.*.nombreDroga'        => 'nullable|string|max:150',
            'medicamentos.*.posologia'          => 'nullable|string|max:500',
            'medicamentos.*.indicaciones'       => 'nullable|string|max:500',
            'medicamentos.*.forzarDuplicado'    => 'nullable|boolean',
            'medicamentos.*.permiteSustitucion' => 'nullable|string|in:S,N',
            'medicamentos.*.tratamiento'        => 'nullable|integer|min:0',
            'medicamentos.*.diagnostico'        => 'nullable|string|max:500',
            'recetasPostadatas.cantidad'        => 'nullable|integer|min:0',
            'recetasPostadatas.diasAPosdatar'   => 'nullable|integer|min:0',
        ]);

        $nomina = \App\Nomina::with('cliente')->findOrFail($data['id_nomina']);

        // ==== Paciente (minúsculas) ====
        // Split de nombre completo si no vino override
        $full = trim((string) $nomina->nombre);
        $apellido = $full; $nombre = '';
        if (strpos($full, ' ') !== false) {
            $parts = preg_split('/\s+/', $full);
            $apellido = array_shift($parts);
            $nombre   = trim(implode(' ', $parts));
        }
        $p = [
            'apellido'        => $req->input('paciente.apellido', $apellido),
            'nombre'          => $req->input('paciente.nombre',   $nombre),
            'tipoDoc'         => $req->input('paciente.tipoDoc', 'DNI'),
            'nroDoc'          => $req->input('paciente.nroDoc', (string) $nomina->dni),
            'sexo'            => $req->input('paciente.sexo', 'X'),
            'fechaNacimiento' => $req->input('paciente.fechaNacimiento') ?: null,
        ];
        // Normalizo fecha a YYYY-MM-DD si vino en otro formato
        if (!empty($p['fechaNacimiento'])) {
            try {
                $p['fechaNacimiento'] = \Carbon\Carbon::parse($p['fechaNacimiento'])->format('Y-m-d');
            } catch (\Throwable $e) {
                $p['fechaNacimiento'] = null; // si falla, la dejamos nula
            }
        }
        // Extra opcionales del paciente
        foreach (['email','telefono','pais','localidad','provincia','cuil'] as $k) {
            $v = $req->input("paciente.$k");
            if ($v !== null && $v !== '') $p[$k] = $v;
        }
        // domicilio del paciente
        $dom = $req->input('paciente.domicilio', $req->input('domicilio', []));
        if (is_array($dom) && count(array_filter($dom, fn($v)=>$v!==null && $v!==''))) {
            $p['domicilio'] = $dom;
        }
        // cobertura
        $cov = $req->input('paciente.cobertura', $req->input('cobertura', []));
        if (is_array($cov) && count(array_filter($cov, fn($v)=>$v!==null && $v!==''))) {
            $p['cobertura'] = $cov;
        }

        // ==== Médico (minúsculas) con matricula anidada ====
        $m = [
            'apellido' => $data['medico']['apellido'],
            'nombre'   => $data['medico']['nombre'],
            'tipoDoc'  => $data['medico']['tipoDoc'],
            'nroDoc'   => $data['medico']['nroDoc'],
            'sexo'     => $data['medico']['sexo'],
            'matricula'=> [
                'tipo'      => $data['medico']['matricula']['tipo'],
                'numero'    => $data['medico']['matricula']['numero'],
                'provincia' => $data['medico']['matricula']['provincia'] ?? '',
            ],
        ];
        // campos opcionales del médico (profesion, especialidad, email, telefono, etc.)
        foreach (['profesion','especialidad','fechaNacimiento','email','telefono','pais','firmalink','firmabase64','logoInstitucion','idTributario','idREFEPS'] as $k) {
            $v = $req->input("medico.$k");
            if ($v !== null && $v !== '') $m[$k] = $v;
        }
        // sello (opcional)
        $sello = $req->input('medico.sello', []);
        if (is_array($sello) && count(array_filter($sello))) $m['sello'] = $sello;

        // ==== Medicamentos (minúsculas) ====
        $meds = [];
        foreach ($data['medicamentos'] as $mm) {
            $item = [
                'cantidad' => (int) $mm['cantidad'],
            ];
            if (!empty($mm['regNo']))            $item['regNo'] = $mm['regNo'];
            if (!empty($mm['nombre']))           $item['nombreProducto'] = $mm['nombre'];
            if (!empty($mm['nombreDroga']))      $item['nombreDroga']    = $mm['nombreDroga'];
            if (!empty($mm['presentacion']))     $item['presentacion']   = $mm['presentacion'];
            if (isset($mm['permiteSustitucion']))$item['permiteSustitucion'] = $mm['permiteSustitucion'];
            if (isset($mm['tratamiento']))       $item['tratamiento']    = (int) $mm['tratamiento'];
            if (!empty($mm['diagnostico']))      $item['diagnostico']    = $mm['diagnostico'];
            if (!empty($mm['posologia']))        $item['posologia']      = $mm['posologia'];
            if (!empty($mm['indicaciones']))     $item['indicaciones']   = $mm['indicaciones'];
            if (isset($mm['forzarDuplicado']))   $item['forzarDuplicado']= (bool) $mm['forzarDuplicado'];
            $meds[] = $item;
        }

        // ==== Payload final (minúsculas) ====
        $payload = [
            'paciente'          => $p,
            'medico'            => $m,
            'medicamentos'      => $meds,
            'clienteAppId'      => (string) config('qbi2.client_app_id'),
        ];

        if ($diag = $req->input('diagnostico'))     $payload['diagnostico'] = $diag;
        if ($rp = $req->input('recetasPostadatas')) $payload['recetasPostadatas'] = $rp;

        // lugarAtencion opcional
        $lugar = $req->input('lugarAtencion', []);
        if (is_array($lugar) && count(array_filter($lugar))) $payload['lugarAtencion'] = $lugar;

        // subemisor (donde típicamente va un CUIT si lo pide tu tenant)
        $sub = $req->input('subemisor', []);
        if (is_array($sub) && count(array_filter($sub))) $payload['subemisor'] = $sub;

        // extras
        foreach (['imprimirDiagnostico','observaciones','indicaciones','fechaEmision','nombreConsultorio','datosContacto','direccionConsultorio','email','linkECommerce','codigoPromocion'] as $k) {
            $v = $req->input($k);
            if ($v !== null && $v !== '') $payload[$k] = $v;
        }
        $infoExtra = $req->input('informacionExtra', []);
        if (is_array($infoExtra) && count($infoExtra)) $payload['informacionExtra'] = $infoExtra;

        // LOG de depuración (request)
        \Log::info('[QBI2] Crear Receta request', ['payload' => $payload]);

        // Llamada remota (JSON)
        $api = $qbi->crearReceta($payload);

        // LOG de depuración (response)
        \Log::info('[QBI2] Crear Receta response', ['api' => $api]);

        if (!$api['ok']) {
            // Intentar mostrar mensaje entendible si viene {error:'QBIxxx', mensaje:'...'}
            $msg = $api['error'];
            $parsed = json_decode($api['error'], true);
            if (is_array($parsed) && isset($parsed['mensaje'])) {
                $msg = "[{$parsed['error']}] ".$parsed['mensaje'];
            }
            return back()->withErrors(['qbi2' => 'Error al generar receta: '.$msg])->withInput();
        }

        // Extraer IDs comunes
        $hash   = data_get($api, 'data.hash') ?? data_get($api, 'data.id') ?? '';
        $idRec  = data_get($api, 'data.idReceta');
        $pdfUrl = data_get($api, 'data.pdfUrl');

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

        return redirect()->route('empleados.recetas.show', $receta->id)->with('ok','Receta generada correctamente.');
    }



public function getFinanciadores(Request $req, Qbi2Client $qbi)
{
    $api = $qbi->financiadores();
    if (!$api['ok'] || !is_array($api['data'])) {
        return response()->json(['results' => []]);
    }
    $results = [];
    foreach (($api['data']['financiadores'] ?? []) as $f) {
        $results[] = [
            'id'   => Arr::get($f, 'idfinanciador'),
            'text' => trim(Arr::get($f,'nrofinanciador','').' - '.Arr::get($f,'nombreComercial','')),
            'raw'  => $f,
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
        $cod  = Arr::get($d,'coddiagnostico');
        $desc = Arr::get($d,'descdiagnostico');
        $out[] = ['id' => $cod, 'text' => $cod.' — '.$desc, 'raw' => $d];
    }
    return response()->json(['results' => $out]);
}

public function getMedicamentos(Request $req, Qbi2Client $qbi)
{
    $search = trim($req->query('q',''));
    if (mb_strlen($search) < 2) {
        return response()->json(['results' => [], 'pagination' => ['more' => false]]);
    }
    $page = (int) $req->query('page', 1);
    $numeroPagina = max(0, $page - 1);

    $extra = [];
    foreach (['idFinanciador','afiliadoDni','afiliadoCredencial','planid','plan'] as $k) {
        if ($req->filled($k)) $extra[$k] = $req->query($k);
    }

    $api = $qbi->buscarMedicamentos($search, $numeroPagina, $extra);
    if (!$api['ok'] || !is_array($api['data'])) {
        return response()->json(['results' => [], 'pagination' => ['more' => false]]);
    }

    $results = [];
    foreach (($api['data']['medicamentos'] ?? []) as $m) {
        $text = trim(
            ($m['nombreProducto'] ?? '').
            (isset($m['presentacion']) ? ' — '.$m['presentacion'] : '').
            (isset($m['nombreDroga']) ? ' ('.$m['nombreDroga'].')' : '')
        );
        $results[] = [
            'id'   => $m['regNo'] ?? Str::uuid()->toString(),
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

 
}
