<?php

namespace App\Traits\Qbi2;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\ProvinciaReceta;

trait BuildReceta
{
    /* ====== utilitarios ====== */
    protected static $provCache = null; // [key_normalizada => nombre_canonico]

    protected function pick(array $src, array $keys): array
    {
        $out = [];
        foreach ($keys as $k) if (array_key_exists($k, $src) && $src[$k] !== null && $src[$k] !== '') $out[$k] = $src[$k];
        return $out;
    }

    protected function pickNested(array $src, string $prefix): array
    {
        $out = [];
        foreach ($src as $k => $v) {
            if (Str::startsWith($k, $prefix)) {
                $out[substr($k, strlen($prefix))] = $v;
            }
        }
        return $out;
    }

    protected function toDMY(?string $date): ?string
    {
        if (!$date) return null;
        try { return Carbon::parse($date)->format('d/m/Y'); } catch (\Throwable $e) { return null; }
    }

    protected function nonEmpty(array $a): array
    {
        return array_filter($a, fn($v) => !(is_null($v) || $v === '' || (is_array($v) && count(array_filter($v)) === 0)));
    }

    protected function onlyDigits($v): string
    {
        return preg_replace('/\D+/', '', (string)$v);
    }

    protected function ddmmyyyy(?string $v): ?string
    {
        // Temas fechas es un tema por eso armé esta funcion
        if (!$v) {
            return null;
        }

        $v = trim($v);

        // Si ya viene dd/mm/yyyy lo devolvemos normalizado
        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $v)) {
            try {
                return Carbon::createFromFormat('d/m/Y', $v)->format('d/m/Y');
            } catch (\Throwable $e) {
                return null;
            }
        }

        // Si viene yyyy-mm-dd lo convertimos a dd/mm/yyyy
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $v)) {
            try {
                return Carbon::createFromFormat('Y-m-d', $v)->format('d/m/Y');
            } catch (\Throwable $e) {
                return null;
            }
        }

        // Último intento: que Carbon parsee lo que sea y lo normalizamos a dd/mm/yyyy
        try {
            return Carbon::parse($v)->format('d/m/Y');
        } catch (\Throwable $e) {
            return null;
        }
    }




    /* ===== Provincias por NOMBRE (no IDs. Solo tiene nombre hardcodeado la docu y por eso se creó la tabla de nuestro lado) ===== */
    protected function buildProvCache(): void
    {
        if (self::$provCache !== null) return;

        // Traemos nombres de tu tabla
        $all = ProvinciaReceta::query()->pluck('nombre')->all();

        // Alias comunes a la forma esperada por la API
        // (clave: variante normalizada; valor: nombre canónico tal como lo guarda nuestra tabla/API)
        //Esto se hace por las dudas
        $aliases = [
            'caba'                    => 'Ciudad Autonoma de Bs As',
            'capital federal'         => 'Ciudad Autonoma de Bs As',
            'ciudad autonoma de bs as'=> 'Ciudad Autonoma de Bs As',
            'buenos aires'            => 'Bs As ó Buenos Aires',
            'bs as'                   => 'Bs As ó Buenos Aires',
            'bs as o buenos aires'    => 'Bs As ó Buenos Aires',
            'cordoba'                 => 'Cordoba',
            'entre rios'              => 'Entre Rios',
            'la rioja'                => 'La rioja',
            'santiago del estero'     => 'Santiago del Estero',
            'tucuman'                 => 'Tucuman',
            'neuquen'                 => 'Neuquen',
            'rio negro'               => 'Rio Negro',
            'tierra del fuego'        => 'Tierra del Fuego',
            'sin especificar'         => 'Sin especificar',
            'nacional'                => 'Nacional',
        ];

        $map = [];
        foreach ($all as $nombre) {
            $key = $this->provKey($nombre);
            $map[$key] = $nombre;
        }
        foreach ($aliases as $k => $canon) {
            $map[$this->provKey($k)] = $canon;
        }
        self::$provCache = $map;
    }

    protected function provKey(?string $name): string
    {
        $name = trim((string)$name);
        $name = Str::ascii(mb_strtolower($name));
        $name = preg_replace('/\s+/', ' ', $name);
        return $name;
    }

    protected function provinciaCanonica(?string $entrada, ?string $fallback = null): ?string
    {
        if ($entrada === null || $entrada === '') return $fallback;
        $this->buildProvCache();
        $k = $this->provKey($entrada);
        if (isset(self::$provCache[$k])) return self::$provCache[$k];

        // Si no encontramos match exacto, intentamos heurística:
        // "capital federal" → CABA, "buenos aires" → Bs As, etc. (ya cubierto por aliases)
        return $fallback;
    }

    /* ====== validación ====== */
    protected function validarReceta(Request $req): array
{
    return $this->validate($req, [
        'id_nomina' => 'required|exists:nominas,id',

        // Médico (core)
        'medico.apellido' => 'required|string|max:80',
        'medico.nombre'   => 'required|string|max:80',
        'medico.tipoDoc'  => 'required|string|max:20',
        'medico.nroDoc'   => 'required|string|max:20',
        'medico.sexo'     => 'required|in:F,M,X,O',
        'medico.matricula.tipo'      => 'required|in:MN,MP',
        'medico.matricula.numero'    => ['required','regex:/^\d{1,9}$/'],
        'medico.matricula.provincia' => 'nullable|string|max:60',

        // Paciente (opcionales)
        'paciente.apellido'        => 'nullable|string|max:80',
        'paciente.nombre'          => 'nullable|string|max:80',
        'paciente.tipoDoc'         => 'nullable|string|max:20',
        'paciente.nroDoc'          => 'nullable|string|max:20',
        'paciente.sexo'            => 'nullable|in:F,M,X,O',
        'paciente.fechaNacimiento' => 'nullable|date|before_or_equal:today',

        // Diagnóstico
        'diagnostico'              => 'nullable|string|max:2000',


        // Domicilio: QBI ahora exige informar donde se realizó la atención aunque la doc dice que no (QBI248)
        'domicilio.calle'          => 'required|string|max:150',
        'domicilio.numero'         => 'required|string|max:30',
        'domicilio.piso'           => 'nullable|string|max:20',
        'domicilio.dpto'           => 'nullable|string|max:20',
        'domicilio.cp'             => 'nullable|string|max:20',
        'domicilio.localidad'      => 'nullable|string|max:120',
        'domicilio.provincia'      => 'required|string|max:120',
        'domicilio.pais'           => 'nullable|string|max:120',


        // Cobertura
        'cobertura.credencial'     => 'nullable|regex:/^\d+$/|max:50|required_with:cobertura.idFinanciador',
        'cobertura.planId'         => 'nullable|string|max:50',
        'cobertura.plan'           => 'nullable|string|max:120',
        'cobertura.idFinanciador'  => 'nullable|string|max:50',
        'cobertura.dniTitular'     => 'nullable|string|max:20',

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

        // Postdatadas
        'recetasPostadatas.cantidad'        => 'nullable|integer|min:0',
        'recetasPostadatas.diasAPosdatar'   => 'nullable|integer|min:0',
    ], [
        'domicilio.calle.required'      => 'Ingresá la calle donde se realizó la atención.',
        'domicilio.numero.required'     => 'Ingresá el número donde se realizó la atención.',
        'domicilio.provincia.required'  => 'Seleccioná la provincia donde se realizó la atención.',
        'medico.matricula.numero.regex' => 'El número de matrícula debe contener sólo dígitos y como máximo 9 (límite del servicio externo).',
        'paciente.fechaNacimiento.before_or_equal' => 'La fecha de nacimiento no puede ser posterior a hoy.',
        'cobertura.credencial.required_with' => 'Ingresá el número de afiliado si seleccionás un financiador.',
        'cobertura.credencial.regex'         => 'El número de afiliado sólo puede contener dígitos (sin puntos ni guiones).',
    ]);
}


    /* ====== mapeadores compactos ====== */
    protected function mapPersona(array $src, array $defaults = [], array $extras = [], array $map = []): array
    {
        $base = [
            'apellido' => $src['apellido'] ?? ($defaults['apellido'] ?? null),
            'nombre'   => $src['nombre']   ?? ($defaults['nombre']   ?? null),
            'tipoDoc'  => $src['tipoDoc']  ?? ($defaults['tipoDoc']  ?? 'DNI'),
            'nroDoc'   => $src['nroDoc']   ?? ($defaults['nroDoc']   ?? null),
            'sexo'     => $src['sexo']     ?? ($defaults['sexo']     ?? 'X'),
        ];
        if (array_key_exists('fechaNacimiento', $src) || array_key_exists('fechaNacimiento', $defaults)) {
            $base['fechaNacimiento'] = $src['fechaNacimiento'] ?? $defaults['fechaNacimiento'] ?? null;
        }

        if (($map['fechaNacimiento'] ?? null) === 'dmy') {
            $base['fechaNacimiento'] = $this->toDMY($base['fechaNacimiento'] ?? null);
        }

        $out = $this->nonEmpty($base);
        foreach ($extras as $k) {
            if (isset($src[$k]) && $src[$k] !== '') $out[$k] = $src[$k];
        }

        if (!empty($src['domicilio']) && is_array($src['domicilio'])) $out['domicilio'] = $this->nonEmpty($src['domicilio']);
        if (!empty($src['cobertura']) && is_array($src['cobertura'])) $out['cobertura'] = $this->nonEmpty($src['cobertura']);
        if (!empty($src['sello'])     && is_array($src['sello']))     $out['sello']     = $this->nonEmpty($src['sello']);

        return $out;
    }


    protected function mapMedico(array $reqAll): array
    {
        $m = $this->mapPersona(
            \Illuminate\Support\Arr::get($reqAll, 'medico', []),
            [],
            ['profesion','especialidad','email','telefono','pais','firmalink','firmabase64','logoInstitucion','idTributario','idREFEPS','fechaNacimiento']
        );

        $rawNumero = Arr::get($reqAll, 'medico.matricula.numero');
        $numeroSan = $this->onlyDigits($rawNumero);

        // Reforzar: el servicio no soporta valores mayores a un Int32
        //  => máximo 9 dígitos seguros (< 2.147.483.647)
        if (strlen($numeroSan) > 9) {
            $numeroSan = substr($numeroSan, 0, 9);
        }

        $tipo = strtoupper((string) Arr::get($reqAll, 'medico.matricula.tipo', 'MN'));
        $prov = Arr::get($reqAll, 'medico.matricula.provincia', '');


        // Normalización por NOMBRE (no ID)
        $provCanon = $this->provinciaCanonica($prov);

        // Si MP sin provincia, usamos "Sin especificar"
        if ($tipo === 'MP' && !$provCanon) {
            $provCanon = $this->provinciaCanonica('Sin especificar', 'Sin especificar');
        }

        $mat = [
            'tipo'   => in_array($tipo, ['MN','MP'], true) ? $tipo : 'MN',
            'numero' => $numeroSan,
        ];
        if ($provCanon && $tipo === 'MP') {
            $mat['provincia'] = $provCanon;
        }

        // Fecha del médico, si la completan, también la mandamos en ISO
        if (!empty($m['fechaNacimiento'])) {
            try {
                $d = Carbon::parse($m['fechaNacimiento']);
                $m['fechaNacimiento'] = $d->format('Y-m-d');
            } catch (\Throwable $e) {
                unset($m['fechaNacimiento']);
            }
        }

        $m['matricula'] = $mat;
        return $m;
    }



    protected function mapPaciente(array $reqAll, $nomina): array
{
    $full = trim((string) $nomina->nombre);
    $apellido = $full;
    $nombre   = '';

    if (strpos($full, ' ') !== false) {
        $parts    = preg_split('/\s+/', $full);
        $apellido = array_shift($parts);
        $nombre   = trim(implode(' ', $parts));
    }

    $defaults = [
        'apellido' => $apellido,
        'nombre'   => $nombre,
        'nroDoc'   => (string) $nomina->dni,
        'sexo'     => 'X',
    ];

    // IMPORTANTE:
    // - Dejamos la fechaNacimiento tal como viene del front (ISO Y-m-d).
    // - El <form> ya manda paciente[fechaNacimiento] en Y-m-d vía JS.
    $p = $this->mapPersona(
        \Illuminate\Support\Arr::get($reqAll, 'paciente', []),
        $defaults,
        ['email','telefono','pais','localidad','provincia','cuil']
        // sin map 'dmy' para que NO pase por toDMY
    );

    // Normalizar números básicos
    if (isset($p['nroDoc'])) {
        $p['nroDoc'] = $this->onlyDigits($p['nroDoc']);
    }
    if (isset($p['telefono'])) {
        $p['telefono'] = $this->onlyDigits($p['telefono']);
    }

    // Si vino fechaNacimiento, la dejamos; el formato final lo ajustamos en buildPayload
    return $p;
}




    protected function mapMedicamentos(array $reqAll): array
{
    $items = \Illuminate\Support\Arr::get($reqAll, 'medicamentos', []);
    $out   = [];

    foreach ($items as $mm) {
        $regNoRaw = isset($mm['regNo']) ? $this->onlyDigits($mm['regNo']) : null;
        $regNo    = ($regNoRaw !== null && $regNoRaw !== '') ? (int) $regNoRaw : null;

        $cant = isset($mm['cantidad'])    ? (int) $mm['cantidad']    : null;
        $trat = isset($mm['tratamiento']) ? (int) $mm['tratamiento'] : null;

        $dup     = array_key_exists('forzarDuplicado', $mm) ? (bool) $mm['forzarDuplicado'] : null;
        $permSub = isset($mm['permiteSustitucion']) ? (string) $mm['permiteSustitucion'] : null;

        $payload = [
            'cantidad'           => $cant,
            'regNo'              => $regNo,
            'tratamiento'        => $trat,
            'forzarDuplicado'    => $dup,
            'permiteSustitucion' => $permSub,  // 'S' o 'N'
            'posologia'          => $mm['posologia']    ?? null,
            'indicaciones'       => $mm['indicaciones'] ?? null,
            'diagnostico'        => $mm['diagnostico']  ?? null,
        ];

        // Si NO hay regNo, mandamos descriptores como ayuda
        if ($regNo === null) {
            $payload['nombreProducto'] = $mm['nombre']        ?? ($mm['nombreProducto'] ?? null);
            $payload['nombreDroga']    = $mm['nombreDroga']   ?? null;
            $payload['presentacion']   = $mm['presentacion']  ?? null;
        }

        // Limpiar vacíos
        $payload = array_filter($payload, function ($v) {
            if (is_array($v)) return count(array_filter($v)) > 0;
            return !($v === null || $v === '');
        });

        $out[] = $payload;
    }

    return $out;
}


