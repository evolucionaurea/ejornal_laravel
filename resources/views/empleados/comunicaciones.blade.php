@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')

        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Listado de comunicaciones</h2>
            <p>Aquí puede ver el listado de comunicaciones de la empresa</p>
            {{-- @if (auth()->user()->fichada == 1)
            <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-base" href="{{route('comunicaciones.create')}}"><i class="fas fa-plus-circle"></i> Nueva comunicación</a>
            </div>
            @endif --}}
        </div>

        @include('../mensajes_validacion')

        <div class="tarjeta">
            <table class="table table-striped table-hover table-sm tabla_comunicaciones_listado">

                <!--Table head-->
                <thead>
                    <tr>
                        <th class="th-lg">
                                Trabajador
                        </th>
                        <th class="th-lg">
                                Email
                        </th>
                        <th class="th-lg">
                                Tipo
                        </th>
                        <th class="th-lg">
                                Cargado
                        </th>
                        <th class="th-lg">
                                Estado
                        </th>
                        {{-- @if (auth()->user()->fichada == 1)
                        <th class="th-lg">
                            <a href="">
                                Acciones
                                <i class="fas fa-sort ml-1"></i>
                            </a>
                        </th>
                        @endif --}}
                    </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody>
                    @foreach ($comunicaciones as $comunicacion)
                    <tr>
                        <td>{{$comunicacion->nombre}}</td>
                        <td>{{$comunicacion->email}}</td>
                        <td>{{$comunicacion->tipo}}</td>
                        <td>
                          {{-- {{ (!empty($comunicacion->created_at)) ? date('d/m/Y',strtotime($comunicacion->created_at)) : "" }} --}}
                          {{ (!empty($comunicacion->created_at)) ? $comunicacion->created_at : "" }}
                        </td>
                        <td>
                          @if ($comunicacion->estado == 1)
                              Activo
                            @else
                              Inactivo
                          @endif
                        </td>
                        {{-- @if (auth()->user()->fichada == 1)
                        <td class="acciones_tabla" scope="row">
                            <a title="Editar" href="{{route('comunicaciones.edit', $comunicacion->id)}}">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form class="" action="{{route('comunicaciones.destroy', $comunicacion->id)}}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE">
                                <button title="Eliminar" type="submit">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                        @endif --}}
                    </tr>
                    @endforeach
                </tbody>
                <!--Table body-->
            </table>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>



<script type="text/javascript">

$(document).ready(function () {

 $('.tabla_comunicaciones_listado').DataTable({
  scrollY: 400,
  dom: 'Bfrtip',
  order: [[ 3, "desc" ]],
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
