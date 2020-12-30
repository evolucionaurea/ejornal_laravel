@extends('partials.layout')

@section('title', 'Cliente')

@section('content')



<div class="d-flex" id="wrapper">
    @include('partials.sidebar_clientes')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')
        {{-- Contenido de la pagina --}}

        <h1>Resumen del usuario Cliente</h1>


        {{-- Contenido de la pagina --}}
    </div>
</div>



@endsection
