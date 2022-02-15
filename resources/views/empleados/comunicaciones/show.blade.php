@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Comunicaciones de un ausentismo</h2>
            <p>Aquí puedes ver y cargar nuevas comunicaciones de ausentismo</p>
            <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-gris-claro" href="{{ url('empleados/ausentismos') }}"><i class="fas fa-arrow-circle-left"></i>Volver</a>
                <a data-toggle="modal" data-target="#cargar_comunicaciones_ausentismo" class="btn-ejornal btn-ejornal-success" href="#"><i class="fas fa-plus-circle"></i>Crear comunicación</a>
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
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 col-sm-12 text-center">
                            <i class="fas fa-user fa-10x"></i>
                            <br>
                            <br>
                            <h5>{{$ausencia->nombre}}</h5>
                        </div>
                        <div class="col-lg-4 col-md-5 col-sm-12">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <b>Tipo: </b> {{$ausencia->nombre_ausentismo}}
                                </li>
                                <li class="list-group-item">
                                    <b>Fecha inicio: </b> {{ (!empty($ausencia->fecha_inicio)) ? date('d/m/Y',strtotime($ausencia->fecha_inicio)) : "" }}
                                </li>
                                <li class="list-group-item">
                                    <b>Fecha final: </b> {{ (!empty($ausencia->fecha_final)) ? date('d/m/Y',strtotime($ausencia->fecha_final)) : "" }}
                                </li>
                                <li class="list-group-item">
                                    <b>Fecha en que regresó: </b> {{ (!empty($ausencia->fecha_regreso_trabajar)) ? date('d/m/Y',strtotime($ausencia->fecha_regreso_trabajar)) : "" }}
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <b>Email: </b> {{$ausencia->email}}</li>
                                <li class="list-group-item"><b>Estado: </b>
                                    @if ($ausencia->estado == 1)
                                    Activo
                                    @else
                                    Inactivo
                                    @endif
                                </li>
                                <li class="list-group-item">
                                    <b>Telefono: </b> {{$ausencia->telefono}}
                                </li>
                                <li class="list-group-item">
                                    <b>Archivo adjunto: </b>
                                    <br>
                                    <br>
                                    @if ($ausencia->archivo == null)
                                      No se adjuntó un archivo
                                    @else
                                      <a class="btn-ejornal btn-ejornal-gris-claro" href="{{route('ausentismos.archivo', $ausencia->id)}}">
                                        <i class="fa fa-file"></i>{{$ausencia->archivo}}
                                      </a>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    @if (isset($comunicaciones_ausentismo) && !empty($comunicaciones_ausentismo) && count($comunicaciones_ausentismo) > 0)

                    <div class="card-columns">
                        @foreach ($comunicaciones_ausentismo as $comunicacion)
                        <div class="card mt-2 mb-3">
                            <div class="card-body">
                                <h5 class="card-title text_primary">
                                    <b>Comunicación:</b> {{$comunicacion->nombre}}
                                    {{-- <a class="editar_comunicacion_ausentismo" href="#!" data-id="{{$comunicacion->id}}">
                                        <i class="fas fa-pen"></i>
                                    </a> --}}
                                </h5>
                                <p class="card-text">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <span style="font-weight: 600;" class="text_black">Descripcion: </span>
                                            <p>{{$comunicacion->descripcion}}</p>
                                        </li>
                                    </ul>
                                </p>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">
                                    <h6 class="ml-4">Última actualización: </h6>
                                    <span class="ml-4">{{ (!empty($comunicacion->updated_at)) ? date('d/m/Y',strtotime($comunicacion->updated_at)) : "" }}</span>
                                </small>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @else
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Sin datos</h4>
                        <p>No hay documentaciones de este ausentismo</p>
                    </div>
                    @endif


                </div>
            </div>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>




<!-- Modal -->
<div class="modal fade" id="cargar_comunicaciones_ausentismo" tabindex="-1" aria-labelledby="cargar_comunicaciones_ausentismo_titulo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cargar_comunicaciones_ausentismo_titulo">Crear Comunicacion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row p-4">
                    <form style="width: 100%;" action="{{action('EmpleadosComunicacionesController@store')}}" enctype="multipart/form-data" accept-charset="UTF-8" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_ausentismo" value="{{$ausencia->id}}">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Tipo de comunicación</label>
                            <select class="form-control" name="id_tipo">
                              @foreach ($tipo_comunicaciones as $tipo)
                                <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                              @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Descripcion</label>
                            <textarea required name="descripcion" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <button class="btn-ejornal btn-ejornal-success" type="submit" name="button">Crear comunicación</button>
                    </form>
                </div>

            </div>

        </div>
    </div>
</div>


<!-- Modal -->
{{-- <div class="modal fade" id="modal_edit_comunicacion_ausentismo" tabindex="-1" aria-labelledby="editar_comunicacion_ausentismo_titulo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editar_comunicacion_ausentismo_titulo">Editar comunicación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row p-4">

                    <form id="form_editar_comunicacion_ausencia" action="" enctype="multipart/form-data" accept-charset="UTF-8" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_comunicacion" value="">
                        <input name="_method" type="hidden" value="PUT">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Tipo de ausentismo</label>
                                <input required name="id_tipo" type="text" class="form-control" placeholder="">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Descripcion</label>
                                <textarea name="descripcion" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <button class="btn-ejornal btn-ejornal-success" type="submit" name="button">Guardar cambios</button>
                    </form>
                </div>

            </div>

        </div>
    </div>
</div> --}}


@endsection
