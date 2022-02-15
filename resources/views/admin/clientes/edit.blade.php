@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_admin')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Edición de clientes</h2>
            <p>Aquí podrá editar la información de un cliente</p>
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
            <form action="{{action('AdminClientesController@update', $cliente->id)}}" accept-charset="UTF-8" method="post">
                {{ csrf_field() }}
                <input name="_method" type="hidden" value="PUT">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Nombre de la empresa</label>
                        <input name="nombre" type="text" class="form-control form-control-sm" value="{{$cliente->nombre}}" placeholder="">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Direccion</label>
                        <input name="direccion" type="text" class="form-control form-control-sm" value="{{$cliente->direccion}}" placeholder="">
                    </div>
                    <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Guardar cambios</button>
                </div>
            </form>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>

@endsection
