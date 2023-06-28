@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Creación de tareas adecuadas</h2>
            <p>Aquí puedes cargar a las tareas adecuadas que formarán parte de la nómina de la empresa</p>
            <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-gris-claro" href="{{url('empleados/tareas_livianas')}}"><i
                        class="fas fa-arrow-alt-circle-left"></i>Volver</a>
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

        <div class="tarjeta">
            <h4>Tarea adecuada</h4>
            <form action="{{action('EmpleadoTareasLivianasController@store')}}" accept-charset="UTF-8" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Trabajador</label>
                        <select required name="trabajador" class="form-control form-control-sm select_2">
                            @foreach ($trabajadores as $trabajador)
                            <option value="{{$trabajador->id}}">{{$trabajador->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>
                            Tipo
                            @if (auth()->user()->permiso_desplegables == 1)
                            <a style="color: #6f9eab; margin-right: 10px;" data-toggle="modal"
                                data-target="#crear_tipo_tarea_liviana" href="#">
                                <i class="fas fa-plus-circle"></i>
                            </a>
                            <a style="color: #6f9eab;" data-toggle="modal" data-target="#ver_tipo_tarea_liviana"
                                href="#">
                                <i class="fas fa-eye"></i>
                            </a>
                            @endif
                        </label>
                        <select name="tipo" class="form-control form-control-sm select_2">
                            @foreach ($tareas_livianas_tipo as $tipo)
                            <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Fecha inicio</label>
                        <input readonly required id="tarea_liviana_fecha_inicio" name="fecha_inicio" type="datetime"
                            class="form-control" value="{{ old(" fecha_inicio") }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Fecha final</label>
                        <input readonly id="tarea_liviana_fecha_final" name="fecha_final" type="datetime"
                            class="form-control" value="{{ old(" fecha_final") }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Fecha de retorno a tareas habituales</label>
                        <input readonly id="tarea_liviana_fecha_regreso" name="fecha_regreso_trabajar" type="datetime"
                            class="form-control" value="{{ old(" fecha_regreso") }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Documentación</label>
                        <input name="archivo" type="file" class="form-control-file">
                    </div>
                </div>
                <hr>
                <h4>Comunicación</h4>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>
                            Tipo
                            <a style="color: #6f9eab; margin-right: 10px;" data-toggle="modal"
                                data-target="#crear_tipo_comunicacion" href="#">
                                <i class="fas fa-plus-circle"></i>
                            </a>
                            <a style="color: #6f9eab;" data-toggle="modal" data-target="#ver_tipo_comunicacion"
                                href="#">
                                <i class="fas fa-eye"></i>
                            </a>
                        </label>
                        <select name="tipo_comunicacion" class="form-control form-control-sm select_2">
                            @foreach ($tipo_comunicacion_liviana as $tipo_com)
                            <option value="{{$tipo_com->id}}">{{$tipo_com->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-9">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Cargar Tarea Adecuada y
                    comunicación</button>
            </form>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>





<!-- Modal Crear tipo tarea liviana -->
<div class="modal fade" id="crear_tipo_tarea_liviana" tabindex="-1" aria-labelledby="tipo_tarea_liviana_titulo"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tipo_tarea_liviana_titulo">Crear tipo de Tarea Adecuada</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">
                        <form action="{{action('EmpleadoTareasLivianasController@tipo')}}" accept-charset="UTF-8"
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



<!-- Modal Ver tipo tarea liviana -->
<div class="modal fade" id="ver_tipo_tarea_liviana" tabindex="-1" aria-labelledby="ver_tipo_tarea_liviana_titulo"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ver_tipo_tarea_liviana_titulo">Tipos de Tareas livianas cargados</h5>
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
                                @foreach ($tareas_livianas_tipo as $tipo)
                                <tr>
                                    <td>{{$tipo->nombre}}</td>
                                    <td class="acciones_tabla" scope="row">
                                        <form class="" action="{{route('tareas_livianas.tipo_delete', $tipo->id)}}"
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




<!-- Modal Crear tipo comunicacion -->
<div class="modal fade" id="crear_tipo_comunicacion" tabindex="-1" aria-labelledby="tipo_comunicacion_titulo"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tipo_comunicacion_titulo">Crear tipo de Comunicación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">
                        <form action="{{action('EmpleadosComunicacionesLivianas@tipo')}}" accept-charset="UTF-8"
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





<!-- Modal Ver tipo Comunicacion -->
<div class="modal fade" id="ver_tipo_comunicacion" tabindex="-1" aria-labelledby="ver_tipo_comunicacion_titulo"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ver_tipo_comunicacion_titulo">Tipos de Comunicación cargados</h5>
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
                                @foreach ($tipo_comunicacion_liviana as $value)
                                <tr>
                                    <td>{{$value->nombre}}</td>
                                    <td class="acciones_tabla" scope="row">
                                        <form class=""
                                            action="{{route('comunicaciones_livianas.tipo_delete', $value->id)}}"
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




<script type="text/javascript">
    window.addEventListener("load", function(event) {

    $('.select_2').select2();

});

</script>


@endsection