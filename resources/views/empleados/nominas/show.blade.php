@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Historial de un trabajador</h2>
            <p>Aquí podrá ver las consultas y ausentismos del trabajador</p>
            <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-gris-claro" href="{{ url('empleados/nominas') }}"><i class="fas fa-arrow-circle-left"></i>Volver</a>
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
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 col-sm-12 text-center">
                          @if (isset($trabajador->foto) && !empty($trabajador->foto))
                            <img style="width: 300px;" src="{{asset('storage/nominas/fotos/'.$trabajador->id.'/'.$trabajador->hash_foto)}}">
                          @else
                            <i class="fas fa-user fa-10x"></i>
                          @endif
                            <br>
                            <br>
                            <h5>{{$trabajador->nombre}}</h5>
                        </div>
                        <div class="col-lg-4 col-md-5 col-sm-12">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <b>Email: </b>
                                    @if ($trabajador->email !== null && $trabajador->email !== '')
                                      <a href="mailto:{{$trabajador->email}}?subject=contacto ejornal">{{$trabajador->email}}</a>
                                      @else
                                        {{'No fue cargado'}}
                                    @endif
                                </li>
                                <li class="list-group-item">
                                    <b>Teléfono: </b>
                                    @if ($trabajador->telefono !== null && $trabajador->telefono !== '')
                                      <a href="tel:{{$trabajador->telefono}}">{{$trabajador->telefono}}</a>
                                      @else
                                        {{'No fue cargado'}}
                                    @endif
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <b>DNI: </b> {{$trabajador->dni}}
                                </li>
                                <li class="list-group-item">
                                    <b>Activo: </b>
                                    @if ($trabajador->estado == 1)
                                    Si
                                    @else
                                    No
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card mdb-color">
                            <div class="card-body">
                                <p class="d-flex align-items-center">
                                  <i class="fas fa-notes-medical fa-3x text-info"></i>
                                  <span style="font-size: 20px; color: white;" class="ml-4 font-weight-bold cyan-lighter-hover">Testeos</span>
                                </p>
                                <p class="text-muted font-weight-light mb-0">
                                <ul class="testeos_vacunas_historial_trabajador">
                                  @if (isset($testeos) && !empty($testeos))
                                    @foreach ($testeos as $testeo)
                                      <li>{{$testeo->nombre}}</li>
                                      <li>{{$testeo->resultado}}</li>
                                      <li>{{$testeo->laboratorio}}</li>
                                      <li>{{ (!empty($testeo->fecha)) ? date('d/m/Y',strtotime($testeo->fecha)) : "" }}</li>
                                      <hr style="background: grey;">
                                    @endforeach
                                    @else
                                      <li>No hay testeos realizados</li>
                                  @endif
                                </ul>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="card mdb-color">
                            <div class="card-body">
                                <p class="d-flex align-items-center">
                                  <i class="fas fa-vial fa-3x text-info"></i>
                                  <span style="font-size: 20px; color: white;" class="ml-4 font-weight-bold cyan-lighter-hover">Vacunas</span>
                                </p>
                                <p class="text-muted font-weight-light mb-0">
                                <ul class="testeos_vacunas_historial_trabajador">
                                  @if (isset($vacunas) && !empty($vacunas))
                                    @foreach ($vacunas as $vacuna)
                                      <li>{{$vacuna->nombre}}</li>
                                      <li>{{$vacuna->institucion}}</li>
                                      <li>{{ (!empty($vacuna->fecha)) ? date('d/m/Y',strtotime($vacuna->fecha)) : "" }}</li>
                                      <hr style="background: grey;">
                                    @endforeach
                                    @else
                                      <li>No hay vacunas aplicadas</li>
                                  @endif
                                </ul>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-12 d-flex flex-wrap justify-content-between">
                    <div class="card_consultas flex-fill m-4">
                        <h4>Consultas medicas</h4>
                        @if (count($consultas_medicas) > 0)
                        @foreach ($consultas_medicas as $consulta_medica)
                        <div class="card_consultas_titulo">
                            <h6>
                                Creado el:
                                {{ (!empty($consulta_medica->created_at)) ? date('d/m/Y',strtotime($consulta_medica->created_at)) : "" }}
                            </h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Fecha: </span>
                                {{ (!empty($consulta_medica->fecha)) ? date('d/m/Y',strtotime($consulta_medica->fecha)) : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Peso: </span>
                                {{ (!empty($consulta_medica->peso)) ? $consulta_medica->peso : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Altura: </span>
                                {{ (!empty($consulta_medica->altura)) ? $consulta_medica->altura : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">IMC: </span>
                                {{ (!empty($consulta_medica->imc)) ? $consulta_medica->imc : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Glucemia: </span>
                                {{ (!empty($consulta_medica->glucemia)) ? $consulta_medica->glucemia : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Saturación de oxígeno: </span>
                                {{ (!empty($consulta_medica->saturacion_oxigeno)) ? $consulta_medica->saturacion_oxigeno : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Tensión arterial: </span>
                                {{ (!empty($consulta_medica->tension_arterial)) ? $consulta_medica->tension_arterial : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Frecuencia cardíaca: </span>
                                {{ (!empty($consulta_medica->frec_cardiaca)) ? $consulta_medica->frec_cardiaca : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Derivación: </span>
                                {{ (!empty($consulta_medica->derivacion_consulta)) ? $consulta_medica->derivacion_consulta : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Anamnesis: </span>
                                {{ (!empty($consulta_medica->anamnesis)) ? $consulta_medica->anamnesis : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Tratamiento: </span>
                                {{ (!empty($consulta_medica->tratamiento)) ? $consulta_medica->tratamiento : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Observaciones: </span>
                                {{ (!empty($consulta_medica->observaciones)) ? $consulta_medica->observaciones : "" }}
                            </li>
                        </ul>
                        <br>
                        @endforeach
                        @else
                        <div class="alert alert-light" role="alert">
                            No hay ninguno
                        </div>
                        @endif
                    </div>
                    <div class="card_consultas flex-fill m-4">
                        <h4>Consultas enfermería</h4>
                        @if (count($consultas_enfermeria) > 0)
                        @foreach ($consultas_enfermeria as $consulta_enfermeria)
                        <div class="card_consultas_titulo">
                            <h6>
                                Creado el:
                                {{ (!empty($consulta_enfermeria->created_at)) ? date('d/m/Y',strtotime($consulta_enfermeria->created_at)) : "" }}
                            </h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Fecha: </span>
                                {{ (!empty($consulta_enfermeria->fecha)) ? date('d/m/Y',strtotime($consulta_enfermeria->fecha)) : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Peso: </span>
                                {{ (!empty($consulta_enfermeria->peso)) ? $consulta_enfermeria->peso : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Altura: </span>
                                {{ (!empty($consulta_enfermeria->altura)) ? $consulta_enfermeria->altura : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">IMC: </span>
                                {{ (!empty($consulta_enfermeria->imc)) ? $consulta_enfermeria->imc : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Glucemia: </span>
                                {{ (!empty($consulta_enfermeria->glucemia)) ? $consulta_enfermeria->glucemia : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Saturación de oxígeno: </span>
                                {{ (!empty($consulta_enfermeria->saturacion_oxigeno)) ? $consulta_enfermeria->saturacion_oxigeno : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Tensión arterial: </span>
                                {{ (!empty($consulta_enfermeria->tension_arterial)) ? $consulta_enfermeria->tension_arterial : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Frecuencia cardíaca: </span>
                                {{ (!empty($consulta_enfermeria->frec_cardiaca)) ? $consulta_enfermeria->frec_cardiaca : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Derivación: </span>
                                {{ (!empty($consulta_enfermeria->derivacion_consulta)) ? $consulta_enfermeria->derivacion_consulta : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Observaciones: </span>
                                {{ (!empty($consulta_enfermeria->observaciones)) ? $consulta_enfermeria->observaciones : "" }}
                            </li>
                        </ul>
                        <br>
                        @endforeach
                        @else
                        <div class="alert alert-light" role="alert">
                            No hay ninguno
                        </div>
                        @endif
                    </div>
                </div>


                <div class="col-md-12 d-flex flex-wrap justify-content-between">
                    <div class="card_consultas flex-fill m-4">
                        <h4>Examenes Medicos Complementarios</h4>
                        @if (count($preocupacionales) > 0)
                        @foreach ($preocupacionales as $preocupacional)
                        <div class="card_consultas_titulo">
                            <h6>
                                Creado el:
                                {{ (!empty($preocupacional->created_at)) ? date('d/m/Y',strtotime($preocupacional->created_at)) : "" }}
                            </h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Trabajador: </span>
                                {{ (!empty($preocupacional->nombre)) ? $preocupacional->nombre : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Fecha inicio: </span>
                                {{ (!empty($preocupacional->fecha)) ? date('d/m/Y',strtotime($preocupacional->fecha)) : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Fecha final: </span>
                                {{ (!empty($preocupacional->observaciones)) ? date('d/m/Y',strtotime($preocupacional->observaciones)) : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Archivo: </span>
                                <br>
                                <br>
                                @if ($preocupacional->archivo == null)
                                <span style="color: grey;">No se adjuntó documentación</span>
                                @else
                                <a class="btn-ejornal btn-ejornal-gris-claro" href="{{route('preocupacionales.archivo', $preocupacional->id)}}">
                                  <i class="fa fa-file"></i>{{$preocupacional->archivo}}
                                </a>
                                @endif
                            </li>
                        </ul>
                        <br>
                        @endforeach
                        @else
                        <div class="alert alert-light" role="alert">
                            No hay ninguno
                        </div>
                        @endif
                    </div>
                    <div class="card_consultas flex-fill m-4">
                        <h4>Ausentismos</h4>
                        @if (count($ausentismos) > 0)
                        @foreach ($ausentismos as $ausentismo)
                        <div class="card_consultas_titulo">
                            <h6>
                                Creado el:
                                {{ (!empty($ausentismo->created_at)) ? date('d/m/Y',strtotime($ausentismo->created_at)) : "" }}
                            </h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Tipo: </span>
                                {{ (!empty($ausentismo->tipo)) ? $ausentismo->tipo : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Fecha inicio: </span>
                                {{ (!empty($ausentismo->fecha_inicio)) ? date('d/m/Y',strtotime($ausentismo->fecha_inicio)) : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Fecha final: </span>
                                {{ (!empty($ausentismo->fecha_final)) ? date('d/m/Y',strtotime($ausentismo->fecha_final)) : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Fecha regreso trabajar: </span>
                                {{ (!empty($ausentismo->fecha_regreso_trabajar)) ? date('d/m/Y',strtotime($ausentismo->fecha_regreso_trabajar)) : "" }}
                            </li>
                            <li class="list-group-item">
                                <span style="font-weight: 600;" class="text_black">Archivo: </span>
                                <br>
                                <br>
                                @if ($ausentismo->archivo == null)
                                <span style="color: grey;">No se adjuntó documentación</span>
                                @else
                                <a class="btn-ejornal btn-ejornal-gris-claro" href="{{route('ausentismos.archivo', $ausentismo->id)}}">
                                    <i class="fa fa-file"></i>{{$ausentismo->archivo}}
                                </a>
                                @endif
                            </li>
                        </ul>
                        <br>
                        @endforeach
                        @else
                        <div class="alert alert-light" role="alert">
                            No hay ninguno
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>



        {{-- Contenido de la pagina --}}
    </div>
</div>

@endsection
