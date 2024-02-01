@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Certificados de un ausentismo</h2>
			<p>Aquí puede ver el listado de las documentaciones que carga en cada ausentismo</p>
		</div>

		 @include('../mensajes_validacion')

		<div class="tarjeta">

			@include('../../modulos.busqueda_fecha')

			<table class="table table-striped table-hover table-sm tabla_certificados_ausentismo_listado">

			  <!--Table head-->
			  <thead>
				<tr>
				  <th>Trabajador</th>
				  <th>Médico</th>
				  <th>Institución</th>
				  <th>Fecha inicio</th>
				  <th>Fecha final</th>
				  <th>Fecha en que regresó</th>
				  <th>Matricula nacional</th>
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
