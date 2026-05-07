@extends('partials.layout')

@section('title', 'Admin - Financiadores que admiten anulación de receta')

@section('content')
    <div class="d-flex" id="wrapper">
        @include('partials.sidebar_admin')

        <div id="page-content-wrapper">
            @include('partials.nav_sup')

            <div class="cabecera mb-3">
                <h2 class="h4 font-weight-bold mb-1">Financiadores - Anulación de Receta</h2>
                <p class="text-muted mb-0">Financiadores que permiten anular recetas digitales vía API.</p>
                <div class="cabecera_acciones">
                    <a class="btn-ejornal btn-ejornal-base" href="{{ route('admin.receta_financiadores_anulacion.create') }}">
                        <i class="fas fa-plus-circle"></i> Nuevo financiador
                    </a>
                </div>
            </div>

            @include('../mensajes_validacion')

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissible fade show mr-4 ml-4" role="alert">
                        {{ $error }}
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endforeach
            @endif

            <div class="tarjeta">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width:80px">ID</th>
                                <th style="width:140px">Id Financiador</th>
                                <th>Nombre</th>
                                <th class="text-right pr-3" style="width:120px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($financiadores as $f)
                                <tr>
                                    <td>{{ $f->id }}</td>
                                    <td><span class="badge badge-secondary">{{ $f->id_financiador }}</span></td>
                                    <td>{{ $f->nombre }}</td>
                                    <td class="text-right acciones_tabla">
                                        <a title="Editar"
                                            href="{{ route('admin.receta_financiadores_anulacion.edit', $f->id) }}">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <form action="{{ route('admin.receta_financiadores_anulacion.destroy', $f->id) }}"
                                            method="POST" onsubmit="return confirm('¿Eliminar este financiador?')"
                                            style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Eliminar"
                                                style="background:none;border:none;padding:0;cursor:pointer">
                                                <i class="fas fa-trash text-danger"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted p-3">No hay financiadores cargados aún.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
