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



@endsection
