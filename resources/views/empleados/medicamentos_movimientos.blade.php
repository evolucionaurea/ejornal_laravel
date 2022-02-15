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
            <p>Aquí puede ver el movimiento de cada cambio que fue sucediendo en los medicamentos</p>
        </div>

         @include('../mensajes_validacion')

        <div class="tarjeta">
            <table class="table table-striped table-hover table-sm tabla_user">

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
                        {{-- <th class="th-lg">
                            <a href="">
                                Stock
                                <i class="fas fa-sort ml-1"></i>
                            </a>
                        </th> --}}
                        <th class="th-lg">
                            Motivo
                        </th>
                        <th class="th-lg">
                                Fecha ingreso
                        </th>
                        <th class="th-lg">
                                Registro creado
                        </th>
                    </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody>
                  @foreach ($medicamentos as $medicamento)
                    <tr>
                      <td>{{$medicamento->nombre}}</td>
                      <td>{{ ($medicamento->ingreso != 0 && $medicamento->ingreso != null) ? $medicamento->ingreso : "" }}</td>
                      <td>{{ ($medicamento->suministrados != 0 && $medicamento->suministrados != null) ? $medicamento->suministrados : "" }}</td>
                      <td>{{ ($medicamento->egreso != 0 && $medicamento->egreso != null) ? $medicamento->egreso : "" }}</td>
                      {{-- <td>{{ ($medicamento->stock != 0 && $medicamento->stock != null) ? $medicamento->stock : "" }}</td> --}}
                      <td>{{$medicamento->motivo}}</td>
                      <td>{{ (!empty($medicamento->fecha_ingreso)) ? date('d/m/Y',strtotime($medicamento->fecha_ingreso)) : "" }}</td>
                      <td>{{$medicamento->created_at}}</td>
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
