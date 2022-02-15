@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Creación de vacunas</h2>
            <p>Aquí puedes cargar los vacunas pertinentes a la empresa en la que estas trabajando</p>
        </div>

        @include('../../../mensajes_validacion')
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
            <form action="{{action('EmpleadosCovidVacunasController@store')}}" accept-charset="UTF-8" method="post">
            @csrf
            <div class="form-row">
              <div class="form-group col-md-3">
                  <label>Trabajador:</label>
                  <select name="nomina" class="form-control form-control-sm select_2">
                  @foreach ($nominas as $nomina)
                      <option value="{{$nomina->id}}">{{$nomina->nombre}}</option>
                  @endforeach
                </select>
              </div>
                <div class="form-group col-md-3">
                  <label>
                    Dosis:
                    @if (auth()->user()->permiso_desplegables == 1)
                      <a style="color: #6f9eab; margin-right: 10px;" data-toggle="modal" data-target="#crear_tipo_dosis" href="#">
                        <i class="fas fa-plus-circle"></i>
                      </a>
                      <a style="color: #6f9eab;" data-toggle="modal" data-target="#ver_tipo_dosis" href="#">
                        <i class="fas fa-eye"></i>
                      </a>
                    @endif
                  </label>
                  <select name="tipo" class="form-control form-control-sm select_2">
                  @foreach ($covid_vacunas_tipo as $tipo)
                      <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                  @endforeach
                </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Fecha</label>
                    <input id="data_picker_gral" name="fecha" type="datetime" class="form-control" value="{{ old("fecha") }}">
                </div>
                <div class="form-group col-md-3">
                    <label>Institución</label>
                    <input name="institucion" type="text" class="form-control" value="{{ old("institucion") }}">
                </div>
            </div>
            <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Guardar vacuna</button>
            </form>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>

@endsection





<!-- Modal Crear tipo covid dosis -->
<div class="modal fade" id="crear_tipo_dosis" tabindex="-1" aria-labelledby="tipo_dosis_titulo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tipo_dosis_titulo">Crear tipo de Dosis</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">
                        <form action="{{action('EmpleadosCovidVacunasController@tipo')}}" accept-charset="UTF-8" method="post">
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



<!-- Modal Ver tipo Dosis covid -->
<div class="modal fade" id="ver_tipo_dosis" tabindex="-1" aria-labelledby="ver_tipo_dosis_titulo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ver_tipo_dosis_titulo">Tipos de Dosis cargados</h5>
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
                                @foreach ($covid_vacunas_tipo as $tipo)
                                <tr>
                                    <td>{{$tipo->nombre}}</td>
                                    <td class="acciones_tabla" scope="row">
                                        <form class="" action="{{route('covid.vacunas.tipo_delete', $tipo->id)}}" method="post">
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


<script type="text/javascript">

window.addEventListener("load", function(event) {

    $('.select_2').select2();

});

</script>
