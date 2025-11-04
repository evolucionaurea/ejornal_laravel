@extends('partials.layout')

@section('title', 'Empleado - Recetas')

@section('content')
<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')

        <div class="cabecera">
            <h2 class="h4 font-weight-bold mb-1">Recetas Generadas</h2>
            <p class="text-muted mb-0">Aquí puede ver el listado de tus recetas generadas</p>
            @if (auth()->user()->fichada == 1 || !auth()->user()->fichar)
            <div class="cabecera_acciones mt-3">
                <a class="btn btn-primary btn-sm" href="{{ route('empleados.recetas.create') }}">
                    <i class="fas fa-plus-circle mr-1"></i> Nueva receta
                </a>
            </div>
            @else
            <div class="small text-muted mt-2"><i>[Debe fichar para agregar una nueva receta]</i></div>
            @endif
        </div>

        @include('../mensajes_validacion')

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                @if($recetas->count()===0)
                <div class="p-4 text-muted">No hay recetas emitidas aún.</div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Trabajador</th>
                                <th>Cliente</th>
                                <th>ID Receta</th>
                                <th>Estado</th>
                                <th>PDF</th>
                                <th class="text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recetas as $r)
                            <tr>
                                <td>{{ $r->id }}</td>
                                <td>{{ $r->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($r->nomina)
                                    <a href="{{ url('/empleados/nominas/'.$r->nomina->id) }}" target="_blank">
                                        {{ $r->nomina->nombre }}
                                    </a>
                                    @else <span class="text-muted">-</span> @endif
                                </td>
                                <td>{{ optional($r->cliente)->nombre ?? '-' }}</td>
                                <td>{{ $r->id_receta ?? '-' }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $r->estado==='anulada'?'danger':($r->estado==='error'?'warning':'success') }}">
                                        {{ ucfirst($r->estado) }}
                                    </span>
                                </td>
                                <td>
                                    @if($r->pdf_url)
                                    <a href="{{ $r->pdf_url }}" target="_blank">Descargar</a>
                                    @else <span class="text-muted">-</span> @endif
                                </td>
                                <td class="text-right">
                                    <a class="btn btn-outline-primary btn-sm"
                                        href="{{ route('empleados.recetas.show', $r->id) }}">Ver</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3">{{ $recetas->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection