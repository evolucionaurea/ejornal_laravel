@extends('partials.layout')

@section('title', 'Empleado - Nueva Receta')

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



        <div class="tarjeta">
            <div class="px-3 py-3">
                <form id="recetaForm" method="POST" action="{{ route('empleados.recetas.store') }}"
                    data-url-get-financiadores="{{ route('empleados.recetas.get_financiadores') }}"
                    data-url-get-diagnosticos="{{ route('empleados.recetas.get_diagnosticos') }}"
                    data-url-get-medicamentos="{{ route('empleados.recetas.get_medicamentos') }}"
                    data-url-get-practicas="{{ route('empleados.recetas.get_practicas') }}">
                    @csrf

                    {{-- SECCIÓN: Trabajador --}}
                    <div class="mb-4">
                        <div class="text-uppercase text-info small mb-2 font-weight-bold">Trabajador</div>
                        <div class="form-group mb-2">
                            <label class="mb-1">Nómina <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm" id="id_nomina" name="id_nomina" required>
                                <option value="">Seleccione…</option>
                                @foreach($nominas as $n)
                                <option value="{{ $n->id }}" data-nombre="{{ $n->nombre }}" data-dni="{{ $n->dni }}"
                                    data-email="{{ $n->email }}" data-telefono="{{ $n->telefono }}"
                                    data-fecha-nacimiento="{{ optional($n->fecha_nacimiento) ? \Carbon\Carbon::parse($n->fecha_nacimiento)->format('Y-m-d') : '' }}"
                                    data-calle="{{ $n->calle }}" data-nro="{{ $n->nro }}"
                                    data-localidad="{{ $n->localidad }}" data-cod-postal="{{ $n->cod_postal }}" {{
                                    old('id_nomina')==$n->id ? 'selected' : '' }}>
                                    {{ $n->nombre }} — {{ optional($n->cliente)->nombre }}
                                    @if($n->dni) (DNI: {{ $n->dni }}) @endif
                                </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Busque por nombre o DNI.</small>
                        </div>
                    </div>

                    {{-- SECCIÓN: Diagnóstico --}}
                    <div class="mb-4">
                        <div class="text-uppercase text-info font-weight-bold small mb-2">Diagnóstico</div>
                        <div class="form-row">
                            <div class="form-group col-md-6 mb-2">
                                <label class="mb-1">Buscar CIE-10</label>
                                <select id="diag_search" class="form-control form-control-sm"
                                    style="width:100%"></select>
                                <input type="hidden" id="diagnostico_codigo" name="diagnostico_codigo"
                                    value="{{ old('diagnostico_codigo') }}">
                            </div>
                            <div class="form-group col-md-6 mb-2">
                                <label class="mb-1">Descripción</label>
                                <textarea class="form-control form-control-sm" id="diagnostico" name="diagnostico"
                                    rows="2"
                                    placeholder="CIE-10 o descripción detallada">{{ old('diagnostico') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN: Paciente --}}
                    <div class="mb-4">
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
                            <div class="form-group col-md-2 mb-2">
                                <label class="mb-1">Sexo</label>
                                <select class="form-control form-control-sm" name="paciente[sexo]">
                                    @foreach(['F','M','X','O'] as $sx)
                                    <option value="{{ $sx }}" {{ old('paciente.sexo')===$sx ? 'selected' :'' }}>{{ $sx
                                        }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3 mb-2">
                                <label class="mb-1">Fecha Nacimiento</label>
                                <input type="hidden" class="form-control form-control-sm"
                                    name="paciente[fechaNacimiento]" value="{{ old('paciente.fechaNacimiento') }}">
                                {{-- visible lo agrega el JS (readonly + datepicker ES) --}}
                            </div>
                            <div class="form-group col-md-3 mb-2">
                                <label class="mb-1">Email</label>
                                <input type="email" class="form-control form-control-sm" name="paciente[email]"
                                    value="{{ old('paciente.email') }}">
                            </div>
                            <div class="form-group col-md-3 mb-2">
                                <label class="mb-1">Teléfono</label>
                                <input type="text" class="form-control form-control-sm" name="paciente[telefono]"
                                    value="{{ old('paciente.telefono') }}">
                            </div>
                        </div>

                        <div class="text-info font-weight-bold small mb-2">Domicilio (opcional)</div>
                        <div class="form-row">
                            <div class="form-group col-md-4 mb-2">
                                <label class="mb-1">Calle</label>
                                <input type="text" class="form-control form-control-sm" name="domicilio[calle]"
                                    value="{{ old('domicilio.calle') }}">
                            </div>
                            <div class="form-group col-md-2 mb-2">
                                <label class="mb-1">Número</label>
                                <input type="text" class="form-control form-control-sm" name="domicilio[numero]"
                                    value="{{ old('domicilio.numero') }}">
                            </div>
                            <div class="form-group col-md-3 mb-2">
                                <label class="mb-1">Localidad</label>
                                <input type="text" class="form-control form-control-sm" name="domicilio[localidad]"
                                    value="{{ old('domicilio.localidad') }}">
                            </div>
                            <div class="form-group col-md-2 mb-2">
                                <label class="mb-1">Provincia</label>
                                <input type="text" class="form-control form-control-sm" name="domicilio[provincia]"
                                    value="{{ old('domicilio.provincia') }}">
                            </div>
                            <div class="form-group col-md-1 mb-2">
                                <label class="mb-1">CP</label>
                                <input type="text" class="form-control form-control-sm" name="domicilio[cp]"
                                    value="{{ old('domicilio.cp') }}">
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN: Cobertura --}}
                    <div class="mb-4">
                        <div class="text-uppercase text-info font-weight-bold small mb-2">Cobertura</div>
                        <div class="form-row">
                            <div class="form-group col-md-5 mb-2">
                                <label class="mb-1">Financiador</label>
                                <select id="financiador" class="form-control form-control-sm"
                                    style="width:100%"></select>
                            </div>
                            <div class="form-group col-md-4 mb-2">
                                <label class="mb-1">Plan</label>
                                <select id="plan" class="form-control form-control-sm" style="width:100%"></select>
                            </div>
                            <div class="form-group col-md-3 mb-2">
                                <label class="mb-1">Credencial / Afiliado</label>
                                <input type="text" class="form-control form-control-sm" name="cobertura[credencial]"
                                    value="{{ old('cobertura.credencial') }}">
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN: Médico --}}
                    <div class="mb-4">
                        <div class="text-uppercase text-info font-weight-bold small mb-2">Médico</div>
                        <div class="form-row">
                            <div class="form-group col-md-3 mb-2">
                                <label class="mb-1">Apellido <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="medico[apellido]"
                                    value="{{ old('medico.apellido') }}" required>
                            </div>
                            <div class="form-group col-md-3 mb-2">
                                <label class="mb-1">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="medico[nombre]"
                                    value="{{ old('medico.nombre') }}" required>
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
                                    value="{{ old('medico.nroDoc') }}" required>
                            </div>
                            <div class="form-group col-md-2 mb-2">
                                <label class="mb-1">Sexo <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm" name="medico[sexo]" required>
                                    @foreach(['F','M','X','O'] as $sx)
                                    <option value="{{ $sx }}" {{ old('medico.sexo')===$sx ? 'selected' :'' }}>{{ $sx }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="text-info font-weight-bold small mb-2">Matrícula</div>
                        <div class="form-row">
                            <div class="form-group col-md-2 mb-2">
                                <label class="mb-1">Tipo <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm" name="medico[matricula][tipo]" required>
                                    <option value="MN" {{ old('medico.matricula.tipo')==='MN' ? 'selected' :'' }}>MN
                                    </option>
                                    <option value="MP" {{ old('medico.matricula.tipo')==='MP' ? 'selected' :'' }}>MP
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 mb-2">
                                <label class="mb-1">Número <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="medico[matricula][numero]"
                                    value="{{ old('medico.matricula.numero') }}" required>
                            </div>
                            <div class="form-group col-md-4 mb-2">
                                <label class="mb-1">Provincia (si MP)</label>
                                <input type="text" class="form-control form-control-sm"
                                    name="medico[matricula][provincia]" value="{{ old('medico.matricula.provincia') }}"
                                    placeholder="Ej: Buenos Aires">
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN: Medicamentos --}}
                    <div class="mb-4">
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
                                        <label class="mb-1 text-muted small">Buscar medicamento</label>
                                        <select class="form-control form-control-sm sel-medicamento"
                                            style="width:100%"></select>
                                    </div>
                                    <div class="form-group col-md-2 mb-2">
                                        <label class="mb-1 text-muted small">Cantidad</label>
                                        <input type="number" min="1" class="form-control form-control-sm"
                                            name="medicamentos[0][cantidad]" required>
                                    </div>
                                    <div class="form-group col-md-3 mb-2">
                                        <label class="mb-1 text-muted small">Reg. Nº</label>
                                        <input type="text" class="form-control form-control-sm regno"
                                            name="medicamentos[0][regNo]" placeholder="Ej: 20095">
                                    </div>
                                    <div class="form-group col-md-3 mb-2">
                                        <label class="mb-1 text-muted small">Presentación</label>
                                        <input type="text" class="form-control form-control-sm presentacion"
                                            name="medicamentos[0][presentacion]" placeholder="comp. blister x 10">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4 mb-2">
                                        <label class="mb-1 text-muted small">Nombre</label>
                                        <input type="text" class="form-control form-control-sm nombre"
                                            name="medicamentos[0][nombre]" placeholder="TAFIROL">
                                    </div>
                                    <div class="form-group col-md-4 mb-2">
                                        <label class="mb-1 text-muted small">Droga</label>
                                        <input type="text" class="form-control form-control-sm droga"
                                            name="medicamentos[0][nombreDroga]" placeholder="paracetamol">
                                    </div>
                                    <div class="form-group col-md-3 mb-2">
                                        <label class="mb-1 text-muted small">Tratamiento (días)</label>
                                        <input type="number" min="0" class="form-control form-control-sm"
                                            name="medicamentos[0][tratamiento]" placeholder="0">
                                    </div>
                                    <div class="form-group col-md-1 d-flex align-items-end mb-2">
                                        <button type="button" class="btn btn-sm btn-danger btn-del-med"
                                            disabled>×</button>
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
                    <div class="mb-4">
                        <div class="text-uppercase text-info font-weight-bold small mb-2">Prácticas (opcional)</div>
                        <div class="form-group">
                            <label class="mb-1">Buscar práctica</label>
                            <select id="practica_search" class="form-control form-control-sm"
                                style="width:100%"></select>
                        </div>
                        <div id="practicasList"></div>
                        <div id="practicasHidden"></div>
                    </div>

                    {{-- Acciones --}}
                    <div class="mt-3 d-flex">
                        <a href="{{ route('empleados.recetas') }}" class="btn-ejornal btn-ejornal-base">Volver</a>
                        <button type="submit" class="btn-ejornal btn-ejornal-gris-claro mr-2">Generar receta</button>
                    </div>
                </form>
            </div>
        </div>



    </div>
</div>

@endsection