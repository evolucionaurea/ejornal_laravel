@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_admin')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Listado de medicamentos</h2>
            <p>Aquí puede ver el listado de los medicamentos del sistema</p>
            <div class="cabecera_acciones">
                <a data-toggle="modal" data-target="#crear_medicamento" class="btn-ejornal btn-ejornal-base" href="#"><i class="fas fa-plus-circle"></i>Nuevo medicamento</a>
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
                                Acciones
                                <i class="fas fa-sort ml-1"></i>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($medicamentos as $medicamento)
                    <tr>
                        <td>{{$medicamento->nombre}}</td>
                        <td class="acciones_tabla" scope="row">
                            <a title="Editar" href="{{url('admin/medicamentos/'. $medicamento->id .'/edit')}}">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form class="" action="{{url('admin/medicamentos/', $medicamento->id)}}" method="post">
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

        {{-- Contenido de la pagina --}}
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="crear_medicamento" tabindex="-1" aria-labelledby="cargar_medicamento_titulo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cargar_medicamento_titulo">Nuevo medicamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{action('AdminMedicamentosController@store')}}" enctype="multipart/form-data" accept-charset="UTF-8" method="post">
                  {{ csrf_field() }}
                  <div class="form-group">
                      <label >Nombre del medicamento</label>
                      <input name="nombre" required type="text" class="form-control form-control-sm" placeholder="">
                  </div>
                  <button type="submit" class="btn-ejornal btn-ejornal-base">Crear medicamento</button>
                  <button type="button" class="btn-ejornal btn-ejornal-gris-claro" data-dismiss="modal">Cerrar</button>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection
