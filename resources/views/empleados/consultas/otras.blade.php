@extends('partials.layout')

@section('title', 'Empleado - Otras Consultas')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Otras Consultas</h2>
			<p>Aquí puede ver el listado de otras consultas de la empresa.</p>
			@if( (auth()->user()->fichada == 1 || !auth()->user()->fichar) && auth()->user()->id_especialidad == 1)
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-base" href="{{ url('empleados/consultas/otras/create') }}">
					<i class="fas fa-plus-circle"></i>
					Nueva Consulta
				</a>
			</div>
			@else
			<div class="small text-muted"><i>[Debe fichar para crear una nueva consulta]</i></div>
			@endif
		</div>

		@include('../mensajes_validacion')

		<div class="tarjeta">
			@include('../../modulos/busqueda_consultas')

			<table data-table="consultas-otras" class="table table-striped table-sm tabla">
				<thead>
					<tr>
						<th>ID</th>
						<th>Trabajador</th>
						<th>Legajo</th>
						<th>Tipo Profesional</th>
						<th>Fecha</th>
						<th>Cargado por</th>
						<th class="text-right">Acciones</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>

@endsection
