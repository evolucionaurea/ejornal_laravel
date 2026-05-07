@extends('partials.layout')

@section('title', 'Admin - Nuevo financiador anulación')

@section('content')
    <div class="d-flex" id="wrapper">
        @include('partials.sidebar_admin')

        <div id="page-content-wrapper">
            @include('partials.nav_sup')

            <div class="cabecera mb-3">
                <h2 class="h4 font-weight-bold mb-1">Nuevo Financiador - Anulación</h2>
            </div>

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissible fade show mr-4 ml-4" role="alert">
                        {{ $error }}
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endforeach
            @endif

            <div class="tarjeta">
                <div class="card-body">
                    <form action="{{ route('admin.receta_financiadores_anulacion.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="id_financiador">ID Financiador <span class="text-danger">*</span></label>
                            <input type="number" id="id_financiador" name="id_financiador" min="1"
                                class="form-control @error('id_financiador') is-invalid @enderror"
                                value="{{ old('id_financiador') }}" required>
                            @error('id_financiador')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Número entero que identifica al financiador en la API
                                QBI2.</small>
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre <span class="text-danger">*</span></label>
                            <input type="text" id="nombre" name="nombre" maxlength="200"
                                class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}"
                                required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn-ejornal btn-ejornal-base mr-2">Guardar</button>
                            <a href="{{ route('admin.receta_financiadores_anulacion.index') }}"
                                class="btn-ejornal btn-ejornal-gris-claro">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
