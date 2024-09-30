@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Historial de ausencias</h2>
			<p>Aquí puedes ver el historial de ausencias del trabajador seleccionado</p>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-gris-claro"
					href="{{ url('empleados/ausentismos') }}?{{$_SERVER['QUERY_STRING']}}"><i
						class="fas fa-arrow-circle-left"></i>Volver</a>
			</div>
		</div>

		@include('../../mensajes_validacion')

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

	</div>
</div>


@endsection