@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Edición de un tareas livianas</h2>
            <p>Aquí podrá editar la información referente al tareas livianas de un trabajador</p>
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
            <form action="{{action('EmpleadoTareasLivianasController@update', $tarea_liviana->id)}}"
                accept-charset="UTF-8" method="post">
                {{ csrf_field() }}
                <input name="_method" type="hidden" value="PUT">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Trabajador</label>
                        <input disabled name="nombre" type="text" class="form-control form-control-sm"
                            value="{{$tarea_liviana->nombre}}" placeholder="">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Tipo</label>
                        <input disabled name="tipo" type="text" class="form-control form-control-sm"
                            value="{{$tarea_liviana->nombre_tarea_liviana}}" placeholder="">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Fecha inicio</label>
                        <input id="tarea_liviana_fecha_inicio" name="fecha_inicio" type="datetime" class="form-control"
                            value="{{ (!empty($tarea_liviana->fecha_inicio)) ? date('d/m/Y',strtotime($tarea_liviana->fecha_inicio)) : "" }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Fecha final</label>
                        <input id="tarea_liviana_fecha_final" name="fecha_final" type="datetime" class="form-control"
                            value="{{ (!empty($tarea_liviana->fecha_final)) ? date('d/m/Y',strtotime($tarea_liviana->fecha_final)) : "" }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Fecha en que regresó</label>
                        <input id="tarea_liviana_fecha_regreso" name="fecha_regreso_trabajar" type="datetime"
                            class="form-control"
                            value="{{ (!empty($tarea_liviana->fecha_regreso_trabajar)) ? date('d/m/Y',strtotime($tarea_liviana->fecha_regreso_trabajar)) : "" }}">
                    </div>
                    <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Guardar cambios</button>
                </div>
            </form>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>

@endsection