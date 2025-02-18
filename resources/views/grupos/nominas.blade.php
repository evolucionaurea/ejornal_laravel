@extends('partials.layout')

@section('title', 'Grupos')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_grupos')

	<div id="page-content-wrapper">

		@include('partials.nav_sup')


		@if ($cliente_actual)

		<div class="cabecera">
			<h2>Listado de trabajadores</h2>
			<p>Aquí puede ver el listado de trabajadores de la sucursal seleccionada: <b>{{ $cliente_actual->nombre }}</b></p>

			<div class="cabecera_acciones">
				<a href="{{route('/grupos/nominas_historial')}}" class="btn-ejornal btn-dark" >
					<i class="fas fa-file-excel fa-fw"></i> Historial
				</a>
				<a href="{{route('/grupos/nominas_movimientos')}}" class="btn-ejornal btn-dark" >
					<i class="fas fa-sync fa-fw"></i> Ver Movimientos
				</a>

			</div>

		</div>


		<div class="tarjeta">

			@include('modulos.nominas_tabla')

		</div>


		@else
		<div class="cabecera">
			<p class="text-muted"><i>Debes seleccionar un cliente de la lista para ver la información.</i></p>
		</div>
		@endif


	</div>

</div>


@endsection