@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Movimiento de medicamentos</h2>
			<p>Aquí puede ver el movimiento de cada cambio que fue sucediendo en los medicamentos</p>
		</div>

		 @include('../mensajes_validacion')

		<div class="tarjeta">

			@include('../../modulos.busqueda_fecha')

			<table class="table table-striped table-hover table-sm tabla_medicamentos_movimientos">

				<!--Table head-->
				<thead>
					<tr>
						<th class="th-lg">Nombre</th>
						<th class="th-lg">Ingreso</th>
						<th class="th-lg">Suministrados</th>
						<th class="th-lg">Egreso</th>
						<th class="th-lg">Motivo</th>
						<th class="th-lg">Fecha ingreso</th>
						<th class="th-lg">Registro creado</th>
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
