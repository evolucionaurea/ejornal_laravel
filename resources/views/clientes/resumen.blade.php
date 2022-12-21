@extends('partials.layout')

@section('title', 'Clientes')

@section('content')

<div class="d-flex" id="wrapper">

	@include('partials.sidebar_clientes')

	<div id="page-content-wrapper">

		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}
		@include('modulos.resumen_cliente')
		{{-- Contenido de la pagina --}}


	</div>

</div>


@endsection
