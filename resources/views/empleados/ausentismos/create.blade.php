@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Creación de ausencias</h2>
            <p>Aquí puedes cargar a los ausencias que formarán parte de la nómina de la empresa</p>
            <div class="cabecera_acciones">
      					<a class="btn-ejornal btn-ejornal-gris-claro" href="{{url('empleados/ausentismos')}}"><i class="fas fa-arrow-alt-circle-left"></i>Volver</a>
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
            <h4>Ausentismo</h4>
            <form action="{{action('EmpleadosAusentismosController@store')}}" accept-charset="UTF-8" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Trabajador</label>
                    <select required name="trabajador" class="form-control form-control-sm select_2">
                        @foreach ($trabajadores as $trabajador)
                        <option value="{{$trabajador->id}}">{{$trabajador->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>
                        Tipo
                        @if (auth()->user()->permiso_desplegables == 1)
                          <a style="color: #6f9eab; margin-right: 10px;" data-toggle="modal" data-target="#crear_tipo_ausentismo" href="#">
                            <i class="fas fa-plus-circle"></i>
                          </a>
                          <a style="color: #6f9eab;" data-toggle="modal" data-target="#ver_tipo_ausentismo" href="#">
                            <i class="fas fa-eye"></i>
                          </a>
                        @endif
                    </label>
                    <select name="tipo" class="form-control form-control-sm select_2">
                        @foreach ($ausentismo_tipos as $tipo)
                        <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Fecha inicio</label>
                    <input readonly required id="ausentismo_fecha_inicio" name="fecha_inicio" type="datetime" class="form-control" value="{{ old("fecha_inicio") }}">
                </div>
                <div class="form-group col-md-3">
                    <label>Fecha final</label>
                    <input readonly id="ausentismo_fecha_final" name="fecha_final" type="datetime" class="form-control" value="{{ old("fecha_final") }}">
                </div>
                <div class="form-group col-md-3">
                    <label>Fecha en que regresó</label>
                    <input readonly id="ausentismo_fecha_regreso" name="fecha_regreso_trabajar" type="datetime" class="form-control" value="{{ old("fecha_regreso") }}">
                </div>
                <div class="form-group col-md-3">
                    <label >Documentación</label>
                    <input name="archivo" type="file" class="form-control-file">
                </div>
            </div>
            <hr>
            <h4>Comunicación</h4>
            <div class="form-row">
              <div class="form-group col-md-3">
                  <label>
                      Tipo
                      <a style="color: #6f9eab; margin-right: 10px;" data-toggle="modal" data-target="#crear_tipo_comunicacion" href="#">
                          <i class="fas fa-plus-circle"></i>
                      </a>
                      <a style="color: #6f9eab;" data-toggle="modal" data-target="#ver_tipo_comunicacion" href="#">
                          <i class="fas fa-eye"></i>
                      </a>
                  </label>
                  <select name="tipo_comunicacion" class="form-control form-control-sm select_2">
                      @foreach ($tipo_comunicacion as $tipo_com)
                      <option value="{{$tipo_com->id}}">{{$tipo_com->nombre}}</option>
                      @endforeach
                  </select>
              </div>
                <div class="form-group col-md-9">
                    <label>Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Cargar ausencia y comunicación</button>
            </form>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>





<!-- Modal Crear tipo ausentismo -->
<div class="modal fade" id="crear_tipo_ausentismo" tabindex="-1" aria-labelledby="tipo_ausentismo_titulo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tipo_ausentismo_titulo">Crear tipo de Ausencia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">
                        <form action="{{action('EmpleadosAusentismosController@tipo')}}" accept-charset="UTF-8" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label>Nombre</label>
                            <input name="nombre" type="text" class="form-control form-control-sm" placeholder="">
                        </div>
                        <button type="submit" class="btn-ejornal btn-ejornal-success">Crear tipo</button>
                        <button type="button" class="btn-ejornal btn-ejornal-gris-claro" data-dismiss="modal">Cerrar</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



<!-- Modal Ver tipo Ausentismo -->
<div class="modal fade" id="ver_tipo_ausentismo" tabindex="-1" aria-labelledby="ver_tipo_ausentismo_titulo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ver_tipo_ausentismo_titulo">Tipos de Ausencia cargados</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">


                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-hover table-sm ">
                            <thead>
                                <tr>
                                    <th class="th-lg">
                                        <a>
                                            Tipo
                                            <i class="fas fa-sort ml-1"></i>
                                        </a>
                                    </th>
                                    <th class="th-lg">
                                        <a href="">
                                            Acciones
                                            <i class="fas fa-sort ml-1"></i>
                                        </a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ausentismo_tipos as $tipo)
                                <tr>
                                    <td>{{$tipo->nombre}}</td>
                                    <td class="acciones_tabla" scope="row">
                                        <form class="" action="{{route('ausentismos.tipo_delete', $tipo->id)}}" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button title="Eliminar" type="submit">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>




<!-- Modal Crear tipo comunicacion -->
<div class="modal fade" id="crear_tipo_comunicacion" tabindex="-1" aria-labelledby="tipo_comunicacion_titulo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tipo_comunicacion_titulo">Crear tipo de Comunicación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">
                        <form action="{{action('EmpleadosComunicacionesController@tipo')}}" accept-charset="UTF-8" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label>Nombre</label>
                            <input name="nombre" type="text" class="form-control form-control-sm" placeholder="">
                        </div>
                        <button type="submit" class="btn-ejornal btn-ejornal-success">Crear tipo</button>
                        <button type="button" class="btn-ejornal btn-ejornal-gris-claro" data-dismiss="modal">Cerrar</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>





<!-- Modal Ver tipo Comunicacion -->
<div class="modal fade" id="ver_tipo_comunicacion" tabindex="-1" aria-labelledby="ver_tipo_comunicacion_titulo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ver_tipo_comunicacion_titulo">Tipos de Comunicación cargados</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">


                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-hover table-sm ">
                            <thead>
                                <tr>
                                    <th class="th-lg">
                                        <a>
                                            Tipo
                                            <i class="fas fa-sort ml-1"></i>
                                        </a>
                                    </th>
                                    <th class="th-lg">
                                        <a href="">
                                            Acciones
                                            <i class="fas fa-sort ml-1"></i>
                                        </a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tipo_comunicacion as $value)
                                <tr>
                                    <td>{{$value->nombre}}</td>
                                    <td class="acciones_tabla" scope="row">
                                        <form class="" action="{{route('comunicaciones.tipo_delete', $value->id)}}" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button title="Eliminar" type="submit">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


@endsection


<script type="text/javascript">

window.addEventListener("load", function(event) {

    $('.select_2').select2();

});

</script>
