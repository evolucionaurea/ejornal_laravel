@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')

        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Certificados de una tarea adecuada</h2>
            <p>Aquí puede ver el listado de las documentaciones que carga en cada tarea adecuada</p>
        </div>

        @include('../mensajes_validacion')

        <div class="tarjeta">

            @include('../../modulos.busqueda_fecha')

            <table class="table table-striped table-hover table-sm tabla_certificados_livianos_listado">

                <!--Table head-->
                <thead>
                    <tr>
                        <th class="th-lg">Trabajador</th>
                        <th class="th-lg">Médico</th>
                        <th class="th-lg">Institución</th>
                        <th class="th-lg">Fecha inicio</th>
                        <th class="th-lg">Fecha final</th>
                        <th class="th-lg">Fecha en que regresó</th>
                        <th class="th-lg">Matricula nacional</th>
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