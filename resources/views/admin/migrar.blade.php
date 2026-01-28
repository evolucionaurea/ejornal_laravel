@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_admin')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		{{-- Migracion de Clientes con datos nuevos en direccion (Es algo momentaneo) --}}
		@if (true)

		<div class="cabecera">
			<h2>Migrar Clientes con datos actualizados</h2>
			<p>
				Subí un archivo <b>CSV</b> (modelo) para completar masivamente:
				<b>calle</b>, <b>nro</b> y <b>provincia</b> (por nombre).
			</p>

			{{-- Mensajes de validación / errores --}}
			@include('../mensajes_validacion')

			@if ($errors->any())
			@foreach ($errors->all() as $error)
			<div class="alert alert-danger alert-dismissible fade show mr-4 ml-4" role="alert">
				{{ $error }}
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			@endforeach
			@endif

			@if (session('ok'))
			<div class="alert alert-success alert-dismissible fade show mr-4 ml-4" role="alert">
				{{ session('ok') }}
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			@endif

			<div class="cabecera_acciones">
				<form action="{{ route('migrar_clientes_actualizados') }}" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="form-group">
						<label for="archivo_excel">Archivo (CSV):</label>
						<input type="file" class="form-control-file" id="archivo_excel" name="archivo_excel" required>
						<small class="form-text text-muted">
							Cabeceras obligatorias: <b>id_cliente</b>, <b>calle</b>, <b>nro</b>, <b>provincia</b>.
							(La provincia se escribe por nombre, se convierte a <b>id_provincia</b> automáticamente)
						</small>
					</div>

					<button type="submit" class="btn-ejornal btn-ejornal-base">
						<i class="fas fa-upload"></i>
						Subir y Migrar
					</button>

					{{-- Descargar modelo --}}
					<a class="btn-ejornal btn-ejornal-dark" href="{{ route('migrar_clientes_actualizados_modelo') }}">
						<i class="fas fa-download"></i>
						Descargar modelo Excel
					</a>

				</form>
			</div>
		</div>
		@endif

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