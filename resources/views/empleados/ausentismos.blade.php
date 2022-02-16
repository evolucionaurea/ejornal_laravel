@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')

        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Listado de ausentismos</h2>
            <p>Aquí puede ver el listado de ausentismos de la empresa</p>
            @if (auth()->user()->fichada == 1)
              <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-base" href="{{route('ausentismos.create')}}"><i class="fas fa-plus-circle"></i> Nuevo ausentismo</a>
              </div>
            @endif
        </div>

         @include('../mensajes_validacion')

        <div class="tarjeta">
          {{-- Falta ajustar el responsive de los filtros, las acciones de copiar y demas y el buscar --}}
          <div class="d-flex justify-content-between row">
              <h4 class="col-md-2 col-lg-5">Ausentismos</h4>
              <div class="col-md-10 col-lg-7 d-flex">
                  <input placeholder="Desde" id="reporte_ausentismos_desde" name="fecha_inicio" type="datetime" class="form-control form-control-sm mr-2 w-25 p-3" value="">
                  <input placeholder="Hasta" id="reporte_ausentismos_hasta" name="fecha_final" type="datetime" class="form-control form-control-sm mr-2 w-25 p-3" value="">
                  <a style="height: 35px; padding-top: 6px;" id="reporte_ausentismo_filtro" class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-search"></i> Buscar</a>
                  <a style="height: 35px; padding-top: 6px;" id="reporte_ausentismo_todo" class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-list"></i> Mostrar todo</a>
              </div>
          </div>
            <table class="table table-striped table-hover table-sm tabla_user">

                <!--Table head-->
                <thead>
                    <tr>
                        <th class="th-lg">
                            <a>
                                Trabajador
                                <i class="fas fa-sort ml-1"></i>
                            </a>
                        </th>
                        <th class="th-lg">
                            <a>
                                DNI
                                <i class="fas fa-sort ml-1"></i>
                            </a>
                        </th>
                        <th class="th-lg">
                            <a>
                                Sector
                                <i class="fas fa-sort ml-1"></i>
                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Tipo
                                <i class="fas fa-sort ml-1"></i>
                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Fecha inicio
                                <i class="fas fa-sort ml-1"></i>
                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Fecha final
                                <i class="fas fa-sort ml-1"></i>
                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Fecha en que regresó
                                <i class="fas fa-sort ml-1"></i>
                            </a>
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
                  @foreach ($ausentismos as $ausentismo)
                    <tr>
                      <td>{{$ausentismo->nombre}}</td>
                      <td>{{$ausentismo->dni}}</td>
                      <td>{{$ausentismo->sector}}</td>
                      <td>{{$ausentismo->nombre_ausentismo}}</td>
                      <td>{{ (!empty($ausentismo->fecha_inicio)) ? date('d/m/Y',strtotime($ausentismo->fecha_inicio)) : "" }}</td>
                      <td>{{ (!empty($ausentismo->fecha_final)) ? date('d/m/Y',strtotime($ausentismo->fecha_final)) : "" }}</td>
                      <td>{{ (!empty($ausentismo->fecha_regreso_trabajar)) ? date('d/m/Y',strtotime($ausentismo->fecha_regreso_trabajar)) : "" }}</td>
                      @if (auth()->user()->fichada == 1)
                        <td class="acciones_tabla" scope="row">
                          <a title="Comunicaciones" href="{{route('comunicaciones.show', $ausentismo->id)}}">
                            <i title="Comunicaciones" class="fas fa-bullhorn"></i>
                          </a>
                          <a title="Documentacion" href="{{route('documentaciones.show', $ausentismo->id)}}">
                            <i title="Documentacion" class="fas fa-files-medical"></i>
                          </a>
                          <a title="Historial" href="{{route('ausentismos.show', $ausentismo->id_trabajador)}}">
                            <i title="Historial" class="fas fa-book"></i>
                          </a>
                          <a title="Editar" href="{{route('ausentismos.edit', $ausentismo->id)}}">
                            <i title="Editar" class="fas fa-pen"></i>
                          </a>
                          <form class="" action="{{route('ausentismos.destroy', $ausentismo->id)}}" method="post">
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


<script type="text/javascript">

  window.onload = function() {
    let ausentismos = [];

    // Ahora los datos se muestran por php. Deben mostrarse por javascript y sumar filtro por fecha
    // El codigo de abajo ya trae los resultados a mostrar. Falta renderizarlos en el html y poner el
    // filtro por fecha
    axios.get('/empleados/getAusentismos')
      .then(response => {
          ausentismos = response.data;
          console.log(ausentismos);
      })
      .catch(e => {
          // Podemos mostrar los errores en la consola
          console.log(e);
      })

  };

</script>

@endsection
