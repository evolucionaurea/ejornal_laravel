@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')

        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Movimiento de medicamentos</h2>
            <p>Aquí puede ver el movimiento de medicamentos de la empresa</p>
            @if (auth()->user()->fichada == 1)
              <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-base" href="{{route('medicamentos.create')}}"><i class="fas fa-plus-circle"></i> Nuevo movimiento</a>
              </div>
            @endif
        </div>

         @include('../mensajes_validacion')

        <div class="tarjeta">
            <table class="table table-striped table-hover table-sm tabla_movimientos_empleado_listado  table-bordered dt-responsive nowrap" style="width:100%">

                <!--Table head-->
                <thead>
                    <tr>
                        <th class="th-lg">
                                Nombre
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
                                Fecha ingreso
                        </th>
                        <th class="th-lg">
                                Motivo
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
                  @foreach ($medicamentos as $medicamento)
                    <tr>
                      <td>{{$medicamento->nombre}}</td>
                      <td>{{$medicamento->ingreso}}</td>
                      <td>{{$medicamento->suministrados}}</td>
                      <td>{{$medicamento->egreso}}</td>
                      <td>{{$medicamento->stock}}</td>
                      {{-- <td>{{ (!empty($medicamento->fecha_ingreso)) ? date('d/m/Y',strtotime($medicamento->fecha_ingreso)) : "" }}</td> --}}
                      <td>{{ (!empty($medicamento->fecha_ingreso)) ? $medicamento->fecha_ingreso : "" }}</td>
                      <td>{{$medicamento->motivo}}</td>
                      @if (auth()->user()->fichada == 1)
                        <td class="acciones_tabla" scope="row">
                          <a class="editar_stock_medicamentos" title="Editar" data-toggle="modal" data-target="#editar_stock_medicamentos" href="#" data-info="{{$medicamento->id}}">
                            <i class="fas fa-minus"></i>
                          </a>
                          {{-- <form class="" action="{{route('medicamentos.destroy', $medicamento->id)}}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="DELETE">
                            <button title="Eliminar" type="submit">
                              <i class="fas fa-trash"></i>
                            </button>
                          </form> --}}
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



<!-- Modal Editar Stock medicamentos -->
<div class="modal fade" id="editar_stock_medicamentos" tabindex="-1" aria-labelledby="editar_stock_medicamentos_titulo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editar_stock_medicamentos_titulo">Egreso del medicamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">
                        <form class="form_editar_stock_medicamentos" action="" accept-charset="UTF-8" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PUT">
                        <div class="form-group">
                            <ul class="list-group list-group-flush">
                              <li class="list-group-item">
                                Si hubo alguna pérdida de este medicamento, ruptura o se retiró del stock disponible por algún motivo
                                debe cargarlo aquí.
                              </li>
                              <li class="list-group-item">
                                Si usted a suministrado un medicamento a un paciente no debe cargarlo aquí. Los medicamentos
                                suministrados se descuentan del stock automáticamente.
                              </li>
                            </ul>
                            <br>
                            <label>Egreso</label>
                            <input name="egreso" type="text" class="form-control form-control-sm" placeholder="">
                        </div>
                        <button type="submit" class="btn-ejornal btn-ejornal-success">Guardar cambios</button>
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

 $('.tabla_movimientos_empleado_listado').DataTable({
  scrollY: 400,
  dom: 'Bfrtip',
  order: [[ 5, "desc" ]],
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
