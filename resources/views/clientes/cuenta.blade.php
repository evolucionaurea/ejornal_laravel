@extends('partials.layout')

@section('title', 'Clientes')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_clientes')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Mi cuenta</h2>
            <p>Aquí puedes ver tus datos personales</p>
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
          <h4>Datos básicos</h4>
            <form action="{{action('ClientesCuentaController@store')}}" method="post" class="row">
              {{ csrf_field() }}
              <input type="hidden" name="id_user" value="{{auth()->user()->id}}">
                <div class="form-group col-md-4">
                    <label >Nombre</label>
                    <input name="nombre" type="text" class="form-control form-control-sm" value="{{auth()->user()->nombre}}">
                </div>
                <div class="form-group col-md-4">
                    <label >Email</label>
                    <input disabled type="email" class="form-control form-control-sm" value="{{auth()->user()->email}}">
                </div>
                <div class="form-group col-md-4">
                    <label >Estado</label>
                    <p style="color: grey;">
                      @if (auth()->user()->estado == 1)
                        Activo
                      @else
                        Inactivo
                      @endif
                    </p>
                </div>
                <div class="form-group col-md-4">
                    <label >
                      Token
                      <button type="button" class="text text-primary" data-toggle="modal" data-target="#modal_token">
                        <i class="fas fa-question-circle"></i>
                      </button>
                    </label>
                    <p style="color: grey;">
                      @if ($cliente->token == null)
                        Usted no tiene un token para utilizar la API
                      @else
                        <small>{{$cliente->token}}</small>
                      @endif
                    </p>
                </div>
                <div class="col-12">
                  <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Guardar cambios</button>
                </div>
            </form>
        </div>


        <div class="tarjeta">
          <h4>Seguridad</h4>
          <p>¿Desea cambiar la contraseña?</p>
            <form action="{{action('ClientesCuentaController@cambiar_pass')}}" method="post" class="row">
              {{ csrf_field() }}
              <input type="hidden" name="id_user" value="{{auth()->user()->id}}">
                <div class="form-group col-md-6">
                    <label >Nueva contraseña</label>
                    <input name="password" type="password" class="form-control form-control-sm" value="">
                </div>
                <div class="form-group col-md-6">
                    <label >Repetir nueva contraseña</label>
                    <input name="cpassword" type="password" class="form-control form-control-sm" value="">
                </div>
                <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Cambiar contraseña</button>
            </form>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal_token" tabindex="-1" aria-labelledby="modal_info_token" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_info_token">Token</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-flush">
          <li class="list-group-item">
            <small>El token es visible y está disponible cuando la empresa lo crea para usted.</small>
          </li>
          <li class="list-group-item">
            <small>El token le servirá para utilizar la API.</small>
          </li>
          <li class="list-group-item">
            <small>
              Una Api es un concepto técnico. Por lo que debe facilitarle la información de la sección de API,
              junto a este token, a su programador o equipo de IT.
            </small>
          </li>
          <li class="list-group-item">
            <small>Ante cualquier duda comuníquese con la empresa</small>
          </li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-ejornal btn-ejornal-base" data-dismiss="modal">Ok</button>
      </div>
    </div>
  </div>
</div>


@endsection
