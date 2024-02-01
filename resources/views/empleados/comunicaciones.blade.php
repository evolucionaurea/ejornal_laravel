@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Listado de comunicaciones</h2>
			<p>Aquí puede ver el listado de comunicaciones de la empresa</p>
			{{-- @if (auth()->user()->fichada == 1)
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-base" href="{{route('comunicaciones.create')}}"><i class="fas fa-plus-circle"></i> Nueva comunicación</a>
			</div>
			@endif --}}
		</div>

		@include('../mensajes_validacion')

		<div class="tarjeta">

			@include('../../modulos.busqueda_fecha')

			<table class="table table-striped table-hover table-sm tabla_comunicaciones_listado">

				<!--Table head-->
				<thead>
					<tr>
						<th>Trabajador</th>
						<th>CUIL</th>
						<th>Tipo</th>
						<th>Cargado</th>
						<th>Estado</th>
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
