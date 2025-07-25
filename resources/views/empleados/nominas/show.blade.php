@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">

		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}
		<div class="cabecera">
			<div class="d-flex align-items-center justify-content-between">

				<div>
					<h2 style="margin: 0px;">Historial de {{$trabajador->nombre}}</h2>
					@if( auth()->user()->id_cliente_actual != $trabajador->id_cliente)
					<span class="badge badge-dark">transferido</span>
					@endif
				</div>
				
				@if ($trabajador->foto)
				<div class="foto-perfil" style="background-image: url({{ $trabajador->photo_url }}); width:60px;height:60px; background-size: cover; background-position: center; border-radius: 50%;margin:0">
				</div>
				@else
				<i class="fas fa-user fa-lg ml-4"></i>
				@endif

			</div>
			<hr>
			<p>Aquí podrá ver las consultas y ausentismos del trabajador</p>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-gris-claro"
					href="{{ url('empleados/nominas') }}?{{$_SERVER['QUERY_STRING']}}">
					<i class="fas fa-arrow-circle-left"></i> <span>Volver</span>
				</a>
				{{-- <a class="btn-ejornal btn-ejornal-base"
					href="{{ route('empleados.nominas.caratulas.create', ['id_nomina' => $trabajador->id]) }}">
					<i class="fas fa-plus-circle"></i> <span>Nueva caratula</span>
				</a> --}}
				@if( auth()->user()->id_cliente_actual == $trabajador->id_cliente)
				<a href="{{ url('empleados/nominas/'.$trabajador->id.'/edit') }}"
					class="btn-ejornal btn-ejornal-gris-claro">
					<i class="fa fa-pencil fa-fw"></i> <span>Editar Trabajador</span>
				</a>
				@endif

			</div>
		</div>

		@include('../../../mensajes_validacion')

		@if ($errors->any())
		@foreach ($errors->all() as $error)
		<div class="alert alert-danger alert-dismissible fade show mr-4 ml-4" role="alert">
			{{$error}}
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		@endforeach
		@endif

		@include('modulos.perfil_trabajador')

		{{-- Contenido de la pagina --}}

	</div>
</div>

@endsection