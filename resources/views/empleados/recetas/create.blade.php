@extends('partials.layout')

@section('title', 'Empleado - Nueva Receta')

<style>
    /* Card por bloque/sección */
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

    /* Separación entre secciones sin dejar el mb-4 “vacío” */
    .receta-card.mb-4 {
        margin-bottom: 14px !important;
    }

    /* Dark mode (si tu sitio usa prefers-color-scheme) */
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

                            @foreach($nominas as $n)
                            @php
                            $isSelected = false;

                            // 1) Si viene por parámetro, tiene prioridad
                            if (!empty($selectedNominaId)) {
                            $isSelected = ((int)$selectedNominaId === (int)$n->id);
                            } else {
                            // 2) Si no viene parámetro, usamos old()
                            $isSelected = (old('id_nomina') == $n->id);
                            }
                            @endphp

                            <option value="{{ $n->id }}" data-nombre="{{ $n->nombre }}" data-dni="{{ $n->dni }}"
                                data-email="{{ $n->email }}" data-telefono="{{ $n->telefono }}"
                                data-fecha-nacimiento="{{ $n->fecha_nacimiento ?? '' }}" data-calle="{{ $n->calle }}"
                                data-nro="{{ $n->nro }}" data-localidad="{{ $n->localidad }}"
                                data-cod-postal="{{ $n->cod_postal }}" {{ $isSelected ? 'selected' : '' }}>
                                {{ $n->nombre }} — {{ optional($n->cliente)->nombre }}
                                @if($n->dni) (DNI: {{ $n->dni }}) @endif
                            </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Busque por nombre o DNI.</small>
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
                                <option value="{{ $doc }}" {{ old('paciente.tipoDoc')===$doc ? 'selected' :'' }}>{{
                                    $doc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2 mb-2">
                            <label class="mb-1">N° Doc</label>
                            <input type="text" class="form-control form-control-sm" name="paciente[nroDoc]"
                                value="{{ old('paciente.nroDoc') }}">
                        </div>
                        <div class="form-group  col-md-2 mb-2">
                            <label class="mb-1">Sexo</label>
                            <select name="paciente[sexo]" class="form-control form-control-sm">
                                <option value="M">Hombre (M)</option>
                                <option value="F">Mujer (F)</option>
                                <option value="X">No binario (X)</option>
                                <option value="O">Otro (O)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1">Fecha Nacimiento</label>
                            <input type="hidden" class="form-control form-control-sm" name="paciente[fechaNacimiento]"
                                value="{{ old('paciente.fechaNacimiento') }}">
                            {{-- visible lo agrega el JS (readonly + datepicker ES) --}}
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

                    <div class="text-info font-weight-bold small mb-2">Domicilio</div>
                    <div class="form-row">
                        <div class="form-group col-md-5">
                            <label>Calle <span class="text-danger">*</span></label>
                            <input type="text" name="domicilio[calle]" class="form-control form-control-sm">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Número <span class="text-danger">*</span></label>
                            <input type="text" name="domicilio[numero]" class="form-control form-control-sm">
                        </div>
                        <div class="form-group col-md-5">
                            <label>Provincia <span class="text-danger">*</span></label>
                            {{-- Se convierte a <select> si hay catálogo --}}
                                <input type="text" name="domicilio[provincia]" class="form-control form-control-sm">
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
                        <button id="btnAddMed" class="btn-ejornal btn-ejornal-dark" type="button">+
                            Agregar</button>
                    </div>
                    <div id="medsWrapper" class="mt-2">
                        {{-- La primera fila ya viene del Blade original --}}
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
                                    <label class="mb-1 text-muted small">Reg. Nº </label>
                                    <input type="text" class="form-control form-control-sm regno"
                                        name="medicamentos[0][regNo]" placeholder="Ej: 20095">
                                </div>
                                <div class="form-group col-md-3 mb-2">
                                    <label class="mb-1 text-muted small">Presentación <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm presentacion"
                                        name="medicamentos[0][presentacion]" placeholder="comp. blister x 10">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4 mb-2">
                                    <label class="mb-1 text-muted small">Nombre <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm nombre"
                                        name="medicamentos[0][nombre]" placeholder="TAFIROL">
                                </div>
                                <div class="form-group col-md-4 mb-2">
                                    <label class="mb-1 text-muted small">Droga <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm droga"
                                        name="medicamentos[0][nombreDroga]" placeholder="paracetamol">
                                </div>
                                <div class="form-group col-md-3 mb-2">
                                    <label class="mb-1 text-muted small">Tratamiento (días) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" min="0" class="form-control form-control-sm"
                                        name="medicamentos[0][tratamiento]" placeholder="0">
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
                                <span class="text-muted text-sm">
                                    (Código de diagnostico estandar)
                                </span>
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
                            <input type="text" class="form-control form-control-sm" name="medico[apellido]"
                                value="{{ old('medico.apellido', $medicoApellido) }}" required>
                        </div>

                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="medico[nombre]"
                                value="{{ old('medico.nombre', $medicoNombre) }}" required>
                        </div>

                        <div class="form-group col-md-2 mb-2">
                            <label class="mb-1">Tipo Doc <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm" name="medico[tipoDoc]" required>
                                @foreach(['DNI','LE','LC','CI','Pasaporte'] as $doc)
                                <option value="{{ $doc }}" {{ old('medico.tipoDoc')===$doc ? 'selected' :'' }}>{{
                                    $doc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2 mb-2">
                            <label class="mb-1">N° Doc <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="medico[nroDoc]"
                                value="{{ auth()->user()->dni }}" required>
                        </div>
                        <div class="form-group col-md-2 mb-2">
                            <label class="mb-1">Sexo <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm" name="medico[sexo]" required>
                                <option value="F">Mujer (F)</option>
                                <option value="M">Hombre (M)</option>
                                <option value="X">No binario (X)</option>
                                <option value="O">Otro (O)</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-info font-weight-bold small mb-2">Matrícula</div>
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label>Tipo matrícula <span class="text-danger">*</span></label>
                            <select name="medico[matricula][tipo]" class="form-control form-control-sm">
                                <option value="MN">MN</option>
                                <option value="MP">MP</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Número</label>
                            <input type="text" name="medico[matricula][numero]" class="form-control form-control-sm"
                                placeholder="Sólo dígitos (máx. 9)">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Provincia (requerida si MP)</label>
                            {{-- Se completa por JS --}}
                            <input type="text" name="medico[matricula][provincia]" class="form-control form-control-sm"
                                placeholder="Provincia">
                        </div>
                    </div>
                </div>


                {{-- Acciones --}}
                <div class="mt-3 d-flex">
                    <a href="{{ route('empleados.recetas') }}" class="btn-ejornal btn-ejornal-gris-claro">Volver</a>
                    <button type="submit" class="btn-ejornal btn-ejornal-base mr-2">Generar receta</button>
                </div>
            </form>
        </div>

    </div>
</div>

@endsection