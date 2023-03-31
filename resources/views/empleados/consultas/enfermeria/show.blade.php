@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Consulta de enfermería realizada</h2>
            <p>Aquí podrá ver una consulta de enfermería que fue cargada con anterioridad</p>
            <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-gris-claro" href="{{url('empleados/consultas/enfermeria')}}"><i
                        class="fas fa-arrow-alt-circle-left"></i>Volver</a>
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
            @if ($consulta_enfermeria)
            <div class="d-flex flex-wrap">
                <div class="col-md-12">
                    <div class="d-flex bd-highlight mb-3">
                        <div class="col-lg-2 col-md-4 text-center">
                            <i class="fas fa-user fa-10x"></i>
                            <br>
                            <br>
                            <h5>{{$consulta_enfermeria->nombre}}</h5>
                        </div>
                        <ul class="col-lg-10 col-md-8 list-group list-group-flush">
                            <li class="list-group-item">
                                <b>Email: </b> {{$consulta_enfermeria->email}}
                            </li>
                            <li class="list-group-item">
                                <b>Diagnostico: </b> {{$consulta_enfermeria->diagnostico}}
                            </li>
                            <li class="list-group-item">
                                <b>Amerita salida: </b>
                                @if ($consulta_enfermeria->amerita_salida == 1)
                                Si
                                @else
                                No
                                @endif
                            </li>
                            <li class="list-group-item">
                                <b>Tel: </b> {{$consulta_enfermeria->telefono}}
                            </li>
                            <li class="list-group-item">
                                <b>Dni: </b> {{$consulta_enfermeria->dni}}
                            </li>
                            <li class="list-group-item">
                                <b>Estado: </b>
                                @if ($consulta_enfermeria->estado == 1)
                                Activo
                                @else
                                Inacivo
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mt-2 mb-3">
                        <div class="card-body">
                            <h5 class="card-title text_primary">
                                <b>Fecha:</b> {{ (!empty($consulta_enfermeria->fecha)) ?
                                date('d/m/Y',strtotime($consulta_enfermeria->fecha)) : "" }}
                            </h5>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <ul class="list-group list-group-flush">
                                        @if ($consulta_enfermeria->user != null)
                                        <li class="list-group-item">
                                            <span style="font-weight: 600;" class="text_black">Cargado por: </span>
                                            {{$consulta_enfermeria->user}}
                                        </li>
                                        @endif
                                        <li class="list-group-item">
                                            <span style="font-weight: 600;" class="text_black">Registro cargado: </span>
                                            {{ (!empty($consulta_enfermeria->created_at)) ? date('d/m/Y
                                            H:i:s',strtotime($consulta_enfermeria->created_at)) : "" }}
                                        </li>
                                        <li class="list-group-item">
                                            <span style="font-weight: 600;" class="text_black">Temp. auxiliar: </span>
                                            {{ (!empty($consulta_enfermeria->temperatura_auxiliar)) ?
                                            $consulta_enfermeria->temperatura_auxiliar : "" }}
                                        </li>
                                        <li class="list-group-item">
                                            <span style="font-weight: 600;" class="text_black">Peso: </span>
                                            {{ (!empty($consulta_enfermeria->peso)) ? $consulta_enfermeria->peso : "" }}
                                        </li>
                                        <li class="list-group-item">
                                            <span style="font-weight: 600;" class="text_black">Altura: </span>
                                            {{ (!empty($consulta_enfermeria->altura)) ? $consulta_enfermeria->altura :
                                            "" }}
                                        </li>
                                        <li class="list-group-item">
                                            <span style="font-weight: 600;" class="text_black">IMC: </span>
                                            {{ (!empty($consulta_enfermeria->imc)) ? $consulta_enfermeria->imc : "" }}
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <span style="font-weight: 600;" class="text_black">Glucemia: </span>
                                            {{ (!empty($consulta_enfermeria->glucemia)) ? $consulta_enfermeria->glucemia
                                            : "" }}
                                        </li>
                                        <li class="list-group-item">
                                            <span style="font-weight: 600;" class="text_black">Saturación de oxígeno:
                                            </span>
                                            {{ (!empty($consulta_enfermeria->saturacion_oxigeno)) ?
                                            $consulta_enfermeria->saturacion_oxigeno : "" }}
                                        </li>
                                        <li class="list-group-item">
                                            <span style="font-weight: 600;" class="text_black">Tensión arterial: </span>
                                            {{ (!empty($consulta_enfermeria->tension_arterial)) ?
                                            $consulta_enfermeria->tension_arterial : "" }}
                                        </li>
                                        <li class="list-group-item">
                                            <span style="font-weight: 600;" class="text_black">Frecuencia cardíaca:
                                            </span>
                                            {{ (!empty($consulta_enfermeria->frec_cardiaca)) ?
                                            $consulta_enfermeria->frec_cardiaca : "" }}
                                        </li>
                                        <li class="list-group-item">
                                            <span style="font-weight: 600;" class="text_black">Derivación: </span>
                                            {{ (!empty($consulta_enfermeria->derivacion_consulta)) ?
                                            $consulta_enfermeria->derivacion_consulta : "" }}
                                        </li>
                                        <li class="list-group-item">
                                            <span style="font-weight: 600;" class="text_black">Observaciones: </span>
                                            {{ (!empty($consulta_enfermeria->observaciones)) ?
                                            $consulta_enfermeria->observaciones : "" }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <h4>No se pudo encontrar la consulta</h4>
            <a href="{{route('empleados.consultas.enfermeria')}}"><i class="fa fa-angle-double-left fa-fw"></i> Volver
                al
                listado</a>
            @endif
        </div>



        {{-- Contenido de la pagina --}}
    </div>
</div>

@endsection