protected function mapCobertura(Request $req): array
{
    $idFin  = $this->onlyDigits($req->input('cobertura.idFinanciador'));
    $planId = $this->onlyDigits($req->input('cobertura.planId'));
    $numero = $this->onlyDigits($req->input('cobertura.credencial'));

    $c = $this->nonEmpty([
        'idFinanciador' => $idFin ?: null,
        'planId'        => $planId ?: null,
        'plan'          => $req->input('cobertura.plan'),
        'numero'        => $numero ?: null,
        'dniTitular'    => $this->onlyDigits($req->input('cobertura.dniTitular')),
    ]);

    return $c;
}


protected function mapLugarAtencion(Request $req): array
{
    $d = $req->input('domicilio', []);

    $calle  = trim((string) ($d['calle']  ?? ''));
    $numero = trim((string) ($d['numero'] ?? ''));
    $prov   = $d['provincia'] ?? null;

    // Provincia por NOMBRE
    $provCanon = $this->provinciaCanonica($prov);

    return [
        'domicilio' => $this->nonEmpty([
            'calle'        => $calle,
            'numero'       => $this->onlyDigits($numero) ?: $numero ?: null,
            'piso'         => $d['piso']      ?? null,
            'dpto'         => $d['dpto']      ?? null,
            'codigoPostal' => $this->onlyDigits($d['cp'] ?? null) ?: null,
            'localidad'    => $d['localidad'] ?? null,
            'provincia'    => $provCanon ?: null,
            'pais'         => $d['pais']      ?? null,
        ]),
    ];
}







    protected function buildPayload(Request $req, $nomina): array
{
    $all          = $req->all();
    $paciente     = $this->mapPaciente($all, $nomina);
    $medico       = $this->mapMedico($all);
    $medicamentos = $this->mapMedicamentos($all);
    $cobertura    = $this->mapCobertura($req);
    $lugar        = $this->mapLugarAtencion($req);

    // --- Fecha del paciente: la API DEMOSTRÓ aceptar ISO (YYYY-MM-DD) aunque se contradiga.
    //     Aseguramos ese formato y que no sea futura.
    if (!empty($paciente['fechaNacimiento'])) {
        try {
            $d = \Carbon\Carbon::parse($paciente['fechaNacimiento']);
            if ($d->greaterThan(\Carbon\Carbon::today())) {
                $d = \Carbon\Carbon::yesterday();
            }
            // Formato final que va al JSON → "2003-03-11" como en el curl que me pasaron por mail
            $paciente['fechaNacimiento'] = $d->format('Y-m-d');
        } catch (\Throwable $e) {
            // Si no se puede parsear, preferimos NO mandar el campo antes que romper el tipo
            unset($paciente['fechaNacimiento']);
        }
    }

    // --- Base de payload
    $payload = [
        'paciente'      => $paciente,
        'medico'        => $medico,
        'medicamentos'  => $medicamentos,
        'clienteAppId'  => (int) (config('qbi2.client_app_id') ?? 510),
    ];

    // Siempre que mapLugarAtencion devuelva algo, lo mandamos
    if (!empty($lugar['domicilio'])) {
        $payload['lugarAtencion'] = $lugar;
    }

    // --- Diagnóstico (campo general de la receta)
    $rawDiag = trim((string) $req->input('diagnostico', ''));
    if ($rawDiag !== '') {
        $diag = $rawDiag;

        // Si el usuario eligió del buscador CIE-10, suele ser "H571 — DOLOR OCULAR"
        // Nos quedamos solo con el código si lo encontramos al inicio.
        if (preg_match('/^([A-Z]\d{2,3}(?:\.\d+)?)/i', $rawDiag, $m)) {
            $diag = strtoupper($m[1]); // p.ej. H571 o H57.1
        }

        $payload['diagnostico'] = $diag;
    }

    // --- Postdatadas (si vienen del form)
    $post = $req->input('recetasPostadatas');
    if (is_array($post)) {
        $postClean = [
            'cantidad'      => (int) ($post['cantidad']      ?? 0),
            'diasAPosdatar' => (int) ($post['diasAPosdatar'] ?? 0),
            'fechas'        => isset($post['fechas']) && is_array($post['fechas']) ? $post['fechas'] : [],
        ];
        $payload['recetasPostadatas'] = $postClean;
    }

    // --- Campos adicionales opcionales (si vienen no vacíos)
    foreach ([
        'imprimirDiagnostico',
        'observaciones',
        'indicaciones',
        'fechaEmision',
        'nombreConsultorio',
        'datosContacto',
        'direccionConsultorio',
        'email',
        'linkECommerce',
        'codigoPromocion',
    ] as $k) {
        $v = $req->input($k);
        if ($v !== null && $v !== '') {
            $payload[$k] = $v;
        }
    }

    // --- informaciónExtra (si viene con contenido)
    $infoExtra = $req->input('informacionExtra');
    if (is_array($infoExtra) && count(array_filter($infoExtra, fn($v) => $v !== null && $v !== ''))) {
        $payload['informacionExtra'] = $infoExtra;
    }

    return $payload;
}






    protected function respuestaErrorQbi(array $api, Request $req)
    {
        $status   = (int) ($api['status'] ?? 400);
        $rawError = $api['error'] ?? null;

        $code = null;
        $msg  = 'Error al generar la receta.';

        if ($rawError === 'TIMEOUT' || $status === 0) {
            $code = 'TIMEOUT';
            $msg  = 'El servicio tardó demasiado en responder. Intentá nuevamente en unos segundos.';
        } else {
            $parsed = null;
            if (is_string($rawError)) {
                $parsed = json_decode($rawError, true);
            } elseif (is_array($rawError)) {
                $parsed = $rawError;
            }

            if (is_array($parsed)) {
                $code = $parsed['error']   ?? null;
                $msg  = $parsed['mensaje'] ?? ($parsed['message'] ?? $msg);

                if ((string) $code === '104') {
                    $msg = "ERROR SERVICIO (104): algún campo numérico excede el tamaño permitido por el servicio.\n\n"
                        ."Revisá especialmente:\n"
                        ."• Médico → Matrícula → Número (no puede superar 2.147.483.647; usá como máximo 9 dígitos).\n"
                        ."• Paciente → Fecha de nacimiento (formato correcto y no futura).\n"
                        ."• Cobertura → idFinanciador / planId (sólo dígitos).";
                }


                if ((string) $code === 'QBI34') {
                    $msg = "Datos inválidos para el servicio (QBI34). Revisá formatos y tipos:\n"
                        ."• Números (DNI, matrícula, planId, idFinanciador) sin puntos ni letras.\n"
                        ."• Fechas en formato DD/MM/AAAA válidas.\n"
                        ."• Campos obligatorios de domicilio (calle y número) completos.";
                }
            } elseif (is_string($rawError) && trim($rawError) !== '') {
                $msg = $rawError;
            }
        }

        if ($req->ajax() || $req->wantsJson()) {
            $httpStatus = ($code === 'TIMEOUT' || $status === 0) ? 504 : ($status ?: 400);
            return response()->json([
                'ok'      => false,
                'status'  => $httpStatus,
                'code'    => $code,
                'message' => $msg,
            ], $httpStatus);
        }

        return back()->with('error', $msg)->withInput();
    }
}
