@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Edición de trabajadores de la nómina</h2>
            <p>Aquí podrá editar la información de un trabajador de la nómina</p>
            <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-gris-claro" href="{{ url('empleados/nominas') }}"><i class="fas fa-arrow-circle-left"></i>Volver</a>
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
            <form action="{{action('EmpleadosNominasController@update', $trabajador->id)}}" accept-charset="UTF-8" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input name="_method" type="hidden" value="PUT">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Nombre</label>
                        <input name="nombre" type="text" class="form-control form-control-sm" value="{{$trabajador->nombre}}" placeholder="">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Email</label>
                        <input name="email" type="email" class="form-control form-control-sm" value="{{$trabajador->email}}" placeholder="">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Telefono</label>
                        <input name="telefono" type="text" class="form-control form-control-sm" value="{{$trabajador->telefono}}" placeholder="">
                    </div>
                    <div class="form-group col-md-4">
                        <label>DNI</label>
                        <input name="dni" type="text" class="form-control form-control-sm" value="{{$trabajador->dni}}" placeholder="">
                    </div>
                    <div class="form-group col-md-4">
                        <label >Estado</label>
                        <select name="estado" class="form-control form-control-sm">
                          @if ($trabajador->estado == 1)
                              <option selected value="1">Activo</option>
                              <option value="0">Inactivo</option>
                            @else
                              <option value="1">Activo</option>
                              <option selected value="0">Inactivo</option>
                          @endif
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Sector</label>
                        <input name="sector" type="text" class="form-control form-control-sm" value="{{$trabajador->sector}}" placeholder="">
                    </div>
                    <div class="form-group col-md-6">
                      <label>Foto</label>
                      <br>
                      @if (isset($trabajador->foto) && !empty($trabajador->foto))
                        <img style="width: 300px;" src="{{asset('storage/nominas/fotos/'.$trabajador->id.'/'.$trabajador->hash_foto)}}">
                      @else
                        <span>
                          <i class="fas fa-user fa-1x"></i>
                          Sin foto cargada
                        </span>
                      @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label>Subir/Reemplazar foto</label>
                        <input name="foto" type="file" class="form-control form-control-sm" placeholder="">
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
