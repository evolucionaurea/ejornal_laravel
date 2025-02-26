@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')

        <div class="cabecera">
            <h2>Listado de carátulas</h2>
            <p>Aquí puede ver el listado de las últimas carátulas de los trabajadores de la empresa.</p>
        </div>

        @include('mensajes_validacion')

        <div class="tarjeta">
            <table class="table table-striped table-sm tabla" id="caratulasTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Trabajador</th>
                        <th>Cliente</th>
                        <th>Patología</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paginatedCaratulas as $caratula)
                    <tr>
                        <td class="align-middle">{{ $caratula->id }}</td>
                        <td class="align-middle">{{ $caratula->nomina->nombre }}</td>
                        <td class="align-middle">{{ $caratula->cliente->nombre }}</td>
                        <td class="align-middle">
                            @if ($caratula->patologias->count() > 0)
                            <ul class="list-group list-group-flush">
                                @foreach ($caratula->patologias as $patologia)
                                <li class="list-group-item p-1">{{ $patologia->nombre }}</li>
                                @endforeach
                            </ul>
                            @else
                            <span>Sin cargar</span>
                            @endif
                        </td>
                        <td class="align-middle" scope="row">
                            <div class="acciones_tabla">
                                <a title="Create" href="{{ route('empleados.nominas.caratulas.create', $caratula->nomina->id) }}">
                                    <i class="fas fa-plus"></i>
                                </a>
                                <a title="Ver" href="{{ route('empleados.nominas.caratulas.show', $caratula->nomina->id) }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>

            {{-- Paginación --}}
            <div class="d-flex justify-content-center">
                {{ $paginatedCaratulas->links() }}
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = $('#caratulasTable').DataTable({
            paging: false,  // Pagina el contenido usando la paginación de Laravel
            info: false,
            searching: true,  // Habilita el buscador
        });
    });
</script>

@endsection