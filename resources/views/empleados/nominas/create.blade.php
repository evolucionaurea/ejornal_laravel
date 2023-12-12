@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Creación de trabajadores</h2>
			<p>Aquí puedes cargar a los trabajadores que formarán parte de la nómina de la empresa</p>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-gris-claro" href="{{ url('empleados/nominas') }}"><i
						class="fas fa-arrow-circle-left"></i>Volver</a>
			</div>
		</div>

		@include('../../mensajes_validacion')

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
			<form action="{{action('EmpleadosNominasController@store')}}" accept-charset="UTF-8" method="post"
				enctype="multipart/form-data">
				@csrf
				<div class="form-row">
					<div class="form-group col-md-3">
						<label>Nombre *</label>
						<input required name="nombre" type="text" class="form-control form-control-sm" placeholder="" value="{{old('nombre')}}">
					</div>
					<div class="form-group col-md-3">
						<label>CUIL</label>
						<input name="email" type="text" class="form-control form-control-sm" placeholder="" value="{{old('email')}}">
					</div>
					<div class="form-group col-md-3">
						<label>Teléfono</label>
						<input name="telefono" type="text" class="form-control form-control-sm" placeholder="" value="{{old('telefono')}}">
					</div>
					<div class="form-group col-md-3">
						<label>DNI *</label>
						<input required name="dni" type="number" class="form-control form-control-sm" placeholder="solamente números, sin puntos" step="1" value="{{old('dni')}}">
					</div>
					<div class="form-group col-md-3">
						<label>Estado *</label>
						<select required name="estado" class="form-control form-control-sm">
							<option value="1" {{ old('estado')=='1' ? 'selected' : '' }}>Activo</option>
							<option value="0" {{ old('estado')=='0' ? 'selected' : '' }}>Inactivo</option>
						</select>
					</div>
					<div class="form-group col-md-3">
						<label>Sector *</label>
						<input required name="sector" type="text" class="form-control form-control-sm" placeholder="" value="{{old('sector')}}">
					</div>
					<div class="form-group col-md-3">
						<label>Calle</label>
						<input name="calle" type="text" class="form-control form-control-sm" placeholder="" value="{{old('calle')}}">
					</div>
					<div class="form-group col-md-3">
						<label>Nro</label>
						<input name="nro" type="text" class="form-control form-control-sm" placeholder="" value="{{old('nro')}}">
					</div>
					<div class="form-group col-md-3">
						<label>Entre calles</label>
						<input name="entre_calles" type="text" class="form-control form-control-sm" placeholder="" value="{{old('entre_calles')}}">
					</div>
					<div class="form-group col-md-3">
						<label>Localidad</label>
						<input name="localidad" type="text" class="form-control form-control-sm" placeholder="" value="{{old('localidad')}}">
					</div>
					<div class="form-group col-md-3">
						<label>Partido</label>
						<input name="partido" type="text" class="form-control form-control-sm" placeholder="" value="{{old('partido')}}">
					</div>
					<div class="form-group col-md-3">
						<label>Codigo postal</label>
						<input name="cod_postal" type="text" class="form-control form-control-sm" placeholder="" value="{{old('cod_postal')}}">
					</div>

					<div class="form-group col-md-3">
						<label>Subir/Reemplazar foto</label>
						<div class="input-group">
							<div class="custom-file">
								<input name="foto" id="input_file" type="file" class="custom-file-input"
									accept="image/*">
								<label for="input_file" class="custom-file-label">Subir Imagen</label>
							</div>
						</div>
					</div>


					<div class="form-group col-md-12">
						<label>Observaciones</label>
						<textarea class="form-control form-control-sm" name="observaciones" rows="2"
							cols="80">{{old('observaciones')}}</textarea>
					</div>
				</div>
				<button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Crear trabajador</button>
			</form>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>

@endsection