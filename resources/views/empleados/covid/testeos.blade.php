@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Listado de testeos</h2>
			<p>Aquí puede ver el listado de testeos de la empresa</p>
			@if (auth()->user()->fichada)
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-base" href="{{route('testeos.create')}}"><i class="fas fa-plus-circle"></i> Nuevo testeo</a>
			</div>
			@else
			<div class="small text-muted"><i>Debe fichar para poder agregar un nuevo testeo</i></div>
			@endif
		</div>

		@include('../../mensajes_validacion')

		<div class="tarjeta">

			@include('../../modulos.busqueda_fecha')


			<table class="table table-striped table-hover table-sm tabla_testeos_listado">

				<!--Table head-->
				<thead>
					<tr>
						<th class="th-lg">Trabajador</th>
						<th class="th-lg">Tipo</th>
						<th class="th-lg">Fecha</th>
						<th class="th-lg">Resultado</th>
						<th class="th-lg">Laboratorio</th>
						@if (auth()->user()->fichada)
						<th class="th-lg">Acciones</th>
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
