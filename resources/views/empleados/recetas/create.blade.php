@extends('partials.layout')

@section('title', 'Empleado - Nueva Receta')

<style>
    .receta-card {
        background: #fff;
        border: 1px solid rgba(0, 0, 0, .06);
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, .06);
        padding: 14px 14px;
        margin-bottom: 14px;
    }

    .receta-card .text-uppercase {
        letter-spacing: .04em;
    }

    .receta-card.mb-4 {
        margin-bottom: 14px !important;
    }

    @media (prefers-color-scheme: dark) {
        .receta-card {
            background: #0b1221;
            border: 1px solid rgba(255, 255, 255, .08);
            box-shadow: 0 10px 26px rgba(0, 0, 0, .35);
        }

        .receta-card label,
        .receta-card .text-muted {
            color: #cbd5e1 !important;
        }
    }
</style>

@section('content')
<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')

        <div class="cabecera">
            <h2 class="h4 font-weight-bold mb-1">Nueva receta</h2>
            <p class="text-muted mb-0">Complete los datos y cargue al menos un medicamento.</p>
        </div>

        @include('../mensajes_validacion')

        <div class="alert alert-success alert-dismissible fade show mt-2 mr-4 ml-4" role="alert">
            Aun no disponible la generación de recetas. Pronto estará listo.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="px-3 py-3">
            <form id="recetaForm" method="POST" action="{{ route('empleados.recetas.store') }}"
                data-url-get-financiadores="{{ route('empleados.recetas.get_financiadores') }}"
                data-url-get-diagnosticos="{{ route('empleados.recetas.get_diagnosticos') }}"
                data-url-get-medicamentos="{{ route('empleados.recetas.get_medicamentos') }}"
                data-url-get-practicas="{{ route('empleados.recetas.get_practicas') }}"
                data-url-get-provincias="{{ route('empleados.recetas.provincias') }}">
                @csrf

                {{-- SECCIÓN: Trabajador --}}
                <div class="receta-card">
                    <div class="text-uppercase text-info small mb-2 font-weight-bold">Trabajador</div>

                    <div class="form-group mb-2">
                        <label class="mb-1">Nómina <span class="text-danger">*</span></label>

                        <select class="form-control form-control-sm" id="id_nomina" name="id_nomina" required
                            data-preset="{{ $selectedNominaId ?? '' }}">
                            <option value="">Seleccione…</option>

                            @php
                            $provById = $provincias->pluck('nombre','id');
                            @endphp

                            @foreach($nominas as $n)
                            @php
                            $isSelected = false;
                            if (!empty($selectedNominaId)) {
                            $isSelected = ((int)$selectedNominaId === (int)$n->id);
                            } else {
                            $isSelected = (old('id_nomina') == $n->id);
                            }

                            $c = $n->cliente; // relación
                            $cProvId = optional($c)->id_provincia;
                            $cProvName = $cProvId ? ($provById[$cProvId] ?? '') : '';
                            @endphp

                            <option value="{{ $n->id }}" {{-- Datos del trabajador (Paciente) --}}
                                data-nombre="{{ $n->nombre }}" data-dni="{{ $n->dni }}" data-email="{{ $n->email }}"
                                data-telefono="{{ $n->telefono }}"
                                data-fecha-nacimiento="{{ $n->fecha_nacimiento ?? '' }}" {{-- Datos del CLIENTE
                                (Domicilio) --}} data-cliente-calle="{{ optional($c)->calle ?? '' }}"
                                data-cliente-nro="{{ optional($c)->nro ?? '' }}"
                                data-cliente-numero="{{ optional($c)->nro ?? '' }}"
                                data-cliente-id-provincia="{{ $cProvId ?? '' }}"
                                data-cliente-provincia="{{ $cProvName ?? '' }}" {{ $isSelected ? 'selected' : '' }}>
                                {{ $n->nombre }} — {{ optional($c)->nombre }}
                                @if($n->dni) (DNI: {{ $n->dni }}) @endif
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- SECCIÓN: Paciente --}}
                <div class="receta-card">
                    <div class="text-uppercase text-info font-weight-bold small mb-2">Paciente</div>

                    <div class="form-row">
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1">Nombre</label>
                            <input type="text" class="form-control form-control-sm" name="paciente[nombre]"
                                value="{{ old('paciente.nombre') }}">
                        </div>
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1">Apellido</label>
                            <input type="text" class="form-control form-control-sm" name="paciente[apellido]"
                                value="{{ old('paciente.apellido') }}">
                        </div>
                        <div class="form-group col-md-2 mb-2">
                            <label class="mb-1">Tipo Doc</label>
                            <select class="form-control form-control-sm" name="paciente[tipoDoc]">
                                @foreach(['DNI','LE','LC','CI','Pasaporte'] as $doc)
                                <option value="{{ $doc }}" {{ old('paciente.tipoDoc','DNI')===$doc ? 'selected' :'' }}>
                                    {{ $doc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2 mb-2">
                            <label class="mb-1">N° Doc</label>
                            <input type="text" class="form-control form-control-sm" name="paciente[nroDoc]"
                                value="{{ old('paciente.nroDoc') }}">
                        </div>
                        <div class="form-group col-md-2 mb-2">
                            <label class="mb-1">Sexo</label>
                            @php $px = old('paciente.sexo', 'M'); @endphp
                            <select name="paciente[sexo]" class="form-control form-control-sm">
                                <option value="M" {{ $px==='M' ?'selected':'' }}>Hombre (M)</option>
                                <option value="F" {{ $px==='F' ?'selected':'' }}>Mujer (F)</option>
                                <option value="X" {{ $px==='X' ?'selected':'' }}>No binario (X)</option>
                                <option value="O" {{ $px==='O' ?'selected':'' }}>Otro (O)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1">Fecha Nacimiento</label>
                            <input type="hidden" class="form-control form-control-sm" name="paciente[fechaNacimiento]"
                                value="{{ old('paciente.fechaNacimiento') }}">
                            {{-- visible lo agrega el JS --}}
                        </div>
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control form-control-sm" name="paciente[email]"
                                value="{{ old('paciente.email') }}" required>
                        </div>
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1">Teléfono</label>
                            <input type="text" class="form-control form-control-sm" name="paciente[telefono]"
                                value="{{ old('paciente.telefono') }}">
                        </div>
                    </div>

                    <div class="text-info font-weight-bold small mb-2">Domicilio (del cliente)</div>
                    <div class="form-row">
                        <div class="form-group col-md-5 mb-2">
                            <label>Calle <span class="text-danger">*</span></label>
                            <input type="text" id="dom_calle" name="domicilio[calle]"
                                class="form-control form-control-sm" value="{{ old('domicilio.calle') }}" required>
                        </div>
                        <div class="form-group col-md-2 mb-2">
                            <label>Número <span class="text-danger">*</span></label>
                            <input type="text" id="dom_numero" name="domicilio[numero]"
                                class="form-control form-control-sm" value="{{ old('domicilio.numero') }}" required>
                        </div>
                        <div class="form-group col-md-5 mb-2">
                            <label>Provincia <span class="text-danger">*</span></label>
                            {{-- Se convierte a <select> por JS --}}
                                <input type="text" id="dom_provincia" name="domicilio[provincia]"
                                    class="form-control form-control-sm" value="{{ old('domicilio.provincia') }}"
                                    required>
                                {{-- si tu JS usa id_provincia, dejalo disponible --}}
                                <input type="hidden" id="dom_id_provincia" name="domicilio[id_provincia]"
                                    value="{{ old('domicilio.id_provincia') }}">
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN: Cobertura --}}
                <div class="receta-card">
                    <div class="text-uppercase text-info font-weight-bold small mb-2">Cobertura</div>
                    <div class="form-row">
                        <div class="form-group col-md-5 mb-2">
                            <label class="mb-1">Financiador <span class="text-danger">*</span></label>
                            <select id="financiador" class="form-control form-control-sm" style="width:100%"></select>
                        </div>
                        <div class="form-group col-md-4 mb-2">
                            <label class="mb-1">Plan</label>
                            <select id="plan" class="form-control form-control-sm" style="width:100%"></select>
                        </div>
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1">Credencial / Afiliado <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="cobertura[credencial]"
                                value="{{ old('cobertura.credencial') }}">
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN: Medicamentos --}}
                <div class="receta-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="text-uppercase text-info font-weight-bold small">Medicamentos</div>
                        <button id="btnAddMed" class="btn-ejornal btn-ejornal-dark" type="button">+ Agregar</button>
                    </div>

                    <div id="medsWrapper" class="mt-2">
                        <div class="med-row border rounded p-2 mb-3">
                            <div class="form-row">
                                <div class="form-group col-md-4 mb-2">
                                    <label class="mb-1 text-muted small">Buscar medicamento <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm sel-medicamento"
                                        style="width:100%"></select>
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-muted small">Cantidad <span
                                            class="text-danger">*</span></label>
                                    <input type="number" min="1" class="form-control form-control-sm"
                                        name="medicamentos[0][cantidad]" required>
                                </div>
                                <div class="form-group col-md-3 mb-2">
                                    <label class="mb-1 text-muted small">Reg. Nº</label>
                                    <input type="text" class="form-control form-control-sm regno"
                                        name="medicamentos[0][regNo]" placeholder="Ej: 20095">
                                </div>
                                <div class="form-group col-md-3 mb-2">
                                    <label class="mb-1 text-muted small">Presentación <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm presentacion"
                                        name="medicamentos[0][presentacion]" placeholder="comp. blister x 10" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4 mb-2">
                                    <label class="mb-1 text-muted small">Nombre <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm nombre"
                                        name="medicamentos[0][nombre]" placeholder="TAFIROL" required>
                                </div>
                                <div class="form-group col-md-4 mb-2">
                                    <label class="mb-1 text-muted small">Droga <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm droga"
                                        name="medicamentos[0][nombreDroga]" placeholder="paracetamol" required>
                                </div>
                                <div class="form-group col-md-3 mb-2">
                                    <label class="mb-1 text-muted small">Tratamiento (días) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" min="0" class="form-control form-control-sm"
                                        name="medicamentos[0][tratamiento]" placeholder="0" required>
                                </div>
                                <div class="form-group col-md-1 d-flex align-items-end mb-2">
                                    <button type="button" class="btn btn-sm btn-danger btn-del-med" disabled>×</button>
                                </div>
                            </div>

                            <div class="form-group mb-2">
                                <label class="mb-1 text-muted small">Posología</label>
                                <input type="text" class="form-control form-control-sm"
                                    name="medicamentos[0][posologia]" placeholder="1 comprimido cada 8 horas">
                            </div>

                            <div class="form-group mb-2">
                                <label class="mb-1 text-muted small">Indicaciones / Observaciones</label>
                                <textarea class="form-control form-control-sm" name="medicamentos[0][indicaciones]"
                                    rows="2" placeholder="No tomar con alcohol"></textarea>
                            </div>

                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input duplicado" id="dup0"
                                    name="medicamentos[0][forzarDuplicado]" value="1">
                                <label class="custom-control-label" for="dup0">Requiere duplicado</label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN: Prácticas (opcional) --}}
                <div class="receta-card">
                    <div class="text-uppercase text-info font-weight-bold small mb-2">Prácticas (opcional)</div>
                    <div class="form-group">
                        <label class="mb-1">Buscar práctica</label>
                        <select id="practica_search" class="form-control form-control-sm" style="width:100%"></select>
                    </div>
                    <div id="practicasList"></div>
                    <div id="practicasHidden"></div>
                </div>

                {{-- SECCIÓN: Diagnóstico --}}
                <div class="receta-card">
                    <div class="text-uppercase text-info font-weight-bold small mb-2">Diagnóstico</div>
                    <div class="form-row">
                        <div class="form-group col-md-6 mb-2">
                            <label class="mb-1">
                                Buscar CIE-10
                                <span class="text-muted text-sm">(Código de diagnostico estandar)</span>
                                <span class="text-danger">*</span>
                            </label>
                            <select id="diag_search" class="form-control form-control-sm" style="width:100%"></select>
                            <input type="hidden" id="diagnostico_codigo" name="diagnostico_codigo"
                                value="{{ old('diagnostico_codigo') }}">
                        </div>
                        <div class="form-group col-md-6 mb-2">
                            <label class="mb-1">Descripción <span class="text-danger">*</span></label>
                            <textarea class="form-control form-control-sm" id="diagnostico" name="diagnostico" rows="2"
                                placeholder="CIE-10 o descripción detallada">{{ old('diagnostico') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN: Médico --}}
                <div class="receta-card">
                    <div class="text-uppercase text-info font-weight-bold small mb-2">Médico</div>

                    <div class="form-row">
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1">Apellido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" value="{{ $medicoApellido }}"
                                readonly>
                            <input type="hidden" name="medico[apellido]" value="{{ $medicoApellido }}">
                        </div>

                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" value="{{ $medicoNombre }}"
                                readonly>
                            <input type="hidden" name="medico[nombre]" value="{{ $medicoNombre }}">
                        </div>

                        <div class="form-group col-md-2 mb-2">
                            <label class="mb-1">Tipo Doc <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm" disabled>
                                <option value="DNI" selected>DNI</option>
                            </select>
                            <input type="hidden" name="medico[tipoDoc]" value="DNI">
                        </div>

                        <div class="form-group col-md-2 mb-2">
                            <label class="mb-1">N° Doc <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" value="{{ $medicoDni }}" readonly>
                            <input type="hidden" name="medico[nroDoc]" value="{{ $medicoDni }}">
                        </div>

                        <div class="form-group col-md-2 mb-2">
                            <label class="mb-1">Sexo <span class="text-danger">*</span></label>
                            @php $sx = old('medico.sexo', $medicoSexo ?: 'M'); @endphp
                            <select class="form-control form-control-sm" disabled>
                                <option value="F" {{ $sx==='F' ?'selected':'' }}>Mujer (F)</option>
                                <option value="M" {{ $sx==='M' ?'selected':'' }}>Hombre (M)</option>
                                <option value="X" {{ $sx==='X' ?'selected':'' }}>No binario (X)</option>
                                <option value="O" {{ $sx==='O' ?'selected':'' }}>Otro (O)</option>
                            </select>
                            <input type="hidden" name="medico[sexo]" value="{{ $sx }}">
                        </div>
                    </div>

                    <div class="text-info font-weight-bold small mb-2">Matrícula</div>
                    <div class="form-row">
                        <div class="form-group col-md-2 mb-2">
                            <label>Tipo matrícula <span class="text-danger">*</span></label>
                            <select name="medico[matricula][tipo]" class="form-control form-control-sm">
                                <option value="MN" {{ old('medico.matricula.tipo')==='MN' ?'selected':'' }}>MN</option>
                                <option value="MP" {{ old('medico.matricula.tipo')==='MP' ?'selected':'' }}>MP</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4 mb-2">
                            <label>Número</label>
                            <input type="text" name="medico[matricula][numero]" class="form-control form-control-sm"
                                value="{{ old('medico.matricula.numero') }}" placeholder="Sólo dígitos (máx. 9)">
                        </div>
                        <div class="form-group col-md-6 mb-2">
                            <label>Provincia (requerida si MP)</label>
                            <input type="text" name="medico[matricula][provincia]" class="form-control form-control-sm"
                                value="{{ old('medico.matricula.provincia') }}" placeholder="Provincia">
                        </div>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="mt-3 d-flex">
                    <a href="{{ route('empleados.recetas') }}" class="btn-ejornal btn-ejornal-gris-claro">Volver</a>
                    {{-- <button type="submit" class="btn-ejornal btn-ejornal-base mr-2">Generar receta</button> --}}
                </div>
            </form>
        </div>
    </div>
</div>
@endsection