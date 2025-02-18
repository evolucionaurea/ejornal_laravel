@extends('partials.layout')

@section('title', 'Clientes')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_clientes')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Listado de trabajadores</h2>
			<p>Aquí puede ver el listado de trabajadores de su empresa</p>

			<div class="form-group">
				<a href="{{url('clientes/nominas/historial')}}" class="btn btn-dark btn-sm">
					<i class="fa fa-calendar fa-fw"></i> Ver Historial
				</a>
				<a href="{{route('/clientes/nominas/movimientos')}}" class="btn btn-dark btn-sm" >
					<i class="fas fa-sync fa-fw"></i> Ver Movimientos
				</a>
			</div>

		</div>

		@include('../mensajes_validacion')

		<div class="tarjeta">
			@include('modulos.nominas_tabla')
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>


@endsection
