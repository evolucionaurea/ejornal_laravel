@extends('partials.layout')

@section('title', 'Admin')

@section('content')


<div class="d-flex" id="wrapper">
	@include('partials.sidebar_admin')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

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

				<!--Table head-->
				<thead>
					<tr>
						<th>Empleado</th>
						<th>Empresa</th>
						<th>Ingreso</th>
						<th>Egreso</th>
						<th>Tiempo trabajado</th>
						<th>IP</th>
					</tr>
				</thead>
				<!--Table head-->

				<!--Table body-->
				<tbody class="resultados_reporte_fichadas">
					{{-- Se llena por JS --}}
				</tbody>
				<!--Table body-->
			</table>
		</div>


		{{-- Contenido de la pagina --}}
	</div>
</div>

@endsection
