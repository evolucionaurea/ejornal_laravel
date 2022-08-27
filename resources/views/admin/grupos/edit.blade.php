@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_admin')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Edición de grupo empresario</h2>
            <p>Aquí podrá editar la información de un grupo empresario</p>
            <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-gris-claro" href="{{url('admin/grupos')}}"><i class="fas fa-arrow-circle-left"></i>Volver</a>
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
            <form action="{{action('AdminGruposController@update', $grupo->id)}}" accept-charset="UTF-8" method="post">
                {{ csrf_field() }}
                <input name="_method" type="hidden" value="PUT">
                <div class="form-row">
                    <div class="form-group col-lg-3 col-md-4 col-sm-12">
                        <label>Nombre</label>
                        <input name="nombre" type="text" class="form-control form-control-sm" value="{{$grupo->nombre}}" placeholder="">
                    </div>
                    <div class="form-group col-lg-3 col-md-4 col-sm-12">
                        <label>Direccion</label>
                        <input name="direccion" type="text" class="form-control form-control-sm" value="{{$grupo->direccion}}" placeholder="">
                    </div>
                    <div class="form-group col-lg-3 col-md-4 col-sm-12 cliente_original">
                        <label>Clientes</label>
                        <br>
                        <select style="max-width: 500px; min-width: 300px;" id="cliente_select_multiple" multiple="multiple" name="clientes[]">
                            @foreach ($clientes as $cliente)
                              @if (in_array($cliente->id, $clientes_seleccionados))
                                <option selected value="{{$cliente->id}}">{{$cliente->nombre}}</option>
                                @else
                                <option value="{{$cliente->id}}">{{$cliente->nombre}}</option>
                              @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Guardar cambios</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>




@endsection
