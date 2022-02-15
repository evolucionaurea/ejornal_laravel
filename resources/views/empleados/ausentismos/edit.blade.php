@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Edición de un ausentismo</h2>
            <p>Aquí podrá editar la información referente al ausentismo de un trabajador</p>
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
            <form action="{{action('EmpleadosAusentismosController@update', $ausentismo->id)}}" accept-charset="UTF-8" method="post">
                {{ csrf_field() }}
                <input name="_method" type="hidden" value="PUT">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Trabajador</label>
                        <input disabled name="nombre" type="text" class="form-control form-control-sm" value="{{$ausentismo->nombre}}" placeholder="">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Tipo</label>
                        <input disabled name="tipo" type="text" class="form-control form-control-sm" value="{{$ausentismo->nombre_ausentismo}}" placeholder="">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Fecha inicio</label>
                        <input id="ausentismo_fecha_inicio" name="fecha_inicio" type="datetime" class="form-control"
                        value="{{ (!empty($ausentismo->fecha_inicio)) ? date('d/m/Y',strtotime($ausentismo->fecha_inicio)) : "" }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Fecha final</label>
                        <input id="ausentismo_fecha_final" name="fecha_final" type="datetime" class="form-control"
                        value="{{ (!empty($ausentismo->fecha_final)) ? date('d/m/Y',strtotime($ausentismo->fecha_final)) : "" }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Fecha en que regresó</label>
                        <input id="ausentismo_fecha_regreso" name="fecha_regreso_trabajar" type="datetime" class="form-control"
                        value="{{ (!empty($ausentismo->fecha_regreso_trabajar)) ? date('d/m/Y',strtotime($ausentismo->fecha_regreso_trabajar)) : "" }}">
                    </div>
                    <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Guardar cambios</button>
                </div>
            </form>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>

@endsection
