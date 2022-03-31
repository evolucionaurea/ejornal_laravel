@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')

        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Listado de vacunas</h2>
            <p>Aquí puede ver el listado de vacunas de la empresa</p>
            @if (auth()->user()->fichada == 1)
            <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-base" href="{{route('vacunas.create')}}"><i class="fas fa-plus-circle"></i> Nueva vacuna</a>
            </div>
            @endif
        </div>

        @include('../../mensajes_validacion')

        <div class="tarjeta">
            <table class="table table-striped table-hover table-sm tabla_vacunas_listado">

                <!--Table head-->
                <thead>
                    <tr>
                        <th class="th-lg">
                                Nombre
                        </th>
                        <th class="th-lg">
                                Dosis
                        </th>
                        <th class="th-lg">
                                Fecha
                        </th>
                        <th class="th-lg">
                                Institución
                        </th>
                        @if (auth()->user()->fichada == 1)
                        <th class="th-lg">
                            <a href="">
                                Acciones
                                <i class="fas fa-sort ml-1"></i>
                            </a>
                        </th>
                        @endif
                    </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody>
                    @foreach ($vacunas as $vacuna)
                    <tr>
                        <td>{{$vacuna->nombre}}</td>
                        <td>{{$vacuna->tipo}}</td>
                        {{-- <td>{{ (!empty($vacuna->fecha)) ? date('d/m/Y',strtotime($vacuna->fecha)) : "" }}</td> --}}
                        <td>{{ (!empty($vacuna->fecha)) ? $vacuna->fecha : "" }}</td>
                        <td>{{$vacuna->institucion}}</td>
                        @if (auth()->user()->fichada == 1)
                        <td class="acciones_tabla" scope="row">
                            <a title="Editar" href="{{route('vacunas.edit', $vacuna->id)}}">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form class="" action="{{route('vacunas.destroy', $vacuna->id)}}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE">
                                <button title="Eliminar" type="submit">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
                <!--Table body-->
            </table>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>





<!-- Modal -->
<div class="modal fade" id="cargar_nominas_excel" tabindex="-1" aria-labelledby="nominas_excel_titulo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nominas_excel_titulo">Carga masiva</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="card mb-4">
                    <h5 class="card-header">Paso 1</h5>
                    <div class="card-body">
                        <h5 class="card-title">Descargar modelo de excel</h5>
                        <p class="card-text">Descarge este excel modelo. Completelo con los campos solicitados y subalo en el paso 2</p>
                        <a class="btn-ejornal btn-ejornal-dark" href="{{asset('archivos/nominas_carga_masiva.csv')}}" download target="_blank">Descargar excel</a>
                    </div>
                </div>
                <div class="card mb-4">
                    <h5 class="card-header">Paso 2</h5>
                    <div class="card-body">
                        <h5 class="card-title">Cargar excel de clientes</h5>
                        <form action="{{action('EmpleadosNominasController@cargar_excel')}}" enctype="multipart/form-data" accept-charset="UTF-8" method="post">
                        {{ csrf_field() }}
                        <div class="form-group mb-4">
                            <input name="archivo" type="file" class="form-control-file form-control-sm">
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <h6>¿Si durante la carga encontramos trabajadores que ya fueron cargados que desea hacer?</h6>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="coincidencia" id="no_modificar_trabajador_radio" value="2" checked>
                              <label class="form-check-label" for="no_modificar_trabajador_radio">
                                No modificarlo. Dejar el que está actualmente cargado.
                              </label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="coincidencia" id="actualizar_trabajador_radio" value="1">
                              <label class="form-check-label" for="actualizar_trabajador_radio">
                                Actualizar con los datos del presente excel
                              </label>
                            </div>
                          </div>
                        </div>
                        <button type="submit" class="btn-ejornal btn-ejornal-success">Subir excel</button>
                        <button type="button" class="btn-ejornal btn-ejornal-gris-claro" data-dismiss="modal">Cerrar</button>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>



@endsection
