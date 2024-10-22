@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')

        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Nutricionales</h2>
            <p>Aquí puede ver el listado de consultas nutricionales de la empresa.</p>
            @if (auth()->user()->fichada == 1 || !auth()->user()->fichar)
            <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-base" href="{{route('nutricionales.create')}}">
                    <i class="fas fa-plus-circle"></i>
                    Nuevo
                </a>
            </div>
            @else
            <div class="small text-muted"><i>[Debe fichar para utilizar esta seccion]</i></div>
            @endif
        </div>

        @include('../mensajes_validacion')

        <div class="tarjeta">
            <h1>Nutricionales</h1>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>





@endsection