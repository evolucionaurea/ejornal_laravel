@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_admin')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Edición de medicamentos</h2>
            <p>Aquí podrá editar la información de un medicamento</p>
            <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-gris-claro" href="{{url('admin/medicamentos')}}"><i class="fas fa-arrow-circle-left"></i>Volver</a>
            </div>
        </div>

        @include('../../mensajes_validacion')
        @if ($errors->any())
        @foreach ($errors->all() as $error)
        <div class="alert alert-danger alert-dismissible fade show mr-4 ml-4" role="alert">
            {{$error}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endforeach
        @endif

        <div class="tarjeta">
            <form action="{{action('AdminMedicamentosController@update', $medicamento->id)}}" accept-charset="UTF-8" method="post">
                {{ csrf_field() }}
                <input name="_method" type="hidden" value="PUT">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Nombre del medicamento</label>
                        <input name="nombre" type="text" class="form-control form-control-sm" value="{{$medicamento->nombre}}" placeholder="">
                    </div>
                    <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Guardar cambios</button>
                </div>
            </form>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>

@endsection
