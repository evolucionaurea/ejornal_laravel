@extends('partials.layout')

@section('title', 'Admin')

@section('content')

  <div class="d-flex" id="wrapper">
      @include('partials.sidebar_admin')
      <div id="page-content-wrapper">
          @include('partials.nav_sup')


          {{-- Contenido de la pagina --}}

          <h1>Admin del sistema</h1>

          {{-- Contenido de la pagina --}}
      </div>
  </div>

@endsection
