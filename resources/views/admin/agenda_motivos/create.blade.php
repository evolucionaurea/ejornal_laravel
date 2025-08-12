@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_admin')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Creación de motivos para la agenda</h2>
            <p>Aquí puedes crear los motivos para la agenda del sistema</p>
            <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-gris-claro" href="{{url('admin/agenda_motivos')}}"><i
                        class="fas fa-arrow-circle-left"></i>Volver</a>
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
            <form action="{{action('AdminAgendaMotivoController@store')}}" accept-charset="UTF-8" method="post">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Nombre del motivo</label>
                        <input required name="nombre" type="text" class="form-control form-control-sm" placeholder="">
                    </div>
                </div>
                <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Crear motivo</button>
            </form>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>

@endsection