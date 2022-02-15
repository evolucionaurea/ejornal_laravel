@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_admin')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Listado de fichadas</h2>
            <p>Aquí puede ver el listado de los fichadas de todos sus empleados</p>
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
            <table class="table table-striped table-hover table-sm tabla_admin_user_fichadas">
                <thead>
                    <tr>
                        <th class="th-lg">
                            <a>
                                Nombre
                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Cliente
                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Ingreso / Egreso
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($fichadas as $fichada)
                    <tr>
                        <td>{{$fichada['user']}}</td>
                        <td>{{$fichada['cliente']}}</td>
                        <td>
                          {{$fichada['ingreso']}}
                          hasta al
                          @if ($fichada['egreso'] == null)
                              {{'Aún trabajando'}}
                            @else
                              {{$fichada['egreso']}}
                          @endif
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




<script type="text/javascript">

$(document).ready(function () {

 $('.tabla_admin_user_fichadas').DataTable({
  scrollY: 400,
  dom: 'Bfrtip',
  order: [[ 2, "desc" ]],
  buttons: [{
      extend: 'copy',
      text: 'Copiar',
    },
    {
      extend: 'excel',
      title: 'eJornal',
    },
    {
      extend: 'pdf',
      text: 'PDF',
      title: 'eJornal',
    },
    {
      extend: 'print',
      text: 'Imprimir',
      title: 'eJornal',
    }
  ],
  responsive: true,
  "language": {
    "ordering": false,
    "sProcessing": "Procesando...",
    "sLengthMenu": "Mostrar _MENU_ registros",
    "sZeroRecords": "No se encontraron resultados",
    "sEmptyTable": "Ningún dato disponible en esta tabla",
    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix": "",
    "sSearch": "Buscar:",
    "sUrl": "",
    "sInfoThousands": ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
      "sFirst": "Primero",
      "sLast": "Último",
      "sNext": "Siguiente",
      "sPrevious": "Anterior"
    },
    "oAria": {
      "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
      "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
  }

});



});

</script>

@endsection
