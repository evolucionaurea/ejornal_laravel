@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_admin')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Agenda</h2>
            <p>Aquí puedes ver las agendas de los usuarios del sistema</p>
        </div>

        @include('../mensajes_validacion')
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

        <div class="row">

            <div class="col-3">
                <div class="tarjeta">
                    {{-- IZQUIERDA: bloqueos por usuario+cliente --}}
                    <div class="form-group">
                        <label><b>Usuario</b></label>
                        <select id="blk-user" class="form-control">
                            <option value="">Selecciona usuario</option>
                            @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->nombre }} {{ $u->apellido }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label><b>Cliente</b></label>
                        <select id="blk-cliente" class="form-control">
                            <option value="">Selecciona cliente</option>
                            @foreach($clientes as $c)
                            <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <h6 class="mt-4"><b>Restricciones Horarias</b></h6>
                    {{-- Ocultamos hasta que el usuario+cliente estén seleccionados --}}
                    <div id="restricciones-container" style="display:none;">
                        @foreach(['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'] as $i=>$dia)
                        <div class="card mb-2" data-dia="{{ $i }}">
                            <div class="card-header d-flex justify-content-between align-items-center py-2">
                                <strong>{{ $dia }}</strong>
                                <button class="btn btn-link btn-sm add-bloque">
                                    <i class="fas fa-plus-circle"></i> Agregar
                                </button>
                            </div>
                            <div class="card-body p-2 bloques">
                                {{-- Se rellenará vía JS --}}
                            </div>
                            <div class="card-footer text-right">
                                <button class="btn-ejornal btn-ejornal-base btn-guardar" data-dia="{{ $i }}">
                                    Guardar
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-9">
                <div class="tarjeta">

                    {{-- filtros calendario --}}
                    <div class="row mb-3">
                        <div class="col-3">
                            <select id="cal-user" class="form-control">
                                <option value="">Todos los usuarios</option>
                                @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->nombre }} {{ $u->apellido }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <select id="cal-cliente" class="form-control">
                                <option value="">Todos los clientes</option>
                                @foreach($clientes as $c)
                                <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- DERECHA: calendario --}}
                    <div id="calendar"></div>
                </div>
            </div>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>

@endsection