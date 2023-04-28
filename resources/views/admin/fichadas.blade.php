@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_admin')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Listado de fichadas</h2>
			<p>Aquí puede ver el listado de los fichadas de todos sus empleados</p>
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

		<div class="tarjeta">
			<table class="table table-striped table-hover table-sm tabla_admin_user_fichadas">
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Cliente</th>
						<th>Ingreso</th>
						<th>Egreso</th>
						<th>Tiempo</th>
					</tr>
				</thead>
				<tbody>
					{{-- Carga por Ajax --}}
				</tbody>
			</table>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>


@endsection
