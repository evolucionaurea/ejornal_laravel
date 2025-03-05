@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')

        <div class="cabecera">
            <h2>Caratulas de {{ $caratulas[0]->nomina->nombre }}</h2>
            <p>Listado de todas las caratulas creadas para este trabajador de la nomina en
                <b>{{ $caratulas[0]->nomina->cliente->nombre }}</b>
            </p>
            <div class="row">
                <div class="col-md-3">
                    <a class="btn-ejornal btn-ejornal-gris-claro" href="{{ url('empleados/caratulas') }}">
                        <i class="fas fa-arrow-circle-left"></i> <span>Volver</span>
                    </a>
                </div>
            </div>
        </div>

        @include('mensajes_validacion')

        <div class="tarjeta">
            <table class="table table-striped table-sm tabla" id="caratulasTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patología</th>
                        <th>Medicacion habitual</th>
                        <th>Antecedentes</th>
                        <th>User que lo carga</th>
                        <th>Alergias</th>
                        <th>Peso</th>
                        <th>Altura</th>
                        <th>IMC</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($caratulas as $caratula)
                    <tr>
                        <td>{{ $caratula->id }}</td>
                        <td>
                            @if ($caratula->patologias->count() > 0)
                            <ul>
                                @foreach ($caratula->patologias as $patologia)
                                <li>{{ $patologia->nombre }}</li>
                                @endforeach
                            </ul>
                            @else
                            <span>Sin cargar</span>
                            @endif
                        </td>
                        <td>{{ $caratula->medicacion_habitual }}</td>
                        <td>{{ $caratula->antecedentes }}</td>
                        <td>
                            @if ($caratula->user != null)
                            {{ $caratula->user }}
                            @else
                            {{ 'No guardado' }}
                            @endif
                        </td>
                        <td>{{ $caratula->alergias }}</td>
                        <td>{{ $caratula->peso }}</td>
                        <td>{{ $caratula->altura }}</td>
                        <td>{{ $caratula->imc }}</td>
                        <td>{{ date('d/m/Y H:i:s', strtotime($caratula->created_at)) }}</td>
                        <td class="acciones_tabla" scope="row">
                            <form action="{{ route('empleados.nominas.caratulas.destroy', $caratula->id) }}"
                                method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE">
                                <button title="Delete" type="submit">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = $('#caratulasTable').DataTable({
            info: false,
            searching: true,  // Habilita el buscador
            order: [[8, 'desc']]  // Ordena por la columna 'Fecha' (índice 4) de manera descendente
        });
    });
</script>


@endsection