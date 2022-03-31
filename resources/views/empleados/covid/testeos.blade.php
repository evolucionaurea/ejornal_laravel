@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')

        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Listado de testeos</h2>
            <p>Aquí puede ver el listado de testeos de la empresa</p>
            @if (auth()->user()->fichada == 1)
            <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-base" href="{{route('testeos.create')}}"><i class="fas fa-plus-circle"></i> Nuevo testeo</a>
            </div>
            @endif
        </div>

        @include('../../mensajes_validacion')

        <div class="tarjeta">
            <table class="table table-striped table-hover table-sm tabla_testeos_listado">

                <!--Table head-->
                <thead>
                    <tr>
                        <th class="th-lg">
                                Trabajador
                        </th>
                        <th class="th-lg">
                                Tipo
                        </th>
                        <th class="th-lg">
                                Fecha
                        </th>
                        <th class="th-lg">
                                Resultado
                        </th>
                        <th class="th-lg">
                                Laboratorio
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
                    @foreach ($testeos as $testeo)
                    <tr>
                        <td>{{$testeo->nombre}}</td>
                        <td>{{$testeo->tipo}}</td>
                        {{-- <td>{{ (!empty($testeo->fecha)) ? date('d/m/Y',strtotime($testeo->fecha)) : "" }}</td> --}}
                        <td>{{ (!empty($testeo->fecha)) ? $testeo->fecha : "" }}</td>
                        <td>{{$testeo->resultado}}</td>
                        <td>{{$testeo->laboratorio}}</td>
                        @if (auth()->user()->fichada == 1)
                        <td class="acciones_tabla" scope="row">
                            <a title="Editar" href="{{route('testeos.edit', $testeo->id)}}">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form class="" action="{{route('testeos.destroy', $testeo->id)}}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE">
                                <button title="Eliminar" type="submit">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
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
