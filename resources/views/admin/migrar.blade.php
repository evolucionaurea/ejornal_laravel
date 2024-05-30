@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_admin')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		@if (true)
		<div class="alert alert-info m-4">
			Seccion no disponible. Se utilizó en el pasado para migrar datos del viejo sistema al nuevo.
			Se lo deja sin efecto de momento por las dudas de ocasionar inconvenientes porque ya se han
			migrado.
		</div>
		@else
		<div class="cabecera">
			<h2>Migrar info del sitio viejo</h2>
			<p>Esta es una vista oculta solo para migrar el sitio viejo</p>
			<div class="cabecera_acciones">
				@if ($clientes == false)
				<a class="btn-ejornal btn-ejornal-base" href="{{route('migrar_clientes')}}">
					<i class="fas fa-sync"></i>
					Migrar clientes
				</a>
				@endif
				@if ($users_empleados == false)
				<a class="btn-ejornal btn-ejornal-base" href="{{route('migrar_users_empleados')}}">
					<i class="fas fa-sync"></i>
					Migrar usuarios empleados
				</a>
				@endif
				@if ($nominas == false)
				<a class="btn-ejornal btn-ejornal-base" href="{{route('migrar_nominas')}}">
					<i class="fas fa-sync"></i>
					Migrar nominas
				</a>
				@endif
				@if (auth()->user()->email == "sebas_admin@ejornal.com.ar" && $nominas == false)
				<a class="btn-ejornal btn-ejornal-base" href="{{route('migrar_fichadas')}}">
					<i class="fas fa-sync"></i>
					Migrar Fichadas a FichadasNuevas
				</a>
				@endif

				@if (auth()->user()->email == "sebas_admin@ejornal.com.ar" )
				<a class="btn-ejornal btn-ejornal-base" href="{{route('migrar_users_clientes')}}">
					<i class="fas fa-sync"></i>
					Actualizar Usuarios Clientes
				</a>
				@endif

			</div>
		</div>
		@include('../mensajes_validacion')
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

		<div class="tarjeta">
			<ul class="list-group">
				<li class="list-group-item d-flex justify-content-between align-items-center">
					Clientes
					@if ($clientes == false)
					<span class="badge badge-danger badge-pill"><i class="fas fa-times"></i></span>
					@else
					<span class="badge badge-success badge-pill"><i class="fas fa-check"></i></span>
					@endif
				</li>
				<li class="list-group-item d-flex justify-content-between align-items-center">
					Usuarios de tipo Empleado
					@if ($users_empleados == false)
					<span class="badge badge-danger badge-pill"><i class="fas fa-times"></i></span>
					@else
					<span class="badge badge-success badge-pill"><i class="fas fa-check"></i></span>
					@endif
				</li>
				<li class="list-group-item d-flex justify-content-between align-items-center">
					Nominas
					@if ($nominas == false)
					<span class="badge badge-danger badge-pill"><i class="fas fa-times"></i></span>
					@else
					<span class="badge badge-success badge-pill"><i class="fas fa-check"></i></span>
					@endif
				</li>
				<li class="list-group-item d-flex justify-content-between align-items-center">
					Fichadas a FichadasNuevas
					@if ($fichadas == false)
					<span class="badge badge-danger badge-pill"><i class="fas fa-times"></i></span>
					@else
					<span class="badge badge-success badge-pill"><i class="fas fa-check"></i></span>
					@endif
				</li>
			</ul>
		</div>

		@endif



		{{-- Contenido de la pagina --}}
	</div>
</div>

@endsection