@extends('partials.layout')

@section('title', 'Empleados')

@section('content')



<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')
        {{-- Contenido de la pagina --}}
        <div class="container-fluid">


            <h1>Liquidacion</h1>


        </div>
        {{-- Contenido de la pagina --}}
    </div>
</div>



@endsection
