@extends('partials.layout')

@section('title', 'Grupos')

@section('content')

@inject('carbon', 'Carbon\Carbon')

<div class="d-flex" id="wrapper">

	@include('partials.sidebar_grupos')

	<div id="page-content-wrapper">

		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}
		@include('modulos.resumen_cliente')
		{{-- Contenido de la pagina --}}



	</div>

</div>


@endsection
