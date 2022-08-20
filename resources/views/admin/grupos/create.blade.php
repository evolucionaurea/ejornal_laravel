@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_admin')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Creación de Grupos Empresarios</h2>
            <p>Aquí puedes crear los grupos empresarios del sistema</p>
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
            <form action="{{action('AdminGruposController@store')}}" accept-charset="UTF-8" method="post">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Nombre del Grupo Empresario</label>
                    <input required name="nombre" type="text" class="form-control form-control-sm" placeholder="">
                </div>
                <div class="form-group col-md-4">
                    <label>Dirección</label>
                    <input required name="direccion" type="text" class="form-control form-control-sm" placeholder="">
                </div>
                @if (count($clientes) > 0 && !empty($clientes))
                  <div class="form-group col-md-4 mostrar_clientes">
                    <label>¿Que Clientes tiene asociados?</label>
                    <br>
                    <select style="max-width: 500px; min-width: 300px;" id="cliente_select_multiple" multiple="multiple" name="clientes[]">
                      @foreach ($clientes as $cliente)
                        <option value="{{$cliente->id}}">{{$cliente->nombre}}</option>
                      @endforeach
                    </select>
                  </div>
                  @else
                    <div class="form-group col-md-12">
          					  No tienes Clientes creados. Vé a su sección y crea Clientes
          					</div>
                @endif
                <div class="col-12">
                  <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Crear grupo</button>
                </div>
            </div>
            </form>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>

@endsection
