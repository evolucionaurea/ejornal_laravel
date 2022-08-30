@extends('partials.layout')

@section('title', 'Grupos')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_grupos')

	<div id="page-content-wrapper">

		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Mi cuenta</h2>
			<p>Aquí puedes ver tus datos personales</p>
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
			<h4>Datos básicos</h4>

			<form action="{{action('GruposCuentaController@store')}}" method="post" class="row">
				{{ csrf_field() }}
				<input type="hidden" name="id_user" value="{{auth()->user()->id}}">
					<div class="form-group col-md-4">
						<label >Nombre</label>
						<input name="nombre" type="text" class="form-control form-control-sm" value="{{auth()->user()->nombre}}">
					</div>
					<div class="form-group col-md-4">
						<label >Email</label>
						<input disabled type="email" class="form-control form-control-sm" value="{{auth()->user()->email}}">
					</div>
					<div class="form-group col-md-4">
						<label >Estado</label>
						<p style="color: grey;">
							@if (auth()->user()->estado == 1)
								Activo
							@else
								Inactivo
							@endif
						</p>
					</div>

					<div class="col-12">
						<button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Guardar cambios</button>
					</div>

			</form>
		</div>


		<div class="tarjeta">
			<h4>Seguridad</h4>
			<p>¿Desea cambiar la contraseña?</p>
			<form action="{{action('GruposCuentaController@cambiar_pass')}}" method="post" class="row">
				{{ csrf_field() }}
				<input type="hidden" name="id_user" value="{{auth()->user()->id}}">
					<div class="form-group col-md-6">
						<label >Nueva contraseña</label>
						<input name="password" type="password" class="form-control form-control-sm" value="">
					</div>
					<div class="form-group col-md-6">
						<label >Repetir nueva contraseña</label>
						<input name="cpassword" type="password" class="form-control form-control-sm" value="">
					</div>
					<button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Cambiar contraseña</button>
			</form>
		</div>

		{{-- Contenido de la pagina --}}

	</div>
</div>



@endsection
