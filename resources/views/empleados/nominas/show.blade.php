@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<div class="d-flex align-items-center justify-between">
				<h2 style="margin: 0px;">Historial de </h2>
				@if ($trabajador->foto)
				<div class="foto-perfil"
					style="background-image: url({{ $trabajador->photo_url }}); width: 50px; height: 50px; background-size: cover; background-position: center; border-radius: 50%;">
				</div>
				@else
				<i class="fas fa-user fa-lg ml-4"></i>
				@endif
				<span class="ml-2">{{$trabajador->nombre}}</span>
			</div>
			<p>Aquí podrá ver las consultas y ausentismos del trabajador</p>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-gris-claro"
					href="{{ url('empleados/nominas') }}?{{$_SERVER['QUERY_STRING']}}">
					<i class="fas fa-arrow-circle-left"></i> <span>Volver</span>
				</a>
				<a class="btn-ejornal btn-ejornal-base"
					href="{{ route('empleados.nominas.caratulas.create', ['id_nomina' => $trabajador->id]) }}">
					<i class="fas fa-plus-circle"></i> <span>Nueva caratula</span>
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

		@include('modulos.perfil_trabajador')

		{{-- Contenido de la pagina --}}

	</div>
</div>

@endsection