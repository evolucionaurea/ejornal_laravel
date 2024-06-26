@extends('partials.layout')

@section('title', 'Grupos')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_grupos')

	<div id="page-content-wrapper">

		@include('partials.nav_sup')


		@if ($cliente_actual)

		<div class="cabecera">
			<h2>Listado de ausentismos</h2>
			<p>Aquí puede ver el listado de ausentismos de la sucursal seleccionada: <b>{{ $cliente_actual->nombre }}</b></p>
		</div>


		<div class="tarjeta">

			@include('modulos.ausentismos_tabla')

		</div>

		@else
		<div class="cabecera">
			<p class="text-muted"><i>Debes seleccionar un cliente de la lista para ver la información.</i></p>
		</div>
		@endif




	</div>

</div>


@endsection