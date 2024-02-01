@extends('partials.layout')

@section('title', 'Error 404')

@section('content')

<section class="d-flex align-items-center justify-content-center" style="height:100vh;">

  <div class="text-center px-md-5 mx-md-5 dark-grey-text z-depth-1 py-5">
    <div class="mb-4 error_404">
      <i class="fas fa-exclamation-triangle"></i>
    </div>
    <h3 class="font-weight-bold mb-4 pb-2">Error 404</h3>
    <p class="text-center mx-auto mb-4 pb-2">
      Página no encontrada o sin permiso.
    </p>
    <a class="btn-ejornal btn-ejornal-base" href="{{url('/')}}">Volver al login</a>
  </div>

</section>

@endsection
