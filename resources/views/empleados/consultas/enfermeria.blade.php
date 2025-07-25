@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Listado de consultas de enfermería</h2>
			<p>Aquí puede ver el listado de consultas de enfermería de la empresa</p>
			@if ( (auth()->user()->fichada == 1 || !auth()->user()->fichar) && auth()->user()->id_especialidad == 2)
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-base" href="{{route('enfermeria.create')}}">
					<i class="fas fa-plus-circle"></i> Nueva consulta de enfermería
				</a>
			</div>
			@endif
		</div>

		@include('../../mensajes_validacion')

		<div class="tarjeta">


			@include('../../modulos/busqueda_consultas')


			<table data-table="consultas-enfermeria" class="table table-striped table-hover table-sm">

				<!--Table head-->
				<thead>
					<tr>
						<th>ID</th>
						<th>Trabajador</th>
						<th>Legajo</th>
						<th>Estado</th>
						<th>Fecha</th>
						<th>Derivación</th>
						<th>Cargado por</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<!--Table head-->

				<!--Table body-->
				<tbody></tbody>
				<!--Table body-->
			</table>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>


@endsection