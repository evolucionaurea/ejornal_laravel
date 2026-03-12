{{-- SOLO se muestra cuando JS detecta: rol=2 y especialidad=1 --}}
<div class="mostrar_docs_medico d-none">

    <hr class="my-3">

    <div class="alert alert-info mb-3">
        <i class="fa fa-user-md"></i>
        Sección médica (solo para empleados con especialidad <b>Médico</b>). Máximo <b>10MB</b> por archivo.
    </div>

    {{-- DNI + archivos --}}
    <div class="row">
        <div class="col-12 mb-2">
            <h5 class="mb-2"><i class="fa fa-id-card mr-1"></i> Identidad</h5>
        </div>

        <div class="form-group col-md-4">
            <label>DNI</label>
            <input name="dni" type="text" class="form-control form-control-sm"
                value="{{ old('dni', isset($user) && $user ? (string) $user->dni : '') }}">
        </div>

        <div class="form-group col-md-4">
            <label>DNI (Frente)</label>

            @if (isset($user) && $user && !empty($user->archivo_dni))
                <div class="alert alert-primary py-2">
                    <a class="alert-link" href="{{ route('users.download_dni', $user->id) }}">
                        <i class="fa fa-file mr-1"></i>{{ $user->archivo_dni }}
                    </a>
                </div>
            @else
                <div class="alert alert-info py-2">No hay archivo adjunto</div>
            @endif

            <div class="custom-file">
                <input type="file" class="custom-file-input" id="archivo_dni" name="archivo_dni"
                    accept=".pdf,.jpg,.jpeg,.png">
                <label class="custom-file-label" for="archivo_dni">Seleccionar archivo</label>
            </div>
        </div>

        <div class="form-group col-md-4">
            <label>DNI (Dorso)</label>

            @if (isset($user) && $user && !empty($user->archivo_dni_detras))
                <div class="alert alert-primary py-2">
                    <a class="alert-link" href="{{ route('users.download_dni_detras', $user->id) }}">
                        <i class="fa fa-file mr-1"></i>{{ $user->archivo_dni_detras }}
                    </a>
                </div>
            @else
                <div class="alert alert-info py-2">No hay archivo adjunto</div>
            @endif

            <div class="custom-file">
                <input type="file" class="custom-file-input" id="archivo_dni_detras" name="archivo_dni_detras"
                    accept=".pdf,.jpg,.jpeg,.png">
                <label class="custom-file-label" for="archivo_dni_detras">Seleccionar archivo</label>
            </div>
        </div>
    </div>

    <hr class="my-3">

    {{-- MATRÍCULAS --}}
    <div class="row">
        <div class="col-12 mb-2">
            <h5 class="mb-2"><i class="fa fa-certificate mr-1"></i> Matrículas</h5>
            <small class="text-muted">Se cargan por tipo. Vencimiento usa calendario HTML5.</small>
        </div>

        @foreach ($matriculas_form ?? [] as $m)
            <div class="col-12">
                <div class="border rounded p-3 mb-3" style="background:#f8f9fa;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div>
                            <span class="badge badge-dark">{{ $m['tipo'] }}</span>
                            <strong class="ml-2">{{ ucfirst($m['label']) }}</strong>
                        </div>
                        <div class="text-muted small">
                            @if (!empty($m['nro']))
                                Nº: {{ $m['nro'] }}
                            @else
                                Sin Nº
                            @endif
                            @if (!empty($m['fecha_vencimiento']))
                                · Vence: {{ $m['fecha_vencimiento'] }}
                            @endif
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Número</label>
                            <input name="matricula_nro[{{ $m['tipo'] }}]" type="text"
                                class="form-control form-control-sm"
                                value="{{ old('matricula_nro.' . $m['tipo'], (string) ($m['nro'] ?? '')) }}"
                                placeholder="Ej: 1929621">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Vencimiento</label>
                            <input name="matricula_vencimiento[{{ $m['tipo'] }}]" type="date" lang="es-AR"
                                class="form-control form-control-sm"
                                value="{{ old('matricula_vencimiento.' . $m['tipo'], (string) ($m['fecha_vencimiento'] ?? '')) }}">
                            <small class="text-muted">Se guarda como aaaa-mm-dd.</small>
                        </div>

                        <div class="form-group col-md-4"></div>

                        {{-- Frente --}}
                        <div class="form-group col-md-6">
                            <label>Archivo (Frente)</label>

                            @if (isset($user) && $user && !empty($m['archivo_frente']))
                                <div class="alert alert-primary py-2">
                                    <a class="alert-link"
                                        href="{{ route('users.download_matricula', $user->id) }}?tipo={{ $m['tipo'] }}">
                                        <i class="fa fa-file mr-1"></i>{{ $m['archivo_frente'] }}
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-info py-2">No hay archivo adjunto</div>
                            @endif

                            <div class="custom-file">
                                <input type="file" class="custom-file-input"
                                    id="archivo_matricula_frente_{{ $m['tipo'] }}"
                                    name="archivo_matricula_frente[{{ $m['tipo'] }}]" accept=".pdf,.jpg,.jpeg,.png">
                                <label class="custom-file-label"
                                    for="archivo_matricula_frente_{{ $m['tipo'] }}">Seleccionar archivo</label>
                            </div>
                        </div>

                        {{-- Dorso --}}
                        <div class="form-group col-md-6">
                            <label>Archivo (Dorso)</label>

                            @if (isset($user) && $user && !empty($m['archivo_dorso']))
                                <div class="alert alert-primary py-2">
                                    <a class="alert-link"
                                        href="{{ route('users.download_matricula_detras', $user->id) }}?tipo={{ $m['tipo'] }}">
                                        <i class="fa fa-file mr-1"></i>{{ $m['archivo_dorso'] }}
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-info py-2">No hay archivo adjunto</div>
                            @endif

                            <div class="custom-file">
                                <input type="file" class="custom-file-input"
                                    id="archivo_matricula_dorso_{{ $m['tipo'] }}"
                                    name="archivo_matricula_dorso[{{ $m['tipo'] }}]" accept=".pdf,.jpg,.jpeg,.png">
                                <label class="custom-file-label"
                                    for="archivo_matricula_dorso_{{ $m['tipo'] }}">Seleccionar archivo</label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <hr class="my-3">

    {{-- TÍTULO --}}
    <div class="row">
        <div class="col-12 mb-2">
            <h5 class="mb-2"><i class="fa fa-graduation-cap mr-1"></i> Título habilitante</h5>
        </div>

        <div class="form-group col-md-6">
            <label>Título (Frente)</label>

            @if (isset($user) && $user && !empty($user->titulo))
                <div class="alert alert-primary py-2">
                    <a class="alert-link" href="{{ route('users.download_titulo', $user->id) }}">
                        <i class="fa fa-file mr-1"></i>{{ $user->titulo }}
                    </a>
                </div>
            @else
                <div class="alert alert-info py-2">No hay archivo adjunto</div>
            @endif

            <div class="custom-file">
                <input type="file" class="custom-file-input" id="archivo_titulo" name="archivo_titulo"
                    accept=".pdf,.jpg,.jpeg,.png">
                <label class="custom-file-label" for="archivo_titulo">Seleccionar archivo</label>
            </div>
        </div>

        <div class="form-group col-md-6">
            <label>Título (Dorso)</label>

            @if (isset($user) && $user && !empty($user->archivo_titulo_detras))
                <div class="alert alert-primary py-2">
                    <a class="alert-link" href="{{ route('users.download_titulo_detras', $user->id) }}">
                        <i class="fa fa-file mr-1"></i>{{ $user->archivo_titulo_detras }}
                    </a>
                </div>
            @else
                <div class="alert alert-info py-2">No hay archivo adjunto</div>
            @endif

            <div class="custom-file">
                <input type="file" class="custom-file-input" id="archivo_titulo_detras"
                    name="archivo_titulo_detras" accept=".pdf,.jpg,.jpeg,.png">
                <label class="custom-file-label" for="archivo_titulo_detras">Seleccionar archivo</label>
            </div>
        </div>
    </div>

    <hr class="my-3">

    {{-- FIRMA + SELLO --}}
    <div class="row">
        <div class="col-12 mb-2">
            <h5 class="mb-2"><i class="fa fa-pencil mr-1"></i> Firma y sello</h5>
        </div>

        <div class="form-group col-md-4">
            <label>Firma (PNG)</label>

            @if (isset($user) && $user && !empty($user->hash_firma_medico))
                <div class="border rounded p-2 bg-white mb-2 text-center">
                    <img src="{{ asset('storage/users/' . $user->id . '/firmas_medico/' . $user->hash_firma_medico) }}"
                        style="max-height:70px;max-width:100%;object-fit:contain;" alt="Firma">
                </div>
            @endif

            <div class="custom-file">
                <input type="file" class="custom-file-input" id="firma_medico" name="firma_medico"
                    accept="image/png">
                <label class="custom-file-label" for="firma_medico">Seleccionar archivo</label>
            </div>
        </div>

        <div class="form-group col-md-8">
            <label>Sello (3 líneas)</label>
            <input name="sello_linea_1" type="text" class="form-control form-control-sm mb-2"
                value="{{ old('sello_linea_1', isset($user) && $user ? (string) $user->sello_linea_1 : '') }}"
                placeholder="Línea 1">
            <input name="sello_linea_2" type="text" class="form-control form-control-sm mb-2"
                value="{{ old('sello_linea_2', isset($user) && $user ? (string) $user->sello_linea_2 : '') }}"
                placeholder="Línea 2">
            <input name="sello_linea_3" type="text" class="form-control form-control-sm mb-2"
                value="{{ old('sello_linea_3', isset($user) && $user ? (string) $user->sello_linea_3 : '') }}"
                placeholder="Línea 3 (ej: MN 12345)">
        </div>
    </div>

</div>
