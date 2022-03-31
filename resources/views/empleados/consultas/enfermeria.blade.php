@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')

        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Listado de consultas de enfermería</h2>
            <p>Aquí puede ver el listado de consultas de enfermería de la empresa</p>
            @if (auth()->user()->fichada == 1 && auth()->user()->id_especialidad == 2)
            <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-base" href="{{route('enfermeria.create')}}"><i class="fas fa-plus-circle"></i> Nueva consulta de enfermería</a>
            </div>
            @endif
        </div>

        @include('../../mensajes_validacion')

        <div class="tarjeta">
            <table class="table table-striped table-hover table-sm tabla_consltas_enfermeria">

                <!--Table head-->
                <thead>
                    <tr>
                        <th class="th-lg">
                                Trabajador
                        </th>
                        <th class="th-lg">
                                Fecha
                        </th>
                        <th class="th-lg">
                                Derivación
                        </th>
                        @if (auth()->user()->fichada == 1)
                        <th class="th-lg">
                                Acciones
                        </th>
                        @endif
                    </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody>
                    @foreach ($consultas as $consulta)
                    <tr>
                        <td>{{$consulta->nombre}}</td>
                        {{-- <td>{{ (!empty($consulta->fecha)) ? date('d/m/Y',strtotime($consulta->fecha)) : "" }}</td> --}}
                        <td>{{ (!empty($consulta->fecha)) ? $consulta->fecha : "" }}</td>
                        <td>{{$consulta->derivacion_consulta}}</td>
                        @if (auth()->user()->fichada == 1)
                        <td class="acciones_tabla" scope="row">
                            <a title="Ver" href="{{route('enfermeria.show', $consulta->id)}}">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                        @endif
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
