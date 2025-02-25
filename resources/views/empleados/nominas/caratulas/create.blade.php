@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Creación de caratulas</h2>
            <p>Crearás una carátula para <b>{{ $trabajador->nombre }}</b></p>
            <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-gris-claro" href="{{ url('empleados/nominas/' . $trabajador->id) }}">
                    <i class="fas fa-arrow-circle-left"></i>Volver
                </a>
            </div>
        </div>


        @include('../../../mensajes_validacion')

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
            <form action="{{action('EmpleadosCaratulaController@store')}}" accept-charset="UTF-8" method="post"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_nomina" value="{{ $trabajador->id }}">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>
                            Patologia
                            <a data-toggle="modal" data-target="#patologiaVerModal" href="#"><i
                                    class="fas fa-eye"></i></a>
                            <a data-toggle="modal" data-target="#patologiaCrearModal" href="#"><i
                                    class="fas fa-plus-circle"></i></a>
                        </label>
                        <select multiple required name="id_patologia[]" class="form-control form-control-sm select_2">
                            @foreach ($patologias as $patologia)
                            <option value="{{ $patologia->id }}">{{ $patologia->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Peso</label>
                        <input name="peso" type="number" class="form-control" value="{{ old(" peso") }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Altura</label>
                        <div class="input-group">
                            <input name="altura" type="number" class="form-control" value="{{ old(" altura") }}">
                            <div class="input-group-append">
                                <span class="input-group-text">cm.</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label>IMC</label>
                        <input disabled name="imc_disabled" type="text" class="form-control" value="{{ old(" imc") }}">
                        <input name="imc" type="hidden" class="form-control" value="{{ old(" imc") }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Medicacion habitual</label>
                        <textarea class="form-control form-control-sm" name="medicacion_habitual" rows="2"
                            cols="80"></textarea>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Antecedentes</label>
                        <textarea class="form-control form-control-sm" name="antecedentes" rows="2"
                            cols="80"></textarea>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Alergias</label>
                        <textarea class="form-control form-control-sm" name="alergias" rows="2" cols="80"></textarea>
                    </div>
                </div>
                <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Guardar Caratula</button>
            </form>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>



<!-- Modal Ver Patologias -->
<div class="modal fade" id="patologiaVerModal" tabindex="-1" aria-labelledby="patologiaVerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="patologiaVerModalLabel">Ver patologia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    @if (count($patologias) > 0)
                    @foreach ($patologias as $patologia)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $patologia->nombre }}
                        <span class="badge badge-danger badge-pill">
                            <form class="" action="{{route('patologias.destroy', $patologia->id)}}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE">
                                <button title="Eliminar" type="submit">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </span>
                    </li>
                    @endforeach
                    @else
                    <p class="alert alert-warning">
                        No hay patologias creadas aun.
                    </p>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>


<!-- Modal Crear Patologias -->
<div class="modal fade" id="patologiaCrearModal" tabindex="-1" aria-labelledby="patologiaCrearModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="patologiaCrearModalLabel">Nueva patologia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{action('EmpleadosPatologiasController@store')}}" accept-charset="UTF-8" method="post">
                    @csrf
                    <div class="form-group">
                        <label>Nombre</label>
                        <input required type="text" class="form-control" placeholder="" name="nombre">
                    </div>
                    <button type="submit" class="btn-ejornal btn-ejornal-base">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection