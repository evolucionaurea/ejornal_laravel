@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Edición de testeos de covid de la empresa</h2>
            <p>Aquí podrá editar los testeos de covid de un trabajador de la empresa</p>
            <div class="cabecera_acciones">
      					<a class="btn-ejornal btn-ejornal-gris-claro" href="{{url('empleados/covid/testeos')}}"><i class="fas fa-arrow-alt-circle-left"></i>Volver</a>
      			</div>
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
            <form action="{{action('EmpleadosCovidTesteoController@update', $testeo->id)}}" accept-charset="UTF-8" method="post">
                {{ csrf_field() }}
                <input name="_method" type="hidden" value="PUT">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Trabajador:</label>
                        <select name="nomina" class="form-control form-control-sm">
                            @foreach ($nominas as $nomina)
                            @if ($nomina->id == $testeo->id_nomina)
                            <option selected value="{{$nomina->id}}">{{$nomina->nombre}}</option>
                            @else
                            <option value="{{$nomina->id}}">{{$nomina->nombre}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>
                            Tipo de testeo:
                            @if (auth()->user()->permiso_desplegables == 1)
                            <a style="color: #6f9eab; margin-right: 10px;" data-toggle="modal" data-target="#crear_tipo_testeo" href="#">
                                <i class="fas fa-plus-circle"></i>
                            </a>
                            <a style="color: #6f9eab;" data-toggle="modal" data-target="#ver_tipo_testeo" href="#">
                                <i class="fas fa-eye"></i>
                            </a>
                            @endif
                        </label>
                        <select name="tipo" class="form-control form-control-sm">
                            @foreach ($covid_testeos_tipo as $tipo)
                            @if ($tipo->id == $testeo->id_tipo)
                            <option selected value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                            @else
                            <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Fecha</label>
                        <input id="data_picker_gral" name="fecha" type="datetime" class="form-control" value="{{ (!empty($testeo->fecha)) ? date('d/m/Y',strtotime($testeo->fecha)) : "" }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Resultado</label>
                        <select name="resultado" class="form-control form-control-sm">
                            <option @php
                            if ($testeo->resultado == 'positivo') {
                            echo "selected";
                            }
                            @endphp
                            value="positivo">Positivo</option>
                            <option @php
                            if ($testeo->resultado == 'negativo') {
                            echo "selected";
                            }
                            @endphp
                            value="negativo">Negativo</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Laboratorio</label>
                        <textarea required name="laboratorio" class="form-control" rows="3">{{$testeo->laboratorio}}</textarea>
                    </div>
                    <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Guardar cambios</button>
                </div>
            </form>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>

@endsection





<!-- Modal Crear tipo covid testeo -->
<div class="modal fade" id="crear_tipo_testeo" tabindex="-1" aria-labelledby="tipo_testeo_titulo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tipo_testeo_titulo">Crear tipo de Testeo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">
                        <form action="{{action('EmpleadosCovidTesteoController@tipo')}}" accept-charset="UTF-8" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label>Nombre</label>
                            <input name="nombre" type="text" class="form-control form-control-sm" placeholder="">
                        </div>
                        <button type="submit" class="btn-ejornal btn-ejornal-success">Crear tipo</button>
                        <button type="button" class="btn-ejornal btn-ejornal-gris-claro" data-dismiss="modal">Cerrar</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



<!-- Modal Ver tipo Testeo covid -->
<div class="modal fade" id="ver_tipo_testeo" tabindex="-1" aria-labelledby="ver_tipo_testeo_titulo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ver_tipo_testeo_titulo">Tipos de Testeo cargados</h5>
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
                                @foreach ($covid_testeos_tipo as $tipo)
                                <tr>
                                    <td>{{$tipo->nombre}}</td>
                                    <td class="acciones_tabla" scope="row">
                                        <form class="" action="{{route('covid.testeos.tipo_delete', $tipo->id)}}" method="post">
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
