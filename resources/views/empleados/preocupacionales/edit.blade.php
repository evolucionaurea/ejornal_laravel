@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Edición del estudio medico complementario</h2>
            <p>Aquí podrá editar el estudio medico complementario</p>
            <div class="cabecera_acciones">
      					<a class="btn-ejornal btn-ejornal-gris-claro" href="{{url('empleados/preocupacionales')}}"><i class="fas fa-arrow-alt-circle-left"></i>Volver</a>
      			</div>
        </div>

        @include('../../mensajes_validacion')
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
            <form action="{{action('EmpleadosPreocupacionalesController@update', $preocupacional->id)}}" accept-charset="UTF-8" method="post">
                {{ csrf_field() }}
                <input name="_method" type="hidden" value="PUT">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Trabajador</label>
                        <input disabled name="trabajador" type="text" class="form-control form-control-sm" value="{{$preocupacional->nombre}}" placeholder="">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Fecha</label>
                        <input id="ausentismo_fecha_inicio" name="fecha" type="datetime" class="form-control"
                        value="{{ (!empty($preocupacional->fecha)) ? date('d/m/Y',strtotime($preocupacional->fecha)) : "" }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="3">{{$preocupacional->observaciones}}</textarea>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Documentación</label>
                        <br>
                        <a class="btn-ejornal btn-ejornal-gris-claro" href="{{route('preocupacionales.archivo', $preocupacional->id)}}">
                          <i class="fa fa-file"></i>{{$preocupacional->archivo}}
                        </a>
                    </div>
                    <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Guardar cambios</button>
                </div>
            </form>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>

@endsection
