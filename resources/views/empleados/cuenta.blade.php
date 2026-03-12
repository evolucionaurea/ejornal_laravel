@extends('partials.layout')

@section('title', 'Empleados')

@section('content')

    <style>
        .tarjeta {
            background: #fff;
            border: 1px solid rgba(0, 0, 0, .06);
            border-radius: 14px;
            box-shadow: 0 10px 24px rgba(0, 0, 0, .06);
            padding: 16px 16px;
            margin-bottom: 16px;
        }

        .section-head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
        }

        .section-head h4 {
            margin: 0;
            font-weight: 700;
        }

        .section-head p {
            margin: 0;
            color: #6c757d;
            font-size: .92rem;
        }

        .subhead {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin: 10px 0 10px 0;
            padding-top: 8px;
            border-top: 1px dashed rgba(0, 0, 0, .12);
        }

        .subhead .title {
            font-weight: 700;
            margin: 0;
        }

        .subhead .hint {
            color: #6c757d;
            font-size: .88rem;
            margin: 0;
        }

        .doc-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            background: #f8f9fa;
            border: 1px solid rgba(0, 0, 0, .06);
            font-size: .86rem;
            color: #495057;
            white-space: nowrap;
        }

        .file-box {
            border: 1px solid rgba(0, 0, 0, .06);
            border-radius: 12px;
            background: #fbfbfc;
            padding: 10px 10px;
        }

        .file-box .file-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 8px;
        }

        .file-box .file-meta .name {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #495057;
            font-size: .92rem;
        }

        .file-box .file-meta .name strong {
            color: #212529;
        }

        .mat-accordion .card {
            border: 1px solid rgba(0, 0, 0, .06);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 8px 18px rgba(0, 0, 0, .04);
        }

        .mat-accordion .card-header {
            background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
            border-bottom: 1px solid rgba(0, 0, 0, .06);
            padding: 10px 12px;
        }

        .mat-accordion .card-header .btn {
            text-decoration: none !important;
        }

        .mat-code {
            font-weight: 800;
            letter-spacing: .02em;
            padding: 4px 10px;
            border-radius: 999px;
            background: #e9ecef;
            color: #212529;
            border: 1px solid rgba(0, 0, 0, .06);
            font-size: .85rem;
        }

        .mat-label {
            font-weight: 700;
            color: #212529;
        }

        .mat-badges .badge {
            font-weight: 600;
            padding: .35rem .5rem;
            border-radius: 999px;
        }

        label {
            font-weight: 600;
        }

        .form-control-sm {
            border-radius: 10px;
        }

        .custom-file-label {
            border-radius: 10px;
        }
    </style>

    <div class="d-flex" id="wrapper">
        @include('partials.sidebar_empleados')

        <div id="page-content-wrapper">
            @include('partials.nav_sup')

            <div class="container-fluid">

                <div class="cabecera">
                    <h2>Mi cuenta</h2>
                    <p>Aquí puedes ver tus datos personales</p>
                </div>

                @include('../mensajes_validacion')
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger alert-dismissible fade show mr-4 ml-4" role="alert">
                            {{ $error }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endforeach
                @endif

                {{-- ========================================================= --}}
                {{-- 1) DATOS BÁSICOS --}}
                {{-- ========================================================= --}}
                <div class="tarjeta">
                    <div class="section-head">
                        <div>
                            <h4>Datos básicos</h4>
                            <p>Información general (solo lectura).</p>
                        </div>
                        <span class="doc-pill">
                            <i class="fa fa-user"></i>
                            {{ auth()->user()->estado == 1 ? 'Cuenta activa' : 'Cuenta inactiva' }}
                        </span>
                    </div>

                    <form class="row">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_user" value="{{ auth()->user()->id }}">

                        <div class="form-group col-md-3">
                            <label>Nombre</label>
                            <input disabled type="text" class="form-control form-control-sm"
                                value="{{ auth()->user()->nombre }}">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Email</label>
                            <input disabled type="email" class="form-control form-control-sm"
                                value="{{ auth()->user()->email }}">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Rol</label>
                            @foreach ($roles as $rol)
                                @if ($rol->id == auth()->user()->id_rol)
                                    <input disabled type="text" class="form-control form-control-sm"
                                        value="{{ $rol->nombre }}">
                                @endif
                            @endforeach
                        </div>

                        <div class="form-group col-md-3">
                            <label>Estado</label>
                            <input disabled type="text" class="form-control form-control-sm"
                                value="{{ auth()->user()->estado == 1 ? 'Activo' : 'Inactivo' }}">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Calle</label>
                            <input disabled type="text" class="form-control form-control-sm"
                                value="{{ auth()->user()->calle }}">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Número</label>
                            <input disabled type="text" class="form-control form-control-sm"
                                value="{{ auth()->user()->nro }}">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Entre calles</label>
                            <input disabled type="text" class="form-control form-control-sm"
                                value="{{ auth()->user()->entre_calles }}">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Localidad</label>
                            <input disabled type="text" class="form-control form-control-sm"
                                value="{{ auth()->user()->localidad }}">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Partido</label>
                            <input disabled type="text" class="form-control form-control-sm"
                                value="{{ auth()->user()->partido }}">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Código postal</label>
                            <input disabled type="text" class="form-control form-control-sm"
                                value="{{ auth()->user()->cod_postal }}">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Observaciones</label>
                            <input disabled type="text" class="form-control form-control-sm"
                                value="{{ auth()->user()->observaciones }}">
                        </div>
                    </form>
                </div>

                {{-- ========================================================= --}}
                {{-- 2) DOCUMENTACIÓN --}}
                {{-- ========================================================= --}}
                <div class="tarjeta">
                    <div class="section-head">
                        <div>
                            <h4>Documentación</h4>
                            <p>Actualizá tus datos y subí archivos. Los cambios se guardan al final.</p>
                        </div>
                        <span class="doc-pill">
                            <i class="fa fa-folder-open"></i> Archivos y vencimientos
                        </span>
                    </div>

                    <div id="upload-alert" class="alert alert-danger alert-dismissible fade show mt-2 mr-4 ml-4 d-none"
                        role="alert">
                        <i class="fa fa-exclamation fa-fw"></i>
                        <span id="upload-alert-text"></span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form id="form-documentacion" action="{{ action('EmpleadosCuentaController@store') }}" method="post"
                        enctype="multipart/form-data" class="row">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_user" value="{{ auth()->user()->id }}">

                        {{-- Identidad --}}
                        <div class="col-12">
                            <div class="subhead">
                                <div>
                                    <p class="title mb-0"><i class="fa fa-id-card mr-1"></i> Identidad</p>
                                    <p class="hint mb-0">DNI y archivos asociados.</p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label>DNI</label>
                            <input name="dni" type="text" class="form-control form-control-sm"
                                value="{{ auth()->user()->dni }}">
                        </div>

                        <div class="form-group col-md-4">
                            <label>DNI (Frente)</label>
                            <div class="file-box">
                                <div class="file-meta">
                                    <div class="name">
                                        <i class="fa fa-file"></i>
                                        @if (auth()->user()->archivo_dni)
                                            <span>Actual: <strong>{{ auth()->user()->archivo_dni }}</strong></span>
                                        @else
                                            <span class="text-muted">No hay archivo adjunto</span>
                                        @endif
                                    </div>

                                    @if (auth()->user()->archivo_dni)
                                        <a class="btn btn-sm btn-outline-primary"
                                            href="{{ route('cuenta.download_dni', auth()->user()->id) }}">
                                            Descargar
                                        </a>
                                    @endif
                                </div>

                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="archivo_dni" name="archivo_dni"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                    <label class="custom-file-label" for="archivo_dni">Seleccionar archivo</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label>DNI (Dorso)</label>
                            <div class="file-box">
                                <div class="file-meta">
                                    <div class="name">
                                        <i class="fa fa-file"></i>
                                        @if (auth()->user()->archivo_dni_detras)
                                            <span>Actual: <strong>{{ auth()->user()->archivo_dni_detras }}</strong></span>
                                        @else
                                            <span class="text-muted">No hay archivo adjunto</span>
                                        @endif
                                    </div>

                                    @if (auth()->user()->archivo_dni_detras)
                                        <a class="btn btn-sm btn-outline-primary"
                                            href="{{ route('cuenta.download_dni_detras', auth()->user()->id) }}">
                                            Descargar
                                        </a>
                                    @endif
                                </div>

                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="archivo_dni_detras"
                                        name="archivo_dni_detras" accept=".pdf,.jpg,.jpeg,.png">
                                    <label class="custom-file-label" for="archivo_dni_detras">Seleccionar archivo</label>
                                </div>
                            </div>
                        </div>

                        {{-- Matrículas (sin select, se muestran todas) --}}
                        <div class="col-12">
                            <div class="subhead">
                                <div>
                                    <p class="title mb-0"><i class="fa fa-certificate mr-1"></i> Matrículas</p>
                                    <p class="hint mb-0">Se listan todos los tipos disponibles. Completá número,
                                        vencimiento y archivos.</p>
                                </div>
                                <span class="doc-pill">
                                    <i class="fa fa-calendar"></i> Vencimiento: calendario del navegador
                                </span>
                            </div>
                        </div>

                        <div class="col-12 mat-accordion" id="matriculasAccordion">
                            @foreach ($matriculas_form as $idx => $m)
                                <div class="card mb-3">
                                    <div class="card-header" id="matHeading{{ $idx }}">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <button class="btn btn-link p-0 text-left" type="button"
                                                data-toggle="collapse" data-target="#matCollapse{{ $idx }}"
                                                aria-expanded="{{ $idx === 0 ? 'true' : 'false' }}"
                                                aria-controls="matCollapse{{ $idx }}">
                                                <span class="mat-code">{{ $m['tipo'] }}</span>
                                                <span class="mat-label ml-2">{{ ucfirst($m['label']) }}</span>
                                                <span class="text-muted ml-2" style="font-size:.9rem;">
                                                    (editar / subir archivos)
                                                </span>
                                            </button>

                                            <div class="mat-badges">
                                                @if (!empty($m['nro']))
                                                    <span class="badge badge-success">Nº OK</span>
                                                @else
                                                    <span class="badge badge-secondary">Sin Nº</span>
                                                @endif

                                                @if (!empty($m['fecha_vencimiento']))
                                                    <span class="badge badge-info">Venc OK</span>
                                                @else
                                                    <span class="badge badge-secondary">Sin venc</span>
                                                @endif

                                                @if (!empty($m['archivo_frente']) || !empty($m['archivo_dorso']))
                                                    <span class="badge badge-primary">Archivos</span>
                                                @else
                                                    <span class="badge badge-secondary">Sin archivos</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div id="matCollapse{{ $idx }}"
                                        class="collapse {{ $idx === 0 ? 'show' : '' }}"
                                        aria-labelledby="matHeading{{ $idx }}"
                                        data-parent="#matriculasAccordion">
                                        <div class="card-body">
                                            <div class="form-row">
                                                <div class="form-group col-md-4">
                                                    <label>Número</label>
                                                    <input name="matricula_nro[{{ $m['tipo'] }}]" type="text"
                                                        class="form-control form-control-sm" value="{{ $m['nro'] }}"
                                                        placeholder="Ej: 1929621">
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label>Vencimiento</label>
                                                    <input name="matricula_vencimiento[{{ $m['tipo'] }}]"
                                                        type="date" lang="es-AR"
                                                        class="form-control form-control-sm"
                                                        value="{{ $m['fecha_vencimiento'] }}">
                                                    <small class="text-muted">Se guarda como aaaa-mm-dd.</small>
                                                </div>

                                                <div class="form-group col-md-4"></div>

                                                {{-- Frente --}}
                                                <div class="form-group col-md-6">
                                                    <label>Archivo (Frente)</label>
                                                    <div class="file-box">
                                                        <div class="file-meta">
                                                            <div class="name">
                                                                <i class="fa fa-file"></i>
                                                                @if (!empty($m['archivo_frente']))
                                                                    <span>Actual:
                                                                        <strong>{{ $m['archivo_frente'] }}</strong></span>
                                                                @else
                                                                    <span class="text-muted">No hay archivo adjunto</span>
                                                                @endif
                                                            </div>

                                                            @if (!empty($m['id']) && !empty($m['archivo_frente']))
                                                                <a class="btn btn-sm btn-outline-primary"
                                                                    href="{{ route('cuenta.download_matricula', $m['id']) }}">
                                                                    Descargar
                                                                </a>
                                                            @endif
                                                        </div>

                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input"
                                                                id="archivo_matricula_frente_{{ $m['tipo'] }}"
                                                                name="archivo_matricula_frente[{{ $m['tipo'] }}]"
                                                                accept=".pdf,.jpg,.jpeg,.png">
                                                            <label class="custom-file-label"
                                                                for="archivo_matricula_frente_{{ $m['tipo'] }}">
                                                                Seleccionar archivo
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Dorso --}}
                                                <div class="form-group col-md-6">
                                                    <label>Archivo (Dorso)</label>
                                                    <div class="file-box">
                                                        <div class="file-meta">
                                                            <div class="name">
                                                                <i class="fa fa-file"></i>
                                                                @if (!empty($m['archivo_dorso']))
                                                                    <span>Actual:
                                                                        <strong>{{ $m['archivo_dorso'] }}</strong></span>
                                                                @else
                                                                    <span class="text-muted">No hay archivo adjunto</span>
                                                                @endif
                                                            </div>

                                                            @if (!empty($m['id']) && !empty($m['archivo_dorso']))
                                                                <a class="btn btn-sm btn-outline-primary"
                                                                    href="{{ route('cuenta.download_matricula_detras', $m['id']) }}">
                                                                    Descargar
                                                                </a>
                                                            @endif
                                                        </div>

                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input"
                                                                id="archivo_matricula_dorso_{{ $m['tipo'] }}"
                                                                name="archivo_matricula_dorso[{{ $m['tipo'] }}]"
                                                                accept=".pdf,.jpg,.jpeg,.png">
                                                            <label class="custom-file-label"
                                                                for="archivo_matricula_dorso_{{ $m['tipo'] }}">
                                                                Seleccionar archivo
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Título --}}
                        <div class="col-12">
                            <div class="subhead">
                                <div>
                                    <p class="title mb-0"><i class="fa fa-graduation-cap mr-1"></i> Título habilitante</p>
                                    <p class="hint mb-0">Subí frente y dorso.</p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Título (Frente)</label>
                            <div class="file-box">
                                <div class="file-meta">
                                    <div class="name">
                                        <i class="fa fa-file"></i>
                                        @if (auth()->user()->titulo)
                                            <span>Actual: <strong>{{ auth()->user()->titulo }}</strong></span>
                                        @else
                                            <span class="text-muted">No hay archivo adjunto</span>
                                        @endif
                                    </div>

                                    @if (auth()->user()->titulo)
                                        <a class="btn btn-sm btn-outline-primary"
                                            href="{{ route('cuenta.download_titulo', auth()->user()->id) }}">
                                            Descargar
                                        </a>
                                    @endif
                                </div>

                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="archivo_titulo"
                                        name="archivo_titulo" accept=".pdf,.jpg,.jpeg,.png">
                                    <label class="custom-file-label" for="archivo_titulo">Seleccionar archivo</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Título (Dorso)</label>
                            <div class="file-box">
                                <div class="file-meta">
                                    <div class="name">
                                        <i class="fa fa-file"></i>
                                        @if (auth()->user()->archivo_titulo_detras)
                                            <span>Actual:
                                                <strong>{{ auth()->user()->archivo_titulo_detras }}</strong></span>
                                        @else
                                            <span class="text-muted">No hay archivo adjunto</span>
                                        @endif
                                    </div>

                                    @if (auth()->user()->archivo_titulo_detras)
                                        <a class="btn btn-sm btn-outline-primary"
                                            href="{{ route('cuenta.download_titulo_detras', auth()->user()->id) }}">
                                            Descargar
                                        </a>
                                    @endif
                                </div>

                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="archivo_titulo_detras"
                                        name="archivo_titulo_detras" accept=".pdf,.jpg,.jpeg,.png">
                                    <label class="custom-file-label" for="archivo_titulo_detras">Seleccionar
                                        archivo</label>
                                </div>
                            </div>
                        </div>

                        {{-- Firma + Sello --}}
                        <div class="col-12">
                            <div class="subhead">
                                <div>
                                    <p class="title mb-0"><i class="fa fa-pencil mr-1"></i> Firma y sello</p>
                                    <p class="hint mb-0">Firma en PNG y líneas del sello.</p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Tu firma</label>
                            <div class="file-box">
                                @if (auth()->user()->firma_medico)
                                    <div class="mb-2">
                                        <img class="img-fluid" style="max-height: 80px;"
                                            src="/storage/users/{{ auth()->user()->id }}/firmas_medico/{{ auth()->user()->hash_firma_medico }}"
                                            alt="Firma">
                                    </div>
                                @endif

                                <div class="custom-file">
                                    <input type="file" accept="image/png" class="custom-file-input" id="firma_medico"
                                        name="firma_medico">
                                    <label class="custom-file-label" for="firma_medico">
                                        {{ auth()->user()->firma_medico ? 'Cambiar firma' : 'Seleccionar archivo' }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-8">
                            <label>Sello</label>
                            <input name="sello_linea_1" type="text" class="form-control form-control-sm mb-2"
                                value="{{ auth()->user()->sello_linea_1 != null ? auth()->user()->sello_linea_1 : '' }}"
                                placeholder="Línea 1 del sello">
                            <input name="sello_linea_2" type="text" class="form-control form-control-sm mb-2"
                                value="{{ auth()->user()->sello_linea_2 != null ? auth()->user()->sello_linea_2 : '' }}"
                                placeholder="Línea 2 del sello">
                            <input name="sello_linea_3" type="text" class="form-control form-control-sm mb-2"
                                value="{{ auth()->user()->sello_linea_3 != null ? auth()->user()->sello_linea_3 : '' }}"
                                placeholder="Línea 3 del sello">
                        </div>

                        <div class="col-12 d-flex align-items-center justify-content-between mt-2">
                            <small class="text-muted">
                                <i class="fa fa-info-circle"></i> Guardá al final para aplicar todos los cambios.
                            </small>
                            <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">
                                <i class="fa fa-save mr-1"></i> Guardar
                            </button>
                        </div>

                    </form>
                </div>

                {{-- ========================================================= --}}
                {{-- 3) SEGURIDAD --}}
                {{-- ========================================================= --}}
                <div class="tarjeta">
                    <div class="section-head">
                        <div>
                            <h4>Seguridad</h4>
                            <p>Cambio de contraseña.</p>
                        </div>
                        <span class="doc-pill">
                            <i class="fa fa-lock"></i> Acceso
                        </span>
                    </div>

                    <form action="{{ action('EmpleadosCuentaController@cambiar_pass') }}" method="post" class="row">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_cliente" value="id_cliente">
                        <input type="hidden" name="id_user" value="{{ auth()->user()->id }}">

                        <div class="form-group col-md-6">
                            <label>Nueva contraseña</label>
                            <input name="password" type="password" class="form-control form-control-sm" value="">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Repetir nueva contraseña</label>
                            <input name="cpassword" type="password" class="form-control form-control-sm" value="">
                        </div>

                        <div class="col-12">
                            <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">
                                <i class="fa fa-key mr-1"></i> Cambiar contraseña
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection
