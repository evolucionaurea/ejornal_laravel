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