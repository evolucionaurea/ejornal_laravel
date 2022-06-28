@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Creación de trabajadores</h2>
            <p>Aquí puedes cargar a los trabajadores que formarán parte de la nómina de la empresa</p>
            <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-gris-claro" href="{{ url('empleados/nominas') }}"><i class="fas fa-arrow-circle-left"></i>Volver</a>
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
            <form action="{{action('EmpleadosNominasController@store')}}" accept-charset="UTF-8" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Nombre</label>
                    <input required name="nombre" type="text" class="form-control form-control-sm" placeholder="">
                </div>
                <div class="form-group col-md-4">
                    <label>Email</label>
                    <input required name="email" type="email" class="form-control form-control-sm" placeholder="">
                </div>
                <div class="form-group col-md-4">
                    <label>Teléfono</label>
                    <input name="telefono" type="text" class="form-control form-control-sm" placeholder="">
                </div>
                <div class="form-group col-md-4">
                    <label>DNI</label>
                    <input name="dni" type="text" class="form-control form-control-sm" placeholder="">
                </div>
                <div class="form-group col-md-4">
                    <label >Estado</label>
                    <select name="estado" class="form-control form-control-sm">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>Sector</label>
                    <input required name="sector" type="text" class="form-control form-control-sm" placeholder="">
                </div>
                <div class="form-group col-md-4">
                    <label>Foto</label>
                    <input name="foto" type="file" class="form-control form-control-sm" placeholder="">
                </div>
            </div>
            <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Crear trabajador</button>
            </form>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>

@endsection
