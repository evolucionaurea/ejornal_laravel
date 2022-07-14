@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_admin')
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
            <form action="{{action('AdminCuentaController@store')}}" method="post" class="row">
              {{ csrf_field() }}
              <input type="hidden" name="id_user" value="{{auth()->user()->id}}">
                <div class="form-group col-md-3">
                    <label >Nombre</label>
                    <input name="nombre" type="text" class="form-control form-control-sm" value="{{auth()->user()->nombre}}">
                </div>
                <div class="form-group col-md-3">
                    <label >Email</label>
                    <input disabled type="email" class="form-control form-control-sm" value="{{auth()->user()->email}}">
                </div>
                <div class="form-group col-md-3">
                    <label >Rol</label>
                  @foreach ($roles as $rol)
                    @if ($rol->id == auth()->user()->id_rol)
                      <input disabled name="rol" type="text" class="form-control form-control-sm" value="{{$rol->nombre}}">
                    @endif
                  @endforeach
                </div>
                <div class="form-group col-md-3">
                    <label >Estado</label>
                    <select name="estado" class="form-control form-control-sm">
                        @if (auth()->user()->estado == 1)
                            <option selected value="1">Activo</option>
                            <option value="0">Inactivo</option>
                          @else
                            <option value="1">Activo</option>
                            <option selected value="0">Inactivo</option>
                        @endif
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label >User creado:</label>
                    <input
                    disabled
                    type="text"
                    class="form-control form-control-sm"
                    value="{{date('d/m/Y',strtotime(auth()->user()->created_at))}}"
                    >
                </div>
                <div class="form-group col-md-4">
                    <label >DNI:</label>
                    <input
                    disabled
                    type="text"
                    class="form-control form-control-sm"
                    value="{{ (auth()->user()->dni !== null) ? auth()->user()->dni : "" }}"
                    >
                </div>
                <div class="form-group col-md-4">
                    <label >CUIL:</label>
                    <input
                    disabled
                    type="text"
                    class="form-control form-control-sm"
                    value="{{ (auth()->user()->cuil !== null) ? auth()->user()->cuil : "" }}"
                    >
                </div>
                <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Guardar cambios</button>
            </form>
        </div>


        <div class="tarjeta">
          <h4>Seguridad</h4>
          <p>¿Desea cambiar la contraseña?</p>
            <form action="{{action('AdminCuentaController@cambiar_pass')}}" method="post" class="row">
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


@endsection
