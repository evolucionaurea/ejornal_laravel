@extends('partials.layout')

@section('title', 'Admin - Receta #'.$receta->id)

@section('content')
<div class="d-flex" id="wrapper">
    @include('partials.sidebar_admin')

    <div id="page-content-wrapper">
        @include('partials.nav_sup')

        <div class="container-fluid py-3">

            {{-- ENCABEZADO: resumen --}}
            <div class="tarjeta mb-3">
                <div class="card-body d-flex flex-wrap justify-content-between align-items-start">
                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <h2 class="h5 mb-0 mr-2">
                                Receta #{{ $receta->id_receta ?? $receta->id }}
                            </h2>
                            <span class="badge badge-{{ $estadoClass }}">
                                {{ ucfirst($receta->estado) }}
                            </span>
                        </div>

                        <div class="small text-muted">
                            @if($createdAtFormatted)
                            Emitida el {{ $createdAtFormatted }}
                            @endif

                            @if(!empty($apiRoot['fecha']))
                            <span class="mx-1">•</span>
                            Fecha receta: {{ $apiRoot['fecha'] }}
                            @endif

                            @if(!empty($apiRoot['fechavencimiento']))
                            <span class="mx-1">•</span>
                            Vence: {{ $apiRoot['fechavencimiento'] }}
                            @endif
                        </div>

                        @if(!empty($response['idTransaccion']))
                        <div class="small text-muted">
                            ID Transacción: {{ $response['idTransaccion'] }}
                        </div>
                        @endif
                    </div>

                    {{-- ACCIONES --}}
                    <div>
                        <a href="{{ route('admin.recetas') }}" class="btn-ejornal btn-ejornal-gris-claro mr-2">
                            ← Volver
                        </a>

                        <a class="btn-ejornal btn-ejornal-base mr-2 {{ $receta->pdf_url ? '' : 'disabled' }}"
                            href="{{ $receta->pdf_url ?: '#' }}" target="_blank">
                            Ver PDF
                        </a>
                    </div>
                </div>

                @if(!empty($apiReceta['verificador']) || !empty($receta->hash_id))
                <div class="card-footer bg-light d-flex flex-wrap justify-content-between align-items-center">
                    @if(!empty($apiReceta['verificador']))
                    <div class="small mb-2 mb-sm-0">
                        <span class="font-weight-bold">Verificador:&nbsp;</span>
                        <a href="{{ $apiReceta['verificador'] }}" target="_blank" rel="noopener">
                            {{ \Illuminate\Support\Str::limit($apiReceta['verificador'], 60) }}
                        </a>
                    </div>
                    @endif

                    @if(!empty($receta->hash_id))
                    <div class="small text-muted">
                        Hash ID: {{ $receta->hash_id }}
                    </div>
                    @endif
                </div>
                @endif
            </div>

            {{-- CONTENIDO PRINCIPAL --}}
            <div class="tarjeta">
                <div class="card-body">

                    <div class="row">
                        {{-- Paciente --}}
                        <div class="col-lg-4 mb-3">
                            <div class="border rounded p-3 bg-light h-100">
                                <div class="text-uppercase text-muted small mb-1">Paciente</div>

                                <div class="font-weight-bold">
                                    {{ $apiPac['apellido'] ?? $payloadPac['apellido'] ?? '-' }},
                                    {{ $apiPac['nombre'] ?? $payloadPac['nombre'] ?? '-' }}
                                </div>

                                <div class="small text-muted mt-1">
                                    {{ $apiPac['tipoDocumento'] ?? $payloadPac['tipoDoc'] ?? '-' }}
                                    {{ $apiPac['numeroDocumento'] ?? $payloadPac['nroDoc'] ?? '-' }}
                                </div>

                                <div class="small text-muted">
                                    Sexo: {{ $apiPac['sexo'] ?? $payloadPac['sexo'] ?? '-' }}
                                </div>

                                <div class="small text-muted">
                                    F. Nac: {{ $apiPac['fechaNacimiento'] ?? $payloadPac['fechaNacimiento'] ?? '-' }}
                                </div>

                                @if(!empty($apiPac['cuil']))
                                <div class="small text-muted mt-1">
                                    CUIL: {{ $apiPac['cuil'] }}
                                </div>
                                @endif

                                @if(!empty($apiPac['email']) || !empty($payloadPac['email']))
                                <div class="small text-muted">
                                    Email: {{ $apiPac['email'] ?? $payloadPac['email'] }}
                                </div>
                                @endif

                                @if(!empty($apiPac['telefono']) || !empty($payloadPac['telefono']))
                                <div class="small text-muted">
                                    Tel: {{ $apiPac['telefono'] ?? $payloadPac['telefono'] }}
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Médico --}}
                        <div class="col-lg-4 mb-3">
                            <div class="border rounded p-3 bg-light h-100">
                                <div class="text-uppercase text-muted small mb-1">Médico</div>

                                <div class="font-weight-bold">
                                    {{ $apiMed['apellido'] ?? $payloadMed['apellido'] ?? '-' }},
                                    {{ $apiMed['nombre'] ?? $payloadMed['nombre'] ?? '-' }}
                                </div>

                                <div class="small text-muted mt-1">
                                    {{ $apiMed['tipoDoc'] ?? $payloadMed['tipoDoc'] ?? '-' }}
                                    {{ $apiMed['numeroDocumento'] ?? $payloadMed['nroDoc'] ?? '-' }}
                                </div>

                                <div class="small text-muted">
                                    Matrícula:
                                    {{ $apiMed['matricula']['tipo'] ?? $payloadMed['matricula']['tipo'] ?? '-' }}
                                    {{ $apiMed['matricula']['numero'] ?? $payloadMed['matricula']['numero'] ?? '-' }}
                                </div>

                                @if(!empty($apiMed['matricula']['provincia']))
                                <div class="small text-muted">
                                    Provincia matr.: {{ $apiMed['matricula']['provincia'] }}
                                </div>
                                @endif

                                @if(!empty($apiMed['profesion']))
                                <div class="small text-muted mt-1">
                                    Profesión: {{ $apiMed['profesion'] }}
                                </div>
                                @endif

                                @if(!empty($apiMed['email']))
                                <div class="small text-muted">
                                    Email: {{ $apiMed['email'] }}
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Datos de la receta / Cobertura --}}
                        <div class="col-lg-4 mb-3">
                            <div class="border rounded p-3 bg-light h-100">
                                <div class="text-uppercase text-muted small mb-1">Diagnóstico</div>

                                <div class="font-weight-bold">
                                    {{ $diagTexto ?? '—' }}
                                </div>

                                @if($payloadDiag)
                                <div class="small text-muted">
                                    Código enviado: {{ $payloadDiag }}
                                </div>
                                @endif

                                <div class="text-uppercase text-muted small mt-3 mb-1">
                                    Estado repositorio
                                </div>
                                <div class="font-weight-bold">
                                    {{ $apiRoot['status'] ?? 'OK' }}
                                </div>

                                @if(!empty($apiCob))
                                <div class="text-uppercase text-muted small mt-3 mb-1">
                                    Cobertura
                                </div>

                                <div class="small text-muted">
                                    ID Financiador: {{ $apiCob['idFinanciador'] ?? '—' }}
                                </div>
                                <div class="small text-muted">
                                    Nº Financiador: {{ $apiCob['nroFinanciador'] ?? '—' }}
                                </div>
                                <div class="small text-muted">
                                    Plan: {{ $apiCob['plan'] ?? '—' }}
                                </div>
                                <div class="small text-muted">
                                    Nº afiliado: {{ $apiCob['numero'] ?? '—' }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- 2) Fila: Medicamentos + Lugar de atención / PDF --}}
                    <div class="row mt-2">
                        {{-- Medicamentos --}}
                        <div class="col-lg-8 mb-3">
                            <h6 class="text-uppercase text-muted small mb-2">
                                Medicamentos
                            </h6>

                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Reg. Nº</th>
                                            <th>Medicamento</th>
                                            <th class="text-center">Cant.</th>
                                            <th class="text-center">Trat. (días)</th>
                                            <th class="text-center">Dup.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($listaMeds as $med)
                                        <tr>
                                            <td class="align-middle">
                                                {{ $med['regNo'] ?? '-' }}
                                            </td>
                                            <td class="align-middle">
                                                <div class="font-weight-bold">
                                                    {{ $med['nombreProducto'] ?? ($med['nombre'] ?? '-') }}
                                                </div>

                                                <div class="small text-muted">
                                                    @if(!empty($med['nombreDroga']))
                                                    {{ $med['nombreDroga'] }}
                                                    @endif

                                                    @if(!empty($med['nombreDroga']) && !empty($med['presentacion']))
                                                    •
                                                    @endif

                                                    @if(!empty($med['presentacion']))
                                                    {{ $med['presentacion'] }}
                                                    @endif
                                                </div>

                                                @if(!empty($med['posologia']))
                                                <div class="small text-muted">
                                                    Posología: {{ $med['posologia'] }}
                                                </div>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                {{ $med['cantidad'] ?? '-' }}
                                            </td>
                                            <td class="align-middle text-center">
                                                {{ $med['tratamiento'] ?? '-' }}
                                            </td>
                                            <td class="align-middle text-center">
                                                @if(!empty($med['forzarDuplicado']))
                                                <span class="badge badge-secondary">Sí</span>
                                                @else
                                                <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                No hay medicamentos registrados para esta receta.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Lugar de atención + PDF / Verificador --}}
                        <div class="col-lg-4 mb-3">
                            <div class="border rounded p-3 bg-light mb-3">
                                <div class="text-uppercase text-muted small mb-1">
                                    Lugar de atención
                                </div>

                                @if(!empty($domLugar))
                                <div class="font-weight-bold">
                                    {{ $domLugar['calle'] ?? '-' }}
                                    {{ $domLugar['numero'] ?? '' }}
                                </div>

                                <div class="small text-muted">
                                    {{ $domLugar['localidad'] ?? '' }}
                                    @if(!empty($domLugar['localidad']) && !empty($domLugar['provincia']))
                                    —
                                    @endif
                                    {{ $domLugar['provincia'] ?? '' }}
                                </div>

                                @if(!empty($domLugar['codigoPostal']))
                                <div class="small text-muted">
                                    CP: {{ $domLugar['codigoPostal'] }}
                                </div>
                                @endif
                                @else
                                <div class="font-weight-bold">
                                    No informado
                                </div>
                                @endif
                            </div>

                            <div class="border rounded p-3 bg-light">
                                <div class="text-uppercase text-muted small mb-1">
                                    PDF / Verificador
                                </div>

                                <div class="small mb-1">
                                    @if($receta->pdf_url)
                                    <span class="badge badge-light">
                                        PDF disponible
                                    </span>
                                    @else
                                    <span class="text-muted">
                                        Sin PDF registrado.
                                    </span>
                                    @endif
                                </div>

                                @if(!empty($apiReceta['idReceta']))
                                <div class="small text-muted">
                                    ID Receta repositorio: {{ $apiReceta['idReceta'] }}
                                </div>
                                @endif

                                @if(!empty($apiReceta['s3Link']))
                                <div class="small text-muted">
                                    Origen PDF: S3
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection