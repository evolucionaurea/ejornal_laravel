@extends('partials.layout')

@section('title', 'Clientes')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_clientes')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Listado de ausentismos</h2>
			<p>Aquí puede ver el listado de ausentismos de la empresa</p>
		</div>

		 @include('../mensajes_validacion')

		<div class="tarjeta">

			@include('modulos.ausentismos_tabla')

		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>


@endsection
