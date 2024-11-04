@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_admin')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		<div class="cabecera">
			<h2>Reportes</h2>
			<p>Aquí puedes ver los reportes de las fichadas.</p>
			@if (auth()->user()->permiso_edicion_fichada == 1)
			<div class="alert alert-success">
				Tienes permiso para editar las fichadas.
				<b>Ten en cuenta que se realizan muchas validaciones y por tanto la seccion demora en cargar</b>
			</div>
			@else
			<div class="alert alert-danger">
				No tienes permiso para editar las fichadas
			</div>
			@endif
			<input id="permiso_edicion_fichada" type="hidden" name="permiso_edicion_fichada"
				value="{{ auth()->user()->permiso_edicion_fichada }}">
			<input id="id_loggeado" type="hidden" name="id_loggeado" value="{{ auth()->user()->id }}">
		</div>

		@include('../mensajes_validacion')

		<div class="tarjeta">
			@php
			$export = true;
			@endphp
			@include('../../modulos.busqueda_fecha')
			<table data-table="fichadas" class="table table-striped table-hover table-sm">
				<thead>
					<tr>
						<th>Empleado</th>
						<th>Estado</th>
						<th>Especialidad</th>
						<th>Empresa</th>

						<th>Ingreso</th>
						<th>Egreso</th>

						<th>Tiempo trabajado</th>

						<th>Sistema/Browser</th>
						<th>IP</th>
					</tr>
				</thead>
				<tbody>
					{{-- Se llena por JS --}}
				</tbody>
			</table>
		</div>
	</div>
</div>

@endsection