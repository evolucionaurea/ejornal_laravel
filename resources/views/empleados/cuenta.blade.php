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
                        <input disabled name="nombre" type="text" class="form-control form-control-sm"
                            value="{{auth()->user()->nombre}}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Email</label>
                        <input disabled type="email" class="form-control form-control-sm"
                            value="{{auth()->user()->email}}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Rol</label>
                        @foreach ($roles as $rol)
                        @if ($rol->id == auth()->user()->id_rol)
                        <input disabled name="rol" type="text" class="form-control form-control-sm"
                            value="{{$rol->nombre}}">
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
                    <div class="form-group col-md-3">
                        <label>Calle</label>
                        <input disabled name="calle" type="text" class="form-control form-control-sm"
                            value="{{auth()->user()->calle}}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Número</label>
                        <input disabled name="nro" type="text" class="form-control form-control-sm"
                            value="{{auth()->user()->nro}}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Entre calles</label>
                        <input disabled name="entre_calles" type="text" class="form-control form-control-sm"
                            value="{{auth()->user()->entre_calles}}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Localidad</label>
                        <input disabled name="localidad" type="text" class="form-control form-control-sm"
                            value="{{auth()->user()->localidad}}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Partido</label>
                        <input disabled name="partido" type="text" class="form-control form-control-sm"
                            value="{{auth()->user()->partido}}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Codigo postal</label>
                        <input disabled name="cod_postal" type="text" class="form-control form-control-sm"
                            value="{{auth()->user()->cod_postal}}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Observaciones</label>
                        <input disabled name="observaciones" type="text" class="form-control form-control-sm"
                            value="{{auth()->user()->observaciones}}">
                    </div>
                    {{-- Por el momento no pueden editar sus datos basicos, por tanto se comenta la opcion de hacer
                    submit --}}
                    {{-- <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Guardar
                        cambios</button> --}}
                </form>
                <hr>
                <form action="{{action('EmpleadosCuentaController@store')}}" accept-charset="UTF-8" method="post"
                    enctype="multipart/form-data" class="row">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_user" value="{{auth()->user()->id}}">
                    <div class="form-group col-md-4">
                        <label>DNI</label>
                        <input name="dni" type="text" class="form-control form-control-sm"
                            value="{{auth()->user()->dni}}">
                    </div>



                    <div class="form-group col-md-4">
                        <label>Cargar DNI parte delantera</label>
                        <br>
                        @if (auth()->user()->archivo_dni == null)
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="fa fa-info-circle mr-2"></i> No hay un archivo adjunto
                        </div>
                        @else
                        <div class="d-flex align-items-center alert alert-primary" role="alert">
                            <a href="{{ route('cuenta.download_dni', auth()->user()->id) }}"
                                class="d-flex align-items-center text-white text-decoration-none">
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="fa fa-file text-white"></i>
                                </div>
                                <span class="ml-2 text-dark">Actual: <strong>{{
                                        auth()->user()->archivo_dni }}</strong></span>
                            </a>
                        </div>
                        @endif
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="archivo_dni" name="archivo_dni">
                            <label class="custom-file-label" for="archivo_dni">Seleccionar otro archivo</label>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Cargar DNI parte trasera</label>
                        <br>
                        @if (auth()->user()->archivo_dni_detras == null)
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="fa fa-info-circle mr-2"></i> No hay un archivo adjunto
                        </div>
                        @else
                        <div class="d-flex align-items-center alert alert-primary" role="alert">
                            <a href="{{ route('cuenta.download_dni_detras', auth()->user()->id) }}"
                                class="d-flex align-items-center text-white text-decoration-none">
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="fa fa-file text-white"></i>
                                </div>
                                <span class="ml-2 text-dark">Actual:<strong>{{
                                        auth()->user()->archivo_dni_detras }}</strong></span>
                            </a>
                        </div>
                        @endif
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="archivo_dni_detras"
                                name="archivo_dni_detras">
                            <label class="custom-file-label" for="archivo_dni_detras">Seleccionar otro archivo</label>
                        </div>
                    </div>




                    <div class="form-group col-md-4">
                        <label>Matrícula</label>
                        <input name="matricula" type="text" class="form-control form-control-sm"
                            value="{{auth()->user()->matricula}}">
                    </div>




                    <div class="form-group col-md-4">
                        <label>Cargar matrícula parte delantera</label>
                        <br>
                        @if (auth()->user()->archivo_matricula == null)
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="fa fa-info-circle mr-2"></i> No hay un archivo adjunto
                        </div>
                        @else
                        <div class="d-flex align-items-center alert alert-primary" role="alert">
                            <a href="{{ route('cuenta.download_matricula', auth()->user()->id) }}"
                                class="d-flex align-items-center text-white text-decoration-none">
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="fa fa-file text-white"></i>
                                </div>
                                <span class="ml-2 text-dark">Actual: <strong>{{
                                        auth()->user()->archivo_matricula }}</strong></span>
                            </a>
                        </div>
                        @endif
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="archivo_matricula"
                                name="archivo_matricula">
                            <label class="custom-file-label" for="archivo_matricula">Seleccionar archivo</label>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Cargar matrícula parte trasera</label>
                        <br>
                        @if (auth()->user()->archivo_matricula_detras == null)
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="fa fa-info-circle mr-2"></i> No hay un archivo adjunto
                        </div>
                        @else
                        <div class="d-flex align-items-center alert alert-primary" role="alert">
                            <a href="{{ route('cuenta.download_matricula_detras', auth()->user()->id) }}"
                                class="d-flex align-items-center text-white text-decoration-none">
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="fa fa-file text-white"></i>
                                </div>
                                <span class="ml-2 text-dark">Actual: <strong>{{
                                        auth()->user()->archivo_matricula_detras }}</strong></span>
                            </a>
                        </div>
                        @endif
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="archivo_matricula_detras"
                                name="archivo_matricula_detras">
                            <label class="custom-file-label" for="archivo_matricula_detras">Seleccionar archivo</label>
                        </div>
                    </div>




                    <div class="form-group col-md-4">
                        <label>Fecha vencimiento matrícula</label>
                        <input id="fecha_vencimiento_matricula" name="fecha_vencimiento_matricula" type="datetime"
                            class="form-control" value="{{ (auth()->user()->fecha_vencimiento != null)
                        ?
                        date('d/m/Y',strtotime(auth()->user()->fecha_vencimiento))
                        :
                        '' }}">
                    </div>



                    <div class="form-group col-md-4">
                        <label>Título habilitante parte delantera</label>
                        <br>
                        @if (auth()->user()->titulo == null)
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="fa fa-info-circle mr-2"></i> No hay un archivo adjunto
                        </div>
                        @else
                        <div class="d-flex align-items-center alert alert-primary" role="alert">
                            <a href="{{ route('cuenta.download_titulo', auth()->user()->id) }}"
                                class="d-flex align-items-center text-white text-decoration-none">
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="fa fa-file text-white"></i>
                                </div>
                                <span class="ml-2 text-dark">Actual: <strong>{{
                                        auth()->user()->titulo }}</strong></span>
                            </a>
                        </div>
                        @endif
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="archivo_titulo" name="archivo_titulo">
                            <label class="custom-file-label" for="archivo_titulo">Seleccionar archivo</label>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Título habilitante parte trasera</label>
                        <br>
                        @if (auth()->user()->archivo_titulo_detras == null)
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="fa fa-info-circle mr-2"></i> No hay un archivo adjunto
                        </div>
                        @else
                        <div class="d-flex align-items-center alert alert-primary" role="alert">
                            <a href="{{ route('cuenta.download_titulo_detras', auth()->user()->id) }}"
                                class="d-flex align-items-center text-white text-decoration-none">
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="fa fa-file text-white"></i>
                                </div>
                                <span class="ml-2 text-dark">Actual: <strong>{{
                                        auth()->user()->archivo_titulo_detras }}</strong></span>
                            </a>
                        </div>
                        @endif
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="archivo_titulo_detras"
                                name="archivo_titulo_detras">
                            <label class="custom-file-label" for="archivo_titulo_detras">Seleccionar archivo</label>
                        </div>
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

<script>
    //     $(".custom-file-input").on("change", function() {
//     let fileName = $(this).val().split("\\").pop();
//     $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
// });

</script>


@endsection