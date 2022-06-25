@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_admin')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Listado de usuarios</h2>
            <p>Aquí puede ver el listado de los usuarios del sistema</p>
            <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-base" href="{{route('users.create')}}"><i class="fas fa-plus-circle"></i> Nuevo usuario</a>
            </div>
        </div>

        @include('../mensajes_validacion')

        @if (!empty($users_sin_empresas))
        <div class="alert alert-danger mt-2 mr-4 ml-4" role="alert">
            <p>Hay {{count($users_sin_empresas)}} empleados sin empresas asignadas</p>
            @foreach ($users_sin_empresas as $value)
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                  {{$value->nombre}} {{$value->email}}
                  <a title="Editar" href="{{route('users.edit', $value->id)}}">
                    <i class="fas fa-pen"></i>
                  </a>
                </li>
            </ul>
            @endforeach
        </div>
        @endif

        <div class="tarjeta">
            <table class="table table-striped table-hover table-sm tabla">

                <!--Table head-->
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
                                Email
                                <i class="fas fa-sort ml-1"></i>
                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Especialidad
                                <i class="fas fa-sort ml-1"></i>
                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Estado
                                <i class="fas fa-sort ml-1"></i>
                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Rol
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
                <!--Table head-->

                <!--Table body-->
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{$user->nombre}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->especialidad}}</td>
                        <td>
                            @if ($user->estado == 1)
                            Activo
                            @else
                            Inactivo
                            @endif
                        </td>
                        <td>{{$user->rol}}</td>
                        <td class="acciones_tabla" scope="row">
                          <a title="Ver" href="{{route('users.show', $user->id)}}">
                              <i class="fas fa-eye"></i>
                          </a>
                            <a title="Editar" href="{{route('users.edit', $user->id)}}">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form class="" action="{{route('users.destroy', $user->id)}}" method="post">
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
                <!--Table body-->
            </table>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>

@endsection
