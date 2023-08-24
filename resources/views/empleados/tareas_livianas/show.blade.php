@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Historial de tareas adecuadas</h2>
            <p>Aquí puedes ver el historial de tareas adecuadas del trabajador seleccionado</p>
            <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-gris-claro" href="{{ url('empleados/tareas_livianas') }}"><i
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

        <div class="tarjeta">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-5 col-sm-12">
                            <i class="fas fa-user fa-10x"></i>
                        </div>
                        <div class="col-lg-9 col-md-7 col-sm-12">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <b>Trabajador/a: </b> {{$tareas_livianas[0]->nombre}}
                                </li>
                                <li class="list-group-item">
                                    <b>Email: </b> {{$tareas_livianas[0]->email}}
                                </li>
                                <li class="list-group-item"><b>Estado: </b>
                                    @if ($tareas_livianas[0]->estado == 1)
                                    Activo
                                    @else
                                    Inactivo
                                    @endif
                                </li>
                                <li class="list-group-item">
                                    <b>Telefono: </b> {{$tareas_livianas[0]->telefono}}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    @foreach ($tareas_livianas as $tarea_liviana)
                    <div class="accordion mb-4" id="accordionExample">
                        <div class="card">
                            <div class="card-header bg-dark text-white cabecera_consultas_historial" id="headingOne">
                                <h2 class="mb-0 d-flex">
                                    <button class="btn btn-link btn-block text-left" type="button"
                                        data-toggle="collapse" data-target="#collapse_{{ $tarea_liviana->id }}"
                                        aria-expanded="true" aria-controls="collapse_{{ $tarea_liviana->id }}">
                                        <b>Tarea adecuada por:</b> {{$tarea_liviana->nombre_tarea_liviana}}
                                    </button>
                                    <i class="fal fa-chevron-circle-down text-white"></i>
                                </h2>
                            </div>
                            <div id="collapse_{{ $tarea_liviana->id }}" class="collapse show"
                                aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="card_consultas">
                                        <div class="card_consulta">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item">
                                                    <span style="font-weight: 600;" class="text_black">Fecha inicio:
                                                    </span>
                                                    {{ (!empty($tarea_liviana->fecha_inicio)) ?
                                                    date('d/m/Y',strtotime($tarea_liviana->fecha_inicio)) : "" }}
                                                </li>
                                                <li class="list-group-item">
                                                    <span style="font-weight: 600;" class="text_black">Fecha final
                                                    </span>
                                                    {{ (!empty($tarea_liviana->fecha_final)) ?
                                                    date('d/m/Y',strtotime($tarea_liviana->fecha_final)) : "" }}
                                                </li>
                                                <li class="list-group-item">
                                                    <span style="font-weight: 600;" class="text_black">Fecha regreso
                                                        trabajar: </span>
                                                    {{ (!empty($tarea_liviana->fecha_regreso_trabajar)) ?
                                                    date('d/m/Y',strtotime($tarea_liviana->fecha_regreso_trabajar)) : ""
                                                    }}
                                                </li>
                                                </li>
                                                @if ($tarea_liviana->user != null)
                                                <li class="list-group-item">
                                                    <span style="font-weight: 600;" class="text_black">User que
                                                        registró:</span> {{$tarea_liviana->user}}
                                                </li>
                                                @endif
                                                <li class="list-group-item">
                                                    Archivo:
                                                    @if ($tarea_liviana->archivo == null)
                                                    <span style="font-weight: 600;" class="text_black">No se adjuntó
                                                        documentación</span>
                                                    @else
                                                    <a class="btn-ejornal btn-ejornal-gris-claro"
                                                        href="{{route('tareas_livianas.archivo', $tarea_liviana->id)}}">
                                                        <i class="fa fa-file"></i>{{$tarea_liviana->archivo}}
                                                    </a>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>

            </div>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>


@endsection