@extends('partials.layout')

@section('title', 'Clientes')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_clientes')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')

        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Listado de ausentismos</h2>
            <p>Aquí puede ver el listado de ausentismos de la empresa</p>
        </div>

         @include('../mensajes_validacion')

        <div class="tarjeta">
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
                    </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody>
                  @foreach ($ausentismos as $ausentismo)
                    <tr>
                      <td>{{$ausentismo->nombre}}</td>
                      <td>{{$ausentismo->nombre_ausentismo}}</td>
                      <td>{{ (!empty($ausentismo->fecha_inicio)) ? date('d/m/Y',strtotime($ausentismo->fecha_inicio)) : "" }}</td>
                      <td>{{ (!empty($ausentismo->fecha_final)) ? date('d/m/Y',strtotime($ausentismo->fecha_final)) : "" }}</td>
                      <td>{{ (!empty($ausentismo->fecha_regreso_trabajar)) ? date('d/m/Y',strtotime($ausentismo->fecha_regreso_trabajar)) : "" }}</td>
                    </tr>
                  @endforeach
                </tbody>
                <!--Table body-->
            </table>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>



@endsection
