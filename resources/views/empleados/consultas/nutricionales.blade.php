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
                <a class="btn-ejornal btn-ejornal-base" href="{{route('empleados.consultas.nutricionales.create')}}">
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
            <table class="table table-striped table-sm tabla" id="nutricionTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Trabajador</th>
                        <th>Cliente</th>
                        <th>Tipo</th>
                        <th>Fecha atencion</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paginatedNutricion as $nutricional)
                    <tr>
                        <td>{{ $nutricional->id }}</td>
                        <td>{{ $nutricional->nomina->nombre }}</td>
                        <td>{{ $nutricional->cliente->nombre }}</td>
                        <td>{{ $nutricional->tipo }}</td>
                        <td>{{ $nutricional->fecha_atencion }}</td>
                        <td class="acciones_tabla" scope="row">
                            <a title="Ver"
                                href="{{ route('empleados.consultas.nutricionales.show', $nutricional->id) }}">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>

            {{-- Paginación --}}
            <div class="d-flex justify-content-center">
                {{ $paginatedNutricion->links() }}
            </div>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>


<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = $('#nutricionTable').DataTable({
            paging: false,  // Pagina el contenido usando la paginación de Laravel
            info: false,
            searching: true,  // Habilita el buscador
        });
    });
</script>


@endsection