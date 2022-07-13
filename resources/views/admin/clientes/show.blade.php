@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_admin')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Desgloce de la empresa</h2>
            <p>Aquí puede ver toda la información referente a ésta empresa</p>
            <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-gris-claro" href="{{url('admin/clientes')}}">
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
                        <div class="col-lg-4 col-sm-12">
                          <div class="d-flex flex-column align-items-cente text-center">
                            <i class="fas fa-user fa-10x"></i>
                            <div class="alert alert-info mt-2">
                            @if ($cliente->token == null)
                              No hay un token generado
                            @else
                              <b>Token</b>
                              <small>{{$cliente->token}}</small>
                            @endif
                          </div>
                          </div>
                        </div>
                        <div class="col-lg-4 col-sm-12">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <b>Empresa: </b> {{$cliente->nombre}}
                                </li>
                                <li class="list-group-item">
                                    <b>Dirección: </b> {{$cliente->direccion}}</li>
                                <li class="list-group-item">
                                    <b>Dado de alta: </b> {{ (!empty($cliente->created_at)) ? date('d/m/Y',strtotime($cliente->created_at)) : "" }}
                                </li>
                                <li class="list-group-item d-flex">
                                    <b class="mr-2">Token:</b>
                                      <form action="{{action('AdminClientesController@generarToken')}}" accept-charset="UTF-8" method="post">
                                        @csrf
                                        <input type="hidden" name="id_cliente" value="{{$cliente->id}}">
                                        @if ($cliente->token == null)
                                            <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Generar token</button>
                                          @else
                                            <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Generar nuevo token</button>
                                            <a class="btn-ejornal tag_ejornal_danger" href="#" data-toggle="modal" data-target="#modal_eliminar_token_validation">Eliminar token</a>
                                        @endif
                                      </form>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-4 col-sm-12">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <b>Nuestro personal trabajando aquí:</b>
                                    <br>
                                    <div class="d-flex flex-wrap mt-2">
                                      @if (isset($empleados) && count($empleados) > 0 && $empleados != null)
                                        @foreach ($empleados as $empleado)
                                          <span class="tag_ejornal tag_ejornal_dark mb-3">{{$empleado['empleado']}}</span>
                                        @endforeach
                                        @else
                                          <span>No hay empleados trabajando aquí</span>
                                      @endif
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tarjeta">
                <h4>Nómina</h4>
                <table class="table table-striped table-hover table-sm tabla">
                    <thead>
                        <tr>
                            <th class="th-lg">
                                <a>
                                    Nombre
                                    <i class="fas fa-sort ml-1"></i>
                                </a>
                            </th>
                            <th class="th-lg">
                                <a href="">
                                    Email
                                    <i class="fas fa-sort ml-1"></i>
                                </a>
                            </th>
                            <th class="th-lg">
                                <a href="">
                                    Tel
                                    <i class="fas fa-sort ml-1"></i>
                                </a>
                            </th>
                            <th class="th-lg">
                                <a href="">
                                    DNI
                                    <i class="fas fa-sort ml-1"></i>
                                </a>
                            </th>
                            <th class="th-lg">
                                <a href="">
                                    Estado
                                    <i class="fas fa-sort ml-1"></i>
                                </a>
                            </th>
                            <th class="th-lg">
                                <a href="">
                                    Fecha alta
                                    <i class="fas fa-sort ml-1"></i>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($trabajadores as $trabajador)
                        <tr>
                            <td>{{$trabajador->nombre}}</td>
                            <td>{{$trabajador->email}}</td>
                            <td>{{$trabajador->telefono}}</td>
                            <td>{{$trabajador->dni}}</td>
                            <td>
                                @if ($trabajador->estado == 1)
                                Activo
                                @else
                                Inactivo
                                @endif
                            </td>
                            <td>{{ (!empty($trabajador->created_at)) ? date('d/m/Y',strtotime($trabajador->created_at)) : "" }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modal_eliminar_token_validation" tabindex="-1" role="dialog" aria-labelledby="title_eliminar_token" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title_eliminar_token">Eliminar token</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" role="alert">
          <h4 class="alert-heading">¿Seguro desea eliminar el token?</h4>
          <p>Con esta acción no solo eliminará el token del cliente, sino que éste ya no tendrá acceso a utilizar la API</p>
          <form action="{{action('AdminClientesController@deleteToken')}}" accept-charset="UTF-8" method="post">
            @csrf
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="id_cliente" value="{{$cliente->id}}">
            <button class="btn-ejornal tag_ejornal_danger" type="submit" name="button">Si, eliminar token y quitar acceso</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
