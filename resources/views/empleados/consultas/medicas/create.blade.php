@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Creación de consultas medicas</h2>
            <p>Aquí puedes cargar los consultas medicas pertinentes a la empresa en la que estas trabajando</p>
        </div>

        @include('../../../mensajes_validacion')
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

        <div class="tarjeta">
            <form id="form_guardar_consulta_medica" action="{{action('EmpleadoConsultaMedicaController@store')}}"
                accept-charset="UTF-8" method="post">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Trabajador: <span style="color: red;">*</span></label>
                        <select name="nomina" class="form-control form-control-sm select_2">
                            @foreach ($nominas as $nomina)
                            <option value="{{$nomina->id}}">{{$nomina->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>
                            Diagnóstico:
                            @if (auth()->user()->permiso_desplegables == 1)
                            <a style="color: #6f9eab; margin-right: 10px;" data-toggle="modal"
                                data-target="#crear_diagnostico" href="#">
                                <i class="fas fa-plus-circle"></i>
                            </a>
                            <a style="color: #6f9eab;" data-toggle="modal" data-target="#ver_tipo_diagnostico" href="#">
                                <i class="fas fa-eye"></i>
                            </a>
                            @endif
                        </label>
                        <select name="tipo" class="form-control form-control-sm">
                            @foreach ($diagnostico_consultas as $tipo)
                            <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Fecha <span style="color: red;">*</span></label>
                        <input readonly required id="data_picker_gral" name="fecha" type="datetime" class="form-control"
                            value="{{ old(" fecha") }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Amerita salida <span style="color: red;">*</span></label>
                        <select name="amerita_salida" class="form-control form-control-sm">
                            <option value="" hidden>Seleccione</option>
                            <option value="1">Si</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <ul class="list-group">
                        <label>
                            Medicación:
                            <a id="cargar_medicacion" style="color: #6f9eab; margin-right: 10px;" href="#">
                                <i class="fas fa-plus-circle"></i>
                            </a>
                        </label>
                        <div class="listado_medicaciones">

                        </div>
                        <div class="listado_medicaciones_inputs_ocultos">

                        </div>
                    </ul>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Temperatura axiliar</label>
                        <input name="temperatura_auxiliar" type="number" class="form-control" value="{{ old("
                            temperatura_auxiliar") }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Peso</label>
                        <input name="peso" type="number" class="form-control" value="{{ old(" peso") }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Altura</label>
                        <input name="altura" type="number" class="form-control" value="{{ old(" altura") }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>IMC</label>
                        <input disabled name="imc_disabled" type="text" class="form-control" value="{{ old(" imc") }}">
                        <input name="imc" type="hidden" class="form-control" value="{{ old(" imc") }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Glucemia</label>
                        <input name="glucemia" type="number" class="form-control" value="{{ old(" glucemia") }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Saturacion oxígeno</label>
                        <input name="saturacion_oxigeno" type="number" class="form-control" value="{{ old("
                            saturacion_oxigeno") }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Tension arterial</label>
                        <input name="tension_arterial" type="text" class="form-control" value="{{ old("
                            tension_arterial") }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Frecuencia Cardíaca</label>
                        <input name="frec_cardiaca" type="number" class="form-control" value="{{ old(" frec_cardiaca")
                            }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Derivación consulta <span style="color: red;">*</span></label>
                        <select name="derivacion_consulta" class="form-control form-control-sm">
                            <option value="" hidden>Seleccione</option>
                            <option value="Sanatorio">Sanatorio</option>
                            <option value="ART">ART</option>
                            <option value="Vuelve a trabajar">Vuelve a trabajar</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Anamnesis</label>
                        <textarea name="anamnesis" required class="form-control"
                            rows="3">{{ old("anamnesis") }}</textarea>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Tratamiento <span style="color: red;">*</span></label>
                        <textarea name="tratamiento" required class="form-control"
                            rows="3">{{ old("tratamiento") }}</textarea>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Observaciones <span style="color: red;">*</span></label>
                        <textarea name="observaciones" required class="form-control"
                            rows="3">{{ old("observaciones") }}</textarea>
                    </div>
                </div>
                <button id="guarda_consulta" class="btn-ejornal btn-ejornal-base" type="submit" name="button">Guardar
                    consulta médica</button>
            </form>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>

@endsection



<!-- Modal Crear tipo diagnostico consulta -->
<div class="modal fade" id="crear_diagnostico" tabindex="-1" aria-labelledby="diagnostico_titulo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="diagnostico_titulo">Crear tipo de diagnostico</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">
                        <form action="{{action('EmpleadoConsultaMedicaController@tipo')}}" accept-charset="UTF-8"
                            method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label>Nombre</label>
                                <input name="nombre" type="text" class="form-control form-control-sm" placeholder="">
                            </div>
                            <button type="submit" class="btn-ejornal btn-ejornal-success">Crear tipo</button>
                            <button type="button" class="btn-ejornal btn-ejornal-gris-claro"
                                data-dismiss="modal">Cerrar</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



<!-- Modal Ver tipo Diagnostico consulta -->
<div class="modal fade" id="ver_tipo_diagnostico" tabindex="-1" aria-labelledby="ver_tipo_diagnostico_titulo"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ver_tipo_diagnostico_titulo">Tipos de Diagnostico cargados</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">


                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-hover table-sm ">
                            <thead>
                                <tr>
                                    <th class="th-lg">
                                        <a>
                                            Tipo
                                            <i class="fas fa-sort ml-1"></i>
                                        </a>
                                    </th>
                                    <th class="th-lg">
                                        <a href="">
                                            Acciones
                                            <i class="fas fa-sort ml-1"></i>
                                        </a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($diagnostico_consultas as $tipo)
                                <tr>
                                    <td>{{$tipo->nombre}}</td>
                                    <td class="acciones_tabla" scope="row">
                                        <form class="" action="{{route('consultas.medicas.tipo_delete', $tipo->id)}}"
                                            method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button title="Eliminar" type="submit">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>





<!-- Modal Cargar tipo de medicacion -->
<div class="modal fade" id="cargar_medicacion_abrir" tabindex="-1" aria-labelledby="cargar_medicacion_titulo"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cargar_medicacion_titulo">Suministrar medicación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">
                        <form class="modal_medicacion_a_suministrar" action="" accept-charset="UTF-8" method="">
                            @foreach ($stock_medicamentos as $medicamento)
                            <div class="btn-toolbar mb-3" role="toolbar" aria-label="Toolbar with button groups">
                                <h6>{{$medicamento->nombre}}</h6>
                                <div style="width: 100%;" class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">En stock: {{$medicamento->stock}}</div>
                                    </div>
                                    <input name="{{$medicamento->id}}" type="text" class="form-control" placeholder=""
                                        value="">
                                </div>
                            </div>
                            @endforeach
                            <button id="aceptar_suministrar_medicamentos" type="button"
                                class="btn-ejornal btn-ejornal-success">Aceptar</button>
                            <button type="button" class="btn-ejornal btn-ejornal-gris-claro"
                                data-dismiss="modal">Cerrar</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>




<!-- Modal confirmación Final -->
<div class="modal fade" id="consulta_confirmacion_final" tabindex="-1"
    aria-labelledby="consulta_confirmacion_final_Titulo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="consulta_confirmacion_final_Titulo">Advertencia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-light" role="alert">
                            <p>Una vez creada la consulta no podrá editarse. Presione Aceptar para continuar o cancelar
                                para revisar la consulta.</p>
                            <hr>
                            <a id="consulta_medica_crear_ok" type="button"
                                class="btn-ejornal btn-ejornal-success">Aceptar</a>
                            <a type="button" class="btn-ejornal btn-ejornal-gris-claro" data-dismiss="modal">Cerrar</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    window.addEventListener("load", function(event) {

        $('.select_2').select2();

        let dominio = window.location.host;
        let imc;
        let medicamentos = '';
        let medicamentos_suministrados = [];

        $(".form-row .form-group input[name='peso']").keyup(function() {
            let peso = $(this).val();
            let altura = $(".form-row .form-group input[name='altura']").val();
            if (peso != '' && peso != null && peso != undefined && altura != '' && altura != null && altura != undefined && altura !== 0 || peso !== 0) {
              imc = parseFloat(peso / (Math.pow((altura/100), 2))).toFixed(2);
                $(".form-row .form-group input[name='imc']").val(imc);
                $(".form-row .form-group input[name='imc_disabled']").val(imc);
            } else {
                $(".form-row .form-group input[name='imc']").val("");
            }
            if ($(".form-row .form-group input[name='imc']").val() == NaN) {
                $(".form-row .form-group input[name='imc']").val("");
            }
            if ($(".form-row .form-group input[name='imc']").val() == Infinity) {
                $(".form-row .form-group input[name='imc']").val("");
            }
        });


        $(".form-row .form-group input[name='altura']").keyup(function() {
            let altura = $(this).val();
            let peso = $(".form-row .form-group input[name='peso']").val();
            if (altura != '' && altura != null && altura != undefined && peso != '' && peso != null && peso != undefined && altura !== 0 || peso !== 0) {
              imc = parseFloat(peso / (Math.pow((altura/100), 2))).toFixed(2);
                $(".form-row .form-group input[name='imc']").val(imc);
                $(".form-row .form-group input[name='imc_disabled']").val(imc);
            } else {
                $(".form-row .form-group input[name='imc']").val("");
            }
            if ($(".form-row .form-group input[name='imc']").val() == NaN) {
                $(".form-row .form-group input[name='imc']").val("");
            }
            if ($(".form-row .form-group input[name='imc']").val() == Infinity) {
                $(".form-row .form-group input[name='imc']").val("");
            }
        });


        $('#cargar_medicacion').click(function() {
            $('#cargar_medicacion_abrir').modal('show');
            medicamentos = '';
            medicamentos_suministrados = [];
        });



        $("#aceptar_suministrar_medicamentos").click(function() {

            // VALIDAR QUE NO PONGA MAS DEL STOCK QUE HAY EN CADA MEDICAMENTO
            medicamentos = '';
            medicamentos_suministrados = [];

            $(".modal_medicacion_a_suministrar .btn-toolbar").each(function(index) {

                medicamentos_suministrados.push({
                    'nombre': $(this).find('h6').text(),
                    'id_medicamento': $(this).find('input').attr('name'),
                    'suministrados': $(this).find('input').val()
                })
            });
            console.log(medicamentos_suministrados);

            $('#cargar_medicacion_abrir').modal('hide');

            // Remover si el elemento existe
            if ($(".ul_lista_medicamentos").length > 0) {
                $('.ul_lista_medicamentos').remove();
            }
            if ($(".listado_medicaciones_inputs_ocultos input").length > 0) {
                $('.listado_medicaciones_inputs_ocultos input').remove();
            }

            for (i = 0; i < medicamentos_suministrados.length; i++) {
              if (medicamentos_suministrados[i].suministrados.length !== 0 || medicamentos_suministrados[i].suministrados !== '0') {

                $(".listado_medicaciones").append(
                    $('<ul>', {
                        'class': 'ul_lista_medicamentos'
                    }).append(
                        $('<li>', {
                            'class': ''
                        }).append(
                            $('<p>', {
                                'text': medicamentos_suministrados[i].nombre + ':  ' + medicamentos_suministrados[i].suministrados
                            })
                        )
                    )
                );

                $(".listado_medicaciones_inputs_ocultos").append(
                    $('<input>', {
                        'type': 'hidden',
                        'name': 'medicaciones[]',
                        'value': medicamentos_suministrados[i].id_medicamento+','+medicamentos_suministrados[i].suministrados
                    })
                );
              }
            }

            $('.listado_medicaciones ul li p').css('color', 'grey');

        });


        $("#guarda_consulta").click(function(e) {
            e.preventDefault();
            $('#consulta_confirmacion_final').modal('show');

            $('#consulta_medica_crear_ok').click(function(e) {
                $('#form_guardar_consulta_medica').submit();
                $('#consulta_confirmacion_final').modal('hide');
            });

        });


    });
</script>