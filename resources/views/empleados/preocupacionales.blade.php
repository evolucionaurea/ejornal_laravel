@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Listado de estudios medicos complementarios</h2>
			<p>Aquí puede ver el listado de estudios medicos complementarios de la empresa</p>
			@if (auth()->user()->fichada == 1)
			  <div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-base" href="{{route('preocupacionales.create')}}">
				  <i class="fas fa-plus-circle"></i>
				  Nuevo estudio medico complementario
				</a>
			  </div>
			@else
			<div class="small text-muted"><i>[Debe fichar para agregar un nuevo estudio médico complementario]</i></div>
			@endif
		</div>

		 @include('../mensajes_validacion')

		<div class="tarjeta">

			@include('../../modulos.busqueda_fecha')

			<table class="table table-striped table-hover table-sm tabla_preocupacionales_listado">

				<!--Table head-->
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Email</th>
						<th>Tel</th>
						<th>Fecha</th>
						<th>Documentación</th>
						@if (auth()->user()->fichada == 1)
						<th>Acciones</th>
						@endif
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
