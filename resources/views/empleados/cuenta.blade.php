@extends('partials.layout')

@section('title', 'Empleados')

@section('content')



<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')
        {{-- Contenido de la pagina --}}
        <div class="container-fluid">


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
                <form class="row">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_user" value="{{auth()->user()->id}}">
                    <div class="form-group col-md-3">
                        <label>Nombre</label>
                        <input disabled name="nombre" type="text" class="form-control form-control-sm" value="{{auth()->user()->nombre}}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Email</label>
                        <input disabled type="email" class="form-control form-control-sm" value="{{auth()->user()->email}}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Rol</label>
                        @foreach ($roles as $rol)
                        @if ($rol->id == auth()->user()->id_rol)
                        <input disabled name="rol" type="text" class="form-control form-control-sm" value="{{$rol->nombre}}">
                        @endif
                        @endforeach
                    </div>
                    <div class="form-group col-md-3">
                        <label>Estado</label>
                        @if (auth()->user()->estado == 1)
                        <input disabled name="estado" type="text" class="form-control form-control-sm" value="Activo">
                        @else
                        <input disabled name="estado" type="text" class="form-control form-control-sm" value="Inactivo">
                        @endif
                    </div>
                    {{-- Por el momento no pueden editar sus datos basicos, por tanto se comenta la opcion de hacer submit --}}
                    {{-- <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Guardar cambios</button> --}}
                </form>
                <hr>
                <form action="{{action('EmpleadosCuentaController@store')}}" accept-charset="UTF-8" method="post" enctype="multipart/form-data" class="row">
                {{ csrf_field() }}
                <input type="hidden" name="id_user" value="{{auth()->user()->id}}">
                <div class="form-group col-md-4">
                    <label>DNI</label>
                    <input name="dni" type="text" class="form-control form-control-sm" value="{{auth()->user()->dni}}">
                </div>
                <div class="form-group col-md-4">
                    <label>Cargar dni parte delantera</label>
                    <br>
                    @if (auth()->user()->archivo_dni == null)
                    <div class="alert alert-info" role="alert">No hay un archivo adjunto</div>
                    @else
                      <div class="alert alert-primary" role="alert">
                        Tiene un archivo cargado actualmente:
                        <br>
                        <a class="alert-link " href="{{route('cuenta.download_dni', auth()->user()->id)}}">
                            <i class="fa fa-file ml-2"></i>{{auth()->user()->archivo_dni}}
                        </a>
                      </div>
                    @endif
                    <input name="archivo_dni" type="file" class="form-control-file" value="">
                </div>
                <div class="form-group col-md-4">
                    <label>Cargar dni parte trasera</label>
                    <br>
                    @if (auth()->user()->archivo_dni_detras == null)
                    <div class="alert alert-info" role="alert">No hay un archivo adjunto</div>
                    @else
                      <div class="alert alert-primary" role="alert">
                        Tiene un archivo cargado actualmente:
                        <br>
                        <a class="alert-link " href="{{route('cuenta.download_dni_detras', auth()->user()->id)}}">
                            <i class="fa fa-file ml-2"></i>{{auth()->user()->archivo_dni_detras}}
                        </a>
                      </div>
                    @endif
                    <input name="archivo_dni_detras" type="file" class="form-control-file" value="">
                </div>
                <div class="form-group col-md-4">
                    <label>Matrícula</label>
                    <input name="matricula" type="text" class="form-control form-control-sm" value="{{auth()->user()->matricula}}">
                </div>
                <div class="form-group col-md-4">
                    <label>Cargar matrícula parte delantera</label>
                    <br>
                    @if (auth()->user()->archivo_matricula == null)
                    <div class="alert alert-info" role="alert">No hay un archivo adjunto</div>
                    @else
                    <div class="alert alert-primary" role="alert">
                      Tiene un archivo cargado actualmente:
                      <br>
                      <a class="alert-link" href="{{route('cuenta.download_matricula', auth()->user()->id)}}">
                          <i class="fa fa-file ml-2"></i>{{auth()->user()->archivo_matricula}}
                      </a>
                    </div>
                    @endif
                    <input name="archivo_matricula" type="file" class="form-control-file">
                </div>
                <div class="form-group col-md-4">
                    <label>Cargar matrícula parte trasera</label>
                    <br>
                    @if (auth()->user()->archivo_matricula_detras == null)
                    <div class="alert alert-info" role="alert">No hay un archivo adjunto</div>
                    @else
                    <div class="alert alert-primary" role="alert">
                      Tiene un archivo cargado actualmente:
                      <br>
                      <a class="alert-link" href="{{route('cuenta.download_matricula_detras', auth()->user()->id)}}">
                          <i class="fa fa-file ml-2"></i>{{auth()->user()->archivo_matricula_detras}}
                      </a>
                    </div>
                    @endif
                    <input name="archivo_matricula_detras" type="file" class="form-control-file">
                </div>
                <div class="form-group col-md-4">
                    <label>Fecha vencimiento matrícula</label>
                    <input id="fecha_vencimiento_matricula" name="fecha_vencimiento_matricula" type="datetime" class="form-control" value="{{ (auth()->user()->fecha_vencimiento != null)
                        ?
                        date('d/m/Y',strtotime(auth()->user()->fecha_vencimiento))
                        :
                        '' }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Titulo habilitante parte delantera</label>
                    <br>
                    @if (auth()->user()->titulo == null)
                    <div class="alert alert-info" role="alert">No hay un archivo adjunto</div>
                    @else
                      <div class="alert alert-primary" role="alert">
                        Tiene un archivo cargado actualmente:
                        <br>
                        <a class="alert-link" href="{{route('cuenta.download_titulo', auth()->user()->id)}}">
                            <i class="fa fa-file lf-2"></i>{{auth()->user()->titulo}}
                        </a>
                      </div>
                    @endif
                    <input name="archivo_titulo" type="file" class="form-control-file">
                </div>
                <div class="form-group col-md-4">
                    <label>Titulo habilitante parte trasera</label>
                    <br>
                    @if (auth()->user()->archivo_titulo_detras == null)
                    <div class="alert alert-info" role="alert">No hay un archivo adjunto</div>
                    @else
                      <div class="alert alert-primary" role="alert">
                        Tiene un archivo cargado actualmente:
                        <br>
                        <a class="alert-link" href="{{route('cuenta.download_titulo_detras', auth()->user()->id)}}">
                            <i class="fa fa-file lf-2"></i>{{auth()->user()->archivo_titulo_detras}}
                        </a>
                      </div>
                    @endif
                    <input name="archivo_titulo_detras" type="file" class="form-control-file">
                </div>
                <div class="col-12">
                  <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Guardar</button>
                </div>
                </form>
            </div>


            <div class="tarjeta">
                <h4>Seguridad</h4>
                <p>¿Desea cambiar la contraseña?</p>
                <form action="{{action('EmpleadosCuentaController@cambiar_pass')}}" method="post" class="row">
                {{ csrf_field() }}
                <input type="hidden" name="id_cliente" value="id_cliente">
                <input type="hidden" name="id_user" value="{{auth()->user()->id}}">
                <div class="form-group col-md-6">
                    <label>Nueva contraseña</label>
                    <input name="password" type="password" class="form-control form-control-sm" value="">
                </div>
                <div class="form-group col-md-6">
                    <label>Repetir nueva contraseña</label>
                    <input name="cpassword" type="password" class="form-control form-control-sm" value="">
                </div>
                <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Cambiar contraseña</button>
                </form>
            </div>


        </div>
        {{-- Contenido de la pagina --}}
    </div>
</div>



@endsection
