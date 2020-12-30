@extends('partials.layout')

@section('title', 'Error404')

@section('content')

<div class="container my-5 py-5 z-depth-1">
  <section class="text-center px-md-5 mx-md-5 dark-grey-text">
    <div class="mb-4 robot_error_404">
      <img style="width: 200px;" src="{{asset('img/personaje_robot.png')}}"  alt="smaple image">
    </div>
    <h3 class="font-weight-bold mb-4 pb-2">Error 404</h3>
    <p class="text-center mx-auto mb-4 pb-2">
      Página no encontrada o sin permiso.
    </p>
    <a class="btn btn-success btn-rounded" href="{{url('/login')}}">Volver al login</a>
  </section>
</div>

@endsection
