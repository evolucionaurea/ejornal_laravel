@extends('partials.layout')

@section('title', 'Movimiento Nóminas')

@section('content')

<div class="d-flex" id="wrapper">

	@include('partials.sidebar_grupos')

	<div id="page-content-wrapper">
		@include('partials.nav_sup')
		<div class="cabecera">
			<h2>Movimientos de los trabajadores de la nómina</h2>
			<p>Si un trabajador se mueve de una empresa a otra (o entre sucursales de la misma empresa) se verá reflejado en este listado.</p>

			<div class="form-group">
				<a href="{{url('grupos/nominas')}}" class="btn btn-dark btn-sm">
					<i class="fa fa-angle-double-left fa-fw"></i>
					<span>Volver al listado</span>
				</a>
			</div>

			<hr>


			@include('modulos.nominas_movimientos')

		</div>

	</div>
</div>


@endsection
