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
            <!-- Formulario de filtros -->
            <form method="GET" action="{{ route('empleados.consultas.nutricionales') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-2">
                        <label for="fecha_desde">Fecha Desde:</label>
                        <input type="text" id="fecha_desde" name="fecha_desde" class="form-control form-control-sm"
                            autocomplete="off" readonly value="{{ request('fecha_desde') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="fecha_hasta">Fecha Hasta:</label>
                        <input type="text" id="fecha_hasta" name="fecha_hasta" class="form-control form-control-sm"
                            autocomplete="off" readonly value="{{ request('fecha_hasta') }}">
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn-ejornal btn-ejornal-base">Filtrar</button>
                        <a href="{{ route('empleados.consultas.nutricionales') }}"
                            class="btn-ejornal btn-ejornal-gris-claro">Traer todo</a>
                    </div>
                </div>
            </form>
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
                        <td>{{ date('d/m/Y', strtotime($nutricional->fecha_atencion)) }}</td>
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
                {{ $paginatedNutricion->appends(request()->query())->links() }}
            </div>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>


<!-- Scripts -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<script>
    $(function () {
        $("#fecha_desde, #fecha_hasta").datepicker({
        dateFormat: "dd-mm-yy",
        dayNames: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
        dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        monthNamesShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"]
    });
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Bloquea teclas y pegado
    $("#fecha_desde, #fecha_hasta").on('keydown paste drop', function(e) {
        e.preventDefault();
    });
    
        $('#nutricionTable').DataTable({
            paging: false,
            info: false,
            searching: true,
        });
    });
</script>


@endsection