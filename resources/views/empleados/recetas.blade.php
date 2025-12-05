@extends('partials.layout')

@section('title', 'Empleado - Recetas')

@section('content')
<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')

    <div id="page-content-wrapper">
        @include('partials.nav_sup')

        <div class="cabecera mb-3">
            <h2 class="h4 font-weight-bold mb-1">Recetas generadas</h2>
            <p class="text-muted mb-0">Listado de recetas emitidas por tu usuario</p>

            @if (auth()->user()->fichada == 1 || !auth()->user()->fichar)
            <div class="cabecera_acciones mt-3">
                <a class="btn-ejornal btn-ejornal-base" href="{{ route('empleados.recetas.create') }}">
                    <i class="fas fa-plus-circle mr-1"></i> Nueva receta
                </a>
            </div>
            @else
            <div class="small text-muted mt-2">
                <i>[Debe fichar para agregar una nueva receta]</i>
            </div>
            @endif
        </div>

        @include('../mensajes_validacion')


        {{-- Filtros --}}
        <form id="form-filtros-recetas" class="mb-3" action="{{ route('empleados.recetas') }}" method="GET">
            <div class="card shadow-sm border-0">
                <div class="card-body pb-2">
                    <div class="form-row align-items-end">

                        <div class="form-group col-sm-6 col-md-3 col-lg-2 mb-2">
                            <label for="f_desde_view" class="small mb-1">Fecha desde</label>

                            {{-- Campo visible (formato dd-mm-yy) --}}
                            <input type="text" id="f_desde_view" class="form-control form-control-sm js-datepicker"
                                data-hidden-target="f_desde" autocomplete="off">

                            {{-- Campo real que viaja al backend (Y-m-d) --}}
                            <input type="hidden" id="f_desde" name="f_desde" value="{{ request('f_desde') }}">
                        </div>

                        <div class="form-group col-sm-6 col-md-3 col-lg-2 mb-2">
                            <label for="f_hasta_view" class="small mb-1">Fecha hasta</label>

                            {{-- Campo visible (formato dd-mm-yy) --}}
                            <input type="text" id="f_hasta_view" class="form-control form-control-sm js-datepicker"
                                data-hidden-target="f_hasta" autocomplete="off">

                            {{-- Campo real que viaja al backend (Y-m-d) --}}
                            <input type="hidden" id="f_hasta" name="f_hasta" value="{{ request('f_hasta') }}">
                        </div>

                        <div class="form-group col-sm-6 col-md-3 col-lg-3 mb-2">
                            <label for="f_nomina" class="small mb-1">Trabajador</label>
                            <select id="f_nomina" name="f_nomina"
                                class="form-control form-control-sm js-select2-nomina">
                                <option value="">Todos</option>
                                @foreach($nominas as $n)
                                <option value="{{ $n->id }}" {{ (string)$n->id === request('f_nomina') ? 'selected' : ''
                                    }}>
                                    {{ $n->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-sm-6 col-md-3 col-lg-2 mb-2">
                            <label for="f_estado" class="small mb-1">Estado</label>
                            <select id="f_estado" name="f_estado" class="form-control form-control-sm">
                                <option value="">Todos</option>
                                @foreach($estados as $estado)
                                <option value="{{ $estado }}" {{ $estado===request('f_estado') ? 'selected' : '' }}>
                                    {{ ucfirst($estado) }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-12 col-lg-3 mb-2 text-right">
                            <button type="submit" class="btn-ejornal btn-ejornal-base btn-sm mr-2">
                                <i class="fas fa-search mr-1"></i> Aplicar filtros
                            </button>
                            <a href="{{ route('empleados.recetas') }}"
                                class="btn-ejornal btn-ejornal-gris-claro btn-sm">
                                Limpiar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>



        {{-- Tabla + paginación (Ajax) --}}
        <div class="tarjeta">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0" id="recetas-container">
                    @include('empleados.recetas._tabla', ['recetas' => $recetas])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection