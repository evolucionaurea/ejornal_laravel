@extends('partials.layout')

@section('title', 'Admin - Recetas')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_admin')

    <div id="page-content-wrapper">
        @include('partials.nav_sup')

        <div class="cabecera mb-3">
            <h2 class="h4 font-weight-bold mb-1">Listado de Recetas</h2>
            <p class="text-muted mb-0">Recetas digitales emitidas por los empleados</p>
        </div>

        @include('../mensajes_validacion')

        {{-- Filtros --}}
        <form id="form-filtros-recetas" class="mb-3" action="{{ route('admin.recetas') }}" method="GET">
            <div class="card shadow-sm border-0">
                <div class="card-body pb-2">
                    <div class="form-row align-items-end">

                        {{-- Fecha desde (visible) + hidden Y-m-d --}}
                        <div class="form-group col-sm-6 col-md-3 col-lg-2 mb-2">
                            <label for="f_desde_view" class="small mb-1">Fecha desde</label>

                            <input type="text" id="f_desde_view" class="form-control form-control-sm js-datepicker"
                                data-hidden-target="f_desde" autocomplete="off">

                            <input type="hidden" id="f_desde" name="f_desde" value="{{ request('f_desde') }}">
                        </div>

                        {{-- Fecha hasta --}}
                        <div class="form-group col-sm-6 col-md-3 col-lg-2 mb-2">
                            <label for="f_hasta_view" class="small mb-1">Fecha hasta</label>

                            <input type="text" id="f_hasta_view" class="form-control form-control-sm js-datepicker"
                                data-hidden-target="f_hasta" autocomplete="off">

                            <input type="hidden" id="f_hasta" name="f_hasta" value="{{ request('f_hasta') }}">
                        </div>

                        {{-- Trabajador --}}
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

                        {{-- Cliente --}}
                        <div class="form-group col-sm-6 col-md-3 col-lg-3 mb-2">
                            <label for="f_cliente" class="small mb-1">Cliente</label>
                            <select id="f_cliente" name="f_cliente"
                                class="form-control form-control-sm js-select2-cliente">
                                <option value="">Todos</option>
                                @foreach($clientes as $c)
                                <option value="{{ $c->id }}" {{ (string)$c->id === request('f_cliente') ? 'selected' :
                                    '' }}>
                                    {{ $c->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Estado --}}
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

                    </div>

                    <div class="row">
                        <div class="form-group col-12 col-lg-2 mb-2 text-right">
                            <button type="submit" class="btn-ejornal btn-ejornal-base btn-sm mr-2">
                                <i class="fas fa-search mr-1"></i> Aplicar
                            </button>
                            <a href="{{ route('admin.recetas') }}" class="btn-ejornal btn-ejornal-gris-claro btn-sm">
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
                    @include('admin.recetas._tabla', ['recetas' => $recetas])
                </div>
            </div>
        </div>
    </div>
</div>

@endsection