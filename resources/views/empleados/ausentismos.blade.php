@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Listado de ausentismos</h2>
			<p>Aquí puede ver el listado de ausentismos de la empresa</p>
			@if (auth()->user()->fichada == 1)
			  <div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-base" href="{{route('ausentismos.create')}}"><i class="fas fa-plus-circle"></i> Nuevo ausentismo</a>
			  </div>
			@else
			<div class="small text-muted"><i>Debe fichar para poder agregar un nuevo ausentismo</i></div>
			@endif
		</div>

		 @include('../mensajes_validacion')

		<div class="tarjeta">


			@include('../../modulos.busqueda_fecha')


			<table class="table table-striped table-bordered table-hover table-sm tabla_ausentismos">

				<!--Table head-->
				<thead>
					<tr>
						<th class="th-lg">
							Trabajador
						</th>
						<th class="th-lg">
							DNI
						</th>
						<th class="th-lg">
							Sector
						</th>
						<th class="th-lg">
							Tipo
						</th>
						<th class="th-lg">
							Fecha inicio
						</th>
						<th class="th-lg">
							Fecha final
						</th>
						<th class="th-lg">
							Fecha en que regresó
						</th>
						@if (auth()->user()->fichada == 1)
						<th class="th-lg">
							Acciones
						</th>
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
