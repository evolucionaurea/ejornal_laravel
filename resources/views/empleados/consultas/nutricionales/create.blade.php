@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Crear consulta nutricional</h2>
            <p>Aquí puedes crear consultas nutricionales para un trabajador de la nomina</p>
            <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-gris-claro" href="{{ url('empleados/consultas/nutricionales') }}"><i
                        class="fas fa-arrow-circle-left"></i>Volver</a>
            </div>
        </div>

        @include('../../mensajes_validacion')

        @if ($errors->any())
        @foreach ($errors->all() as $error)
        <div class="alert alert-danger alert-dismissible fade show mr-4 ml-4" role="alert">
            {{$error}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endforeach
        @endif

        <div class="tarjeta" id="caratula">
            <p class="alert alert-info">Seleccione un trabajador de la nomina</p>
            {{-- Se completa por JS --}}
        </div>

        <div class="tarjeta">
            <form id="form_guardar_consulta_nutricional"
                action="{{action('EmpleadosConsultaNutricionalController@store')}}" accept-charset="UTF-8" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Trabajador <span style="color: red;">*</span></label>
                        <select required id="id_nomina" name="id_nomina" class="form-control form-control-sm select_2">
                            <option value="">--Seleccionar--</option>
                            @foreach ($nominas as $nomina)
                            <option value="{{ $nomina->id }}">{{ $nomina->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Cliente <span style="color: red;">*</span></label>
                        <input name="cliente" type="text" class="form-control form-control-sm" placeholder=""
                            value="{{ $cliente->nombre }}" disabled>
                        <input type="hidden" value="{{ $cliente->id }}" name="id_cliente" id="id_cliente_actual">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Tipo <span style="color: red;">*</span></label>
                        <select required id="tipo-consulta" name="tipo" class="form-control form-control-sm">
                            <option value="inicial" {{ old('tipo')=='inicial' ? 'selected' : '' }}>Inicial</option>
                            <option value="seguimiento" {{ old('tipo')=='seguimiento' ? 'selected' : '' }}>Seguimiento
                            </option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Fecha atención <span style="color: red;">*</span></label>
                        <input required id="data_picker_gral" name="fecha_atencion" type="text" class="form-control"
                            value="{{ old('fecha_atencion') }}">
                    </div>

                    {{-- Campos de tipo Inicial --}}
                    <div class="form-group col-md-4 campos-inicial">
                        <label>Objetivos</label>
                        <textarea class="form-control form-control-sm" name="objetivos" cols="30" rows="5"></textarea>
                    </div>
                    <div class="form-group col-md-4 campos-inicial">
                        <label>Gustos alimentarios</label>
                        <textarea class="form-control form-control-sm" name="gustos_alimentarios" cols="30"
                            rows="5"></textarea>
                    </div>
                    <div class="form-group col-md-4 campos-inicial">
                        <label>Comidas diarias</label>
                        <textarea class="form-control form-control-sm" name="comidas_diarias" cols="30"
                            rows="5"></textarea>
                    </div>
                    <div class="form-group col-md-4 campos-inicial">
                        <label>Descanso</label>
                        <textarea class="form-control form-control-sm" name="descanso" cols="30" rows="5"></textarea>
                    </div>
                    <div class="form-group col-md-4 campos-inicial">
                        <label>Intolerancias digestivas</label>
                        <textarea class="form-control form-control-sm" name="intolerancias_digestivas" cols="30"
                            rows="5"></textarea>
                    </div>
                    <div class="form-group col-md-4 campos-inicial">
                        <label>Alergias alimentarias</label>
                        <textarea class="form-control form-control-sm" name="alergias_alimentarias" cols="30"
                            rows="5"></textarea>
                    </div>

                    {{-- Campos de tipo Seguimiento --}}
                    <div class="form-group col-md-3 campos-seguimiento">
                        <label>Circunferencia cintura</label>
                        <input name="circunferencia_cintura" type="number" step="0.01" min="0" max="999.99"
                            class="form-control form-control-sm" value="">
                    </div>
                    <div class="form-group col-md-3 campos-seguimiento">
                        <label>Porcentaje de masa grasa</label>
                        <input name="porcent_masa_grasa" type="number" step="0.01" min="0" max="999.99"
                            class="form-control form-control-sm" value="">
                    </div>
                    <div class="form-group col-md-3 campos-seguimiento">
                        <label>Porcentaje masa muscular</label>
                        <input name="porcent_masa_muscular" type="number" step="0.01" min="0" max="999.99"
                            class="form-control form-control-sm" value="">
                    </div>
                    <div class="form-group col-md-3 campos-seguimiento">
                        <label>Próxima cita</label>
                        <input id="prox_cita" name="prox_cita" type="text" class="form-control"
                            value="{{ old('prox_cita') }}">
                    </div>
                    <div class="form-group col-md-3 campos-seguimiento">
                        <label>Act física</label>
                        <textarea class="form-control form-control-sm" name="act_fisica" cols="30" rows="5"></textarea>
                    </div>
                    <div class="form-group col-md-3 campos-seguimiento">
                        <label>Tránsito intestinal</label>
                        <textarea class="form-control form-control-sm" name="transito_intestinal" cols="30"
                            rows="5"></textarea>
                    </div>
                    <div class="form-group col-md-3 campos-seguimiento">
                        <label>Evolución</label>
                        <textarea class="form-control form-control-sm" name="evolucion" cols="30" rows="5"></textarea>
                    </div>
                    <div class="form-group col-md-3 campos-seguimiento">
                        <label>Medicaciones</label>
                        <textarea class="form-control form-control-sm" name="medicaciones" cols="30"
                            rows="5"></textarea>
                    </div>
                </div>
                <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Crear consulta
                    nutricional</button>
            </form>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>

@endsection