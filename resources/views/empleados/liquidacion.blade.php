@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')

        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Liquidación</h2>
            <p>Aquí puede su liquidación</p>
        </div>

         @include('../mensajes_validacion')

        <div class="tarjeta">
          <div class="row">
            <div class="col-md-6">
              <h4>Datos personales:</h4>
              <ul class="liquidacion_datos_personales">
                <li>{{auth()->user()->nombre}}</li>
                <li>{{auth()->user()->email}}</li>
              </ul>
            </div>
            <div class="col-md-6">
              <h4>Liquidación OneDrive</h4>
              @if (auth()->user()->onedrive != null)
                <a href="{!!auth()->user()->onedrive!!}" target="_blank">
                  <img class="img_onedrive" src="{{asset('img/empleados/logo_onedrive.png')}}" alt="">
                </a>
                @else
                  <p>Aún no fue cargado</p>
              @endif
            </div>
          </div>
        </div>

        {{-- Contenido de la pagina --}}
    </div>
</div>

@endsection
