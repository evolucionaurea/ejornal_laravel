@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_admin')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Ediciones de fichadas</h2>
            <p>Aquí puede ver el listado de las ediciones de fichadas</p>
        </div>

        @include('../mensajes_validacion')

        <div class="tarjeta">
            <table class="table table-striped table-hover table-sm tabla">

                <!--Table head-->
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>ID fichada</th>
                        <th>Old ingreso</th>
                        <th>Old egreso</th>
                        <th>New ingreso</th>
                        <th>New egreso</th>
                        <th>IP</th>
                        <th>Dispositivo</th>
                        <th>Fecha de modificacion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ediciones as $edicion)
                    <tr>
                        <td>{{$edicion->id}}</td>
                        <td>{{$edicion->user->nombre}}</td>
                        <td>{{$edicion->id_fichada}}</td>
                        <td>
                            @if ($edicion->old_ingreso != null)
                            {{ $edicion->old_ingreso_formatted }}
                            @else
                            {{ 'N/A' }}
                            @endif
                        </td>
                        <td>
                            @if ($edicion->old_egreso != null)
                            {{ $edicion->old_egreso_formatted }}
                            @else
                            {{ 'N/A' }}
                            @endif
                        </td>
                        <td>
                            @if ($edicion->new_ingreso != null)
                            {{ $edicion->new_ingreso_formatted }}
                            @else
                            {{ 'N/A' }}
                            @endif
                        </td>
                        <td>
                            @if ($edicion->new_egreso != null)
                            {{ $edicion->new_egreso_formatted }}
                            @else
                            {{ 'N/A' }}
                            @endif
                        </td>
                        <td>{{$edicion->ip}}</td>
                        <td>{{$edicion->dispositivo}}</td>
                        <td>{{$edicion->created_at}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>

@endsection