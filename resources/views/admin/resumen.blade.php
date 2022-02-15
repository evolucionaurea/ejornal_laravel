@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_admin')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="container">
            <section>
                <div class="row">

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="media white z-depth-1 rounded">
                            <i class="fas fa-briefcase fa-lg blue z-depth-1 p-4 rounded-left text-white mr-3"></i>
                            <div class="media-body p-1">
                                <p class="text-uppercase text-muted mb-1"><small>Clientes</small></p>
                                <h5 class="font-weight-bold mb-0">{{$clientes}}</h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="media white z-depth-1 rounded">
                            <i class="fas fa-user-times fa-lg pink z-depth-1 p-4 rounded-left text-white mr-3"></i>
                            <div class="media-body p-1">
                                <p class="text-uppercase text-muted mb-1"><small>Enfermeros activos</small></p>
                                <h5 class="font-weight-bold mb-0">{{$enfermeros}}</h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="media white z-depth-1 rounded">
                            <i class="fas fa-user-md fa-lg teal z-depth-1 p-4 rounded-left text-white mr-3"></i>
                            <div class="media-body p-1">
                                <p class="text-uppercase text-muted mb-1"><small>Medicos activos</small></p>
                                <h5 class="font-weight-bold mb-0">{{$medicos}}</h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="media white z-depth-1 rounded">
                            <i class="fas fa-download fa-lg pink z-depth-1 p-4 rounded-left text-white mr-3"></i>
                            <div class="media-body p-1">
                                <p class="text-uppercase text-muted mb-1"><small>Enfermeros trabajando</small></p>
                                <h5 class="font-weight-bold mb-0">{{$enfermeros_trabajando}}</h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="media white z-depth-1 rounded">
                            <i class="fas fa-download fa-lg teal z-depth-1 p-4 rounded-left text-white mr-3"></i>
                            <div class="media-body p-1">
                                <p class="text-uppercase text-muted mb-1"><small>Medicos trabajando</small></p>
                                <h5 class="font-weight-bold mb-0">{{$medicos_trabajando}}</h5>
                            </div>
                        </div>
                    </div>

                </div>
            </section>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>

@endsection
