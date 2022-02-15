@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Historial de ausencias</h2>
            <p>Aquí puedes ver el historial de ausencias del trabajador seleccionado</p>
            <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-gris-claro" href="{{ url('empleados/ausentismos') }}"><i class="fas fa-arrow-circle-left"></i>Volver</a>
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
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-lg-3 col-md-5 col-sm-12">
                            <i class="fas fa-user fa-10x"></i>
                        </div>
                        <div class="col-lg-9 col-md-7 col-sm-12">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <b>Trabajador/a: </b> {{$ausencias[0]->nombre}}
                                </li>
                                <li class="list-group-item">
                                    <b>Email: </b> {{$ausencias[0]->email}}</li>
                                <li class="list-group-item"><b>Estado: </b>
                                    @if ($ausencias[0]->estado == 1)
                                    Activo
                                    @else
                                    Inactivo
                                    @endif
                                </li>
                                <li class="list-group-item">
                                    <b>Telefono: </b> {{$ausencias[0]->telefono}}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                        <ul class="list-unstyled fa-ul mb-0">
                          @foreach ($ausencias as $ausencia)
                            <li class="d-flex pl-4">
                                <span class="fa-li"><i class="fas fa-book fa-2x cyan-text"></i></span>
                                <div>
                                    <h5 class="font-weight-bold mb-3">Ausencia por: {{$ausencia->nombre_ausentismo}}</h5>
                                    <p class="text-muted">
                                      <ul class="list-group list-group-flush">
                                          <li class="list-group-item">
                                              Fecha inicio: {{ (!empty($ausencia->fecha_inicio)) ? date('d/m/Y',strtotime($ausencia->fecha_inicio)) : "" }}
                                          </li>
                                          <li class="list-group-item">
                                              Fecha final: {{ (!empty($ausencia->fecha_final)) ? date('d/m/Y',strtotime($ausencia->fecha_final)) : "" }}
                                          </li>
                                          <li class="list-group-item">
                                              Fecha en que regresó: {{ (!empty($ausencia->fecha_regreso_trabajar)) ? date('d/m/Y',strtotime($ausencia->fecha_regreso_trabajar)) : "" }}
                                          </li>
                                          @if ($ausencia->user != null)
                                            <li class="list-group-item">
                                              User que registró: {{$ausencia->user}}
                                            </li>
                                          @endif
                                          <li class="list-group-item">
                                              Archivo:
                                              @if ($ausencia->archivo == null)
                                                <span style="color: grey;">No se adjuntó documentación</span>
                                                @else
                                                  <a class="btn-ejornal btn-ejornal-gris-claro" href="{{route('ausentismos.archivo', $ausencia->id)}}">
                                                    <i class="fa fa-file"></i>{{$ausencia->archivo}}
                                                  </a>
                                              @endif
                                          </li>
                                      </ul>
                                    </p>
                                </div>
                            </li>
                          @endforeach
                        </ul>


                </div>
            </div>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>


@endsection
