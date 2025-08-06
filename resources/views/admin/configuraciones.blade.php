@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_admin')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Configuraciones</h2>
            <p>Configuracion global del sitio</p>
        </div>

        @include('../mensajes_validacion')
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
            @if ($configuracion != null)
            <form action="{{action('AdminConfiguracionController@update', $configuracion->id)}}" accept-charset="UTF-8"
                method="post">
                {{ csrf_field() }}
                <input name="_method" type="hidden" value="PUT">
                @else
                <form action="{{action('AdminConfiguracionController@store')}}" method="post" class="row">
                    {{ csrf_field() }}
                    @endif
                    <div class="form-group col-md-3">
                        <label>Online</label>
                        <select name="online" class="form-control form-control-sm" required>
                            <option value="1" @if(isset($configuracion->online) && $configuracion->online == 1) selected
                                @endif>Sí</option>
                            <option value="0" @if(isset($configuracion->online) && $configuracion->online == 0) selected
                                @endif>No</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Guardar</button>
                    </div>
                </form>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>


@endsection