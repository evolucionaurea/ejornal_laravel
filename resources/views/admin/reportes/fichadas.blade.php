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
			<table class="table table-striped table-hover table-sm tabla_reporte_fichadas">
				<thead>
					<tr>
						<th>Empleado</th>
						<th>Estado</th>
						<th>Especialidad</th>
						<th>Empresa</th>
						<th>Ingreso</th>
						<th>Egreso</th>
						<th>Tiempo trabajado</th>
						<th>IP</th>
					</tr>
				</thead>
				<tbody class="resultados_reporte_fichadas">
					{{-- Se llena por JS --}}
				</tbody>
			</table>
		</div>
	</div>
</div>

@endsection