@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_admin')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Listado de Recetas</h2>
            <p>Aquí puede ver el listado de las recetas que hicieron los empleados</p>
        </div>

        @include('../mensajes_validacion')

        <div class="tarjeta">
            {{-- Recetas --}}
            <p>
                Recetas mostradas con alguna estetica de documento corto.
                Filtros por fecha, empleado y tipo de receta
            </p>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>

@endsection