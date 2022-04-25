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


			<table class="table table-striped table-hover table-sm tabla_ausentismos">

				<!--Table head-->
				<thead>
					<tr>
						<th class="th-lg">
							<a>Trabajador <i class="fas fa-sort ml-1"></i></a>
						</th>
						<th class="th-lg">
							<a>DNI <i class="fas fa-sort ml-1"></i></a>
						</th>
						<th class="th-lg">
							<a>Sector <i class="fas fa-sort ml-1"></i></a>
						</th>
						<th class="th-lg">
							<a href="">Tipo <i class="fas fa-sort ml-1"></i></a>
						</th>
						<th class="th-lg">
							<a href="">Fecha inicio <i class="fas fa-sort ml-1"></i></a>
						</th>
						<th class="th-lg">
							<a href="">Fecha final <i class="fas fa-sort ml-1"></i></a>
						</th>
						<th class="th-lg">
							<a href="">Fecha en que regresó <i class="fas fa-sort ml-1"></i></a>
						</th>
						@if (auth()->user()->fichada == 1)
						<th class="th-lg">
							<a href="">  Acciones <i class="fas fa-sort ml-1"></i></a>
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
