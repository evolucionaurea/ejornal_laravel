@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_admin')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Vista del usuario</h2>
            <p>Aquí puede ver toda la información del usuario</p>
            <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-gris-claro" href="{{url('admin/users')}}">
                    <i class="fas fa-chevron-left"></i>
                    Volver
                </a>
            </div>
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
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-lg-2 col-sm-12">
                          <div class="d-flex flex-column align-items-cente text-center">
                            <i class="fas fa-user fa-10x"></i>
                            <b>Nombre: </b> {{$user->nombre}}
                          </div>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <b>Cuil: </b> {{$user->Cuil}}
                                </li>
                                <li class="list-group-item">
                                    <b>Estado: </b>
                                    @if ($user->estado == 1)
                                      {{'Activo'}}
                                      @else
                                      {{'Inactivo'}}
                                    @endif
                                </li>
                                <li class="list-group-item">
                                    <b>Email: </b> {{ $user->email }}
                                </li>
                                <li class="list-group-item">
                                    <b>DNI: </b> {{ $user->dni }}
                                </li>
                                <li class="list-group-item">
                                    <b>Rol: </b> {{ $user->rol }}
                                </li>
                            </ul>
                        </div>
                        @if ($user->id_rol == 3)
                          <div class="col-lg-7 col-sm-12">
                              <ul class="list-group list-group-flush">
                                  <li class="list-group-item">
                                      <b>Nombre: </b> {{$datos->nombre}}
                                  </li>
                                  <li class="list-group-item">
                                      <b>Direccion: </b> {{ $datos->direccion }}
                                  </li>
                                  <li class="list-group-item">
                                      <b>Token: </b>
                                      <small style="font-size: 10px;">{{ $datos->token }}</small>
                                  </li>
                              </ul>
                          </div>
                        @endif
                    </div>
                </div>
            </div>

            @if ($user->id_rol == 2)
              <div class="tarjeta">
                <h4>últimas 30 Fichadas</h4>
                <table class="table table-striped table-hover table-sm tabla">
                  <thead>
                    <tr>
                      <th class="th-lg">
                        <a>
                          Ingreso
                          <i class="fas fa-sort ml-1"></i>
                        </a>
                      </th>
                      <th class="th-lg">
                        <a href="">
                          Egreso
                          <i class="fas fa-sort ml-1"></i>
                        </a>
                      </th>
                      <th class="th-lg">
                        <a href="">
                          Tiempo dedicado
                          <i class="fas fa-sort ml-1"></i>
                        </a>
                      </th>
                      <th class="th-lg">
                        <a href="">
                          Dispositivo
                          <i class="fas fa-sort ml-1"></i>
                        </a>
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($datos as $dato)
                      <tr>
                        <td>{{ (!empty($dato->ingreso)) ? date('d/m/Y',strtotime($dato->ingreso)) : "" }}</td>
                        <td>{{ (!empty($dato->egreso)) ? date('d/m/Y',strtotime($dato->egreso)) : "" }}</td>
                        <td>{{ $dato->tiempo_dedicado }}</td>
                        <td>{{ $dato->dispositivo }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif

        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>



@endsection
