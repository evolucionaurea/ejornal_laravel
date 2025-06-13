@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Todas las consultas</h2>
			<p>Aquí puede ver el listado de todas las consultas médicas y de enfermería</p>
		</div>

		@include('../../mensajes_validacion')

		<div class="tarjeta">


			@include('../../modulos/busqueda_consultas')


			<table data-table="consultas" class="table table-striped table-hover table-sm">

				<!--Table head-->
				<thead>
					<tr>
						<th>ID</th>
						<th>Consulta</th>
						<th>Trabajador</th>
						<th>Estado</th>
						<th>Legajo</th>
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