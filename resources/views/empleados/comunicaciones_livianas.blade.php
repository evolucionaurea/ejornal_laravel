@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')

        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Listado de comunicaciones adecuadas</h2>
            <p>Aquí puede ver el listado de comunicaciones adecuadas de la empresa</p>
            {{-- @if (auth()->user()->fichada == 1)
            <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-base" href="{{route('comunicaciones.create')}}"><i
                        class="fas fa-plus-circle"></i> Nueva comunicación</a>
            </div>
            @endif --}}
        </div>

        @include('../mensajes_validacion')

        <div class="tarjeta">

            @include('../../modulos.busqueda_fecha')

            <table class="table table-striped table-hover table-sm tabla_comunicaciones_livianas_listado">

                <!--Table head-->
                <thead>
                    <tr>
                        <th class="th-lg">Trabajador</th>
                        <th class="th-lg">Email</th>
                        <th class="th-lg">Tipo</th>
                        <th class="th-lg">Cargado</th>
                        <th class="th-lg">Estado</th>
                    </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody></tbody>
                <!--Table body-->
            </table>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>




@endsection