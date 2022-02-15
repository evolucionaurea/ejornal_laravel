@extends('partials.layout')

@section('title', 'Clientes')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_clientes')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')

        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Listado de trabajadores</h2>
            <p>Aquí puede ver el listado de trabajadores de su empresa</p>
        </div>

        @include('../mensajes_validacion')

        <div class="tarjeta">
            <table class="table table-striped table-hover table-sm tabla_user">

                <!--Table head-->
                <thead>
                    <tr>
                        <th class="th-lg">
                            <a>
                                Nombre
                                <i class="fas fa-sort ml-1"></i>
                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Email
                                <i class="fas fa-sort ml-1"></i>
                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Tel
                                <i class="fas fa-sort ml-1"></i>
                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                DNI
                                <i class="fas fa-sort ml-1"></i>
                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Estado
                                <i class="fas fa-sort ml-1"></i>
                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Sector
                                <i class="fas fa-sort ml-1"></i>
                            </a>
                        </th>
                    </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody>
                    @foreach ($nominas as $nomina)
                    <tr>
                        <td>{{$nomina->nombre}}</td>
                        <td>{{$nomina->email}}</td>
                        <td>{{$nomina->telefono}}</td>
                        <td>{{$nomina->dni}}</td>
                        <td>
                          @if ($nomina->estado == 1)
                            Activo
                          @else
                            Inactivo
                          @endif
                        </td>
                        <td>{{$nomina->sector}}</td>
                    </tr>
                    @endforeach
                </tbody>
                <!--Table body-->
            </table>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>


@endsection
