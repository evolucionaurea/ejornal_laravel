@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_admin')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Movimiento de medicamentos</h2>
            <p>Aquí puede ver el listado de todos los pedidos y stock de medicamentos que han realizado los empleados de eJornal en cada cliente</p>
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
            <table class="table table-striped table-hover table-sm tabla">
                <thead>
                    <tr>
                        <th class="th-lg">
                                Medicamento
                        </th>
                        <th class="th-lg">
                                Cliente
                        </th>
                        <th class="th-lg">
                                User
                        </th>
                        <th class="th-lg">
                                Ingreso
                        </th>
                        <th class="th-lg">
                                Suministrados
                        </th>
                        <th class="th-lg">
                                Egreso
                        </th>
                        <th class="th-lg">
                            Stock
                        </th>
                        <th class="th-lg">
                                Fecha de ingreso
                        </th>
                        <th class="th-lg">
                                Motivo
                        </th>
                        <th class="th-lg">
                                Creado
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stock_medicamentos as $stock)
                    <tr>
                        <td>{{$stock->nombre}}</td>
                        <td>{{$stock->cliente}}</td>
                        <td>{{$stock->user}}</td>
                        <td>{{ ($stock->ingreso != 0 && $stock->ingreso != null) ? $stock->ingreso : "" }}</td>
                        <td>{{ ($stock->suministrados != 0 && $stock->suministrados != null) ? $stock->suministrados : "" }}</td>
                        <td>{{ ($stock->egreso != 0 && $stock->egreso != null) ? $stock->egreso : "" }}</td>
                        <td>{{$stock->stock}}</td>
                        <td>{{ (!empty($stock->fecha_ingreso)) ? date('d/m/Y',strtotime($stock->fecha_ingreso)) : "" }}</td>
                        <td>{{$stock->motivo}}</td>
                        <td>{{$stock->created_at}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="cargar_clientes_excel" tabindex="-1" aria-labelledby="clientes_excel_titulo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clientes_excel_titulo">Carga masiva</h5>
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
                        <a class="btn-ejornal btn-ejornal-dark" href="{{asset('archivos/clientes_carga_masiva.csv')}}" download target="_blank">Descargar excel</a>
                    </div>
                </div>
                <div class="card mb-4">
                    <h5 class="card-header">Paso 2</h5>
                    <div class="card-body">
                        <h5 class="card-title">Cargar excel de clientes</h5>
                        <form action="{{action('AdminClientesController@cargar_excel')}}" enctype="multipart/form-data" accept-charset="UTF-8" method="post">
                          {{ csrf_field() }}
                          <div class="form-group mb-4">
                            <input name="archivo" type="file" class="form-control-file">
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
