@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<style>
    .table td {
        word-break: break-word;
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .table tbody tr:hover td {
        white-space: normal;
    }
</style>

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_admin')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')

        <div class="cabecera">
            <h2>Errores</h2>
            <p>Listado de errores</p>
            <form action="{{ route('admin.limpiar_errores') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-ejornal btn-ejornal-gris-claro">
                    <i class="fas fa-broom"></i>
                    Dejar últimos 50 errores
                </button>
            </form>

        </div>

        @include('../mensajes_validacion')

        <div class="tarjeta">

            {{-- Formulario de búsqueda --}}
            <form id="search-form" method="GET" action="{{ route('/admin/errores') }}" class="mb-4">
                <div class="form-row">
                    <div class="col">
                        <input type="text" name="query" class="form-control" placeholder="Buscar error..."
                            value="{{ request('query') }}">
                    </div>
                    <div class="col">
                        <input id="errores_desde" type="text" name="fecha_desde" class="form-control"
                            placeholder="Fecha desde" value="{{ request('fecha_desde') }}">
                    </div>
                    <div class="col">
                        <input id="errores_hasta" type="text" name="fecha_hasta" class="form-control"
                            placeholder="Fecha hasta" value="{{ request('fecha_hasta') }}">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn-ejornal btn-ejornal-base">Buscar</button>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Mensaje</th>
                        <th>Archivo</th>
                        <th>Línea</th>
                        <th>User</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($errores as $error)
                    <tr>
                        <td>{{ $error->id }}</td>
                        <td>{{ $error->type }}</td>
                        <td class="text-truncate" style="max-width: 300px;" title="{{ $error->message }}">{{
                            $error->message }}</td>
                        <td>{{ $error->file }}</td>
                        <td>{{ $error->line }}</td>
                        <td>{{ $error->user_id ? 'ID: ' . $error->user_id : 'No registrado' }}</td>
                        <td>{{ \Carbon\Carbon::parse($error->created_at)->translatedFormat('d \d\e F \d\e Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No hay errores registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center">
                {{ $errores->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Script para DatePicker --}}
@push('scripts')
<script>
    $(document).ready(function () {
        $.datepicker.setDefaults($.datepicker.regional["es"]);
        $("#errores_desde, #errores_hasta").datepicker({
            dateFormat: "dd/mm/yy", // Formato de fecha en español
            changeMonth: true,
            changeYear: true
        });

        // Prevenir el submit y procesar las fechas en formato inglés
        $('#search-form').on('submit', function (e) {
            e.preventDefault();  // Prevenir el submit del formulario

            // Obtener las fechas en español
            let fechaDesde = $('#errores_desde').val();
            let fechaHasta = $('#errores_hasta').val();

            // Validar las fechas
            if (!fechaDesde || !fechaHasta) {
                alert("Por favor, ingrese ambas fechas.");
                return false;
            }

            // Convertir las fechas al formato adecuado (YYYY-MM-DD)
            let fechaDesdeObj = $.datepicker.parseDate('dd/mm/yy', fechaDesde);
            let fechaHastaObj = $.datepicker.parseDate('dd/mm/yy', fechaHasta);

            if (!fechaDesdeObj || !fechaHastaObj) {
                alert("Por favor, ingrese fechas válidas.");
                return false;
            }

            // Convertir las fechas al formato adecuado para el backend (YYYY-MM-DD)
            $('#errores_desde').val($.datepicker.formatDate('yy-mm-dd', fechaDesdeObj));
            $('#errores_hasta').val($.datepicker.formatDate('yy-mm-dd', fechaHastaObj));

            // Si todo está bien, hacer el submit
            this.submit();
        });
    });
</script>
@endpush

@endsection