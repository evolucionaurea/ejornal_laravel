@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')

        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Certificados de un ausentismo</h2>
            <p>Aquí puede ver el listado de las documentaciones que carga en cada ausentismo</p>
        </div>

         @include('../mensajes_validacion')

        <div class="tarjeta">
          @if (isset($certificados) && count($certificados) > 0)
            <table class="table table-striped table-hover table-sm tabla_certificados_ausentismo_listado">

              <!--Table head-->
              <thead>
                <tr>
                  <th class="th-lg">
                      Trabajador
                  </th>
                  <th class="th-lg">
                      Medico
                  </th>
                  <th class="th-lg">
                      Institución
                  </th>
                  <th class="th-lg">
                      Fecha inicio
                  </th>
                  <th class="th-lg">
                      Fecha final
                  </th>
                  <th class="th-lg">
                      Fecha en que regresó
                  </th>
                  <th class="th-lg">
                      Matricula nacional
                  </th>
                </tr>
              </thead>
              <!--Table head-->

              <!--Table body-->
              <tbody>
                @foreach ($certificados as $certificado)
                  <tr>
                    <td>{{$certificado->nombre}}</td>
                    <td>{{$certificado->medico}}</td>
                    <td>{{$certificado->institucion}}</td>
                    {{-- <td>{{ (!empty($certificado->fecha_inicio)) ? date('d/m/Y',strtotime($certificado->fecha_inicio)) : "" }}</td>
                    <td>{{ (!empty($certificado->fecha_final)) ? date('d/m/Y',strtotime($certificado->fecha_final)) : "" }}</td>
                    <td>{{ (!empty($certificado->fecha_regreso_trabajar)) ? date('d/m/Y',strtotime($certificado->fecha_regreso_trabajar)) : "" }}</td> --}}
                    <td>{{ (!empty($certificado->fecha_inicio)) ? $certificado->fecha_inicio : "" }}</td>
                    <td>{{ (!empty($certificado->fecha_final)) ? $certificado->fecha_final : "" }}</td>
                    <td>{{ (!empty($certificado->fecha_regreso_trabajar)) ? $certificado->fecha_regreso_trabajar : "" }}</td>
                    <td>{{$certificado->matricula_nacional}}</td>
                  </tr>
                @endforeach
              </tbody>
              <!--Table body-->
            </table>
            @else
              <p>No hay certificados cargados actualmente.</p>
          @endif
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>



<script type="text/javascript">

$(document).ready(function () {

 $('.tabla_certificados_ausentismo_listado').DataTable({
  scrollY: 400,
  dom: 'Bfrtip',
  order: [[ 4, "desc" ]],
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
