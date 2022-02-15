@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Consulta médica realizada</h2>
            <p>Aquí podrá ver una consulta médica que fue cargada con anterioridad</p>
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
            <div class="d-flex flex-wrap">
                <div class="col-md-6">
                        <div class="d-flex flex-column bd-highlight mb-3">
                            <div class="col-md-12 text-center">
                              <i class="fas fa-user fa-10x"></i>
                              <br>
                              <br>
                              <h5>{{$consulta_medica->nombre}}</h5>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <b>Email: </b> {{$consulta_medica->email}}
                                </li>
                                <li class="list-group-item">
                                    <b>Diagnostico: </b> {{$consulta_medica->diagnostico}}
                                </li>
                                <li class="list-group-item">
                                    <b>Amerita salida: </b>
                                    @if ($consulta_medica->amerita_salida == 1)
                                        Si
                                      @else
                                        No
                                    @endif
                                </li>
                                <li class="list-group-item">
                                    <b>Tel: </b> {{$consulta_medica->telefono}}
                                </li>
                                <li class="list-group-item">
                                    <b>Dni: </b> {{$consulta_medica->dni}}
                                </li>
                                <li class="list-group-item">
                                    <b>Estado: </b>
                                    @if ($consulta_medica->estado == 1)
                                        Activo
                                      @else
                                        Inacivo
                                    @endif
                                </li>
                            </ul>
                        </div>
                </div>
                <div class="col-md-6">
                        <div class="card mt-2 mb-3">
                            <div class="card-body">
                                <h5 class="card-title text_primary">
                                    <b>Fecha:</b> {{ (!empty($consulta_medica->fecha)) ? date('d/m/Y',strtotime($consulta_medica->fecha)) : "" }}
                                </h5>
                                <p class="card-text">
                                    <ul class="list-group list-group-flush">
                                      @if ($consulta_medica->user != null)
                                        <li class="list-group-item">
                                          <span style="font-weight: 600;" class="text_black">Cargado por: </span>
                                          {{$consulta_medica->user}}
                                        </li>
                                      @endif
                                        <li class="list-group-item">
                                          <span style="font-weight: 600;" class="text_black">Registro cargado: </span>
                                          {{ (!empty($consulta_medica->created_at)) ? date('d/m/Y H:i:s',strtotime($consulta_medica->created_at)) : "" }}
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
                                </p>
                            </div>
                        </div>
                </div>
            </div>
        </div>



        {{-- Contenido de la pagina --}}
    </div>
</div>

@endsection
