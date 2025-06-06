@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Edición de trabajadores de la nómina</h2>
			<p>Aquí podrá editar la información de un trabajador de la nómina</p>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-gris-claro"
					href="{{ url('empleados/nominas') }}?{{$_SERVER['QUERY_STRING']}}"><i
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
			<form action="{{action('EmpleadosNominasController@update', $trabajador->id)}}" accept-charset="UTF-8"
				method="post" enctype="multipart/form-data">
				{{ csrf_field() }}
				<input name="_method" type="hidden" value="PUT">
				<div class="form-row">
					<div class="form-group col-md-3">
						<label>Nombre*</label>
						<input name="nombre" type="text" class="form-control form-control-sm"
							value="{{$trabajador->nombre}}" placeholder="">
					</div>
					<div class="form-group col-md-3">
						<label>CUIL</label>
						<input name="email" type="text" class="form-control form-control-sm"
							value="{{$trabajador->email}}" placeholder="">
					</div>
					<div class="form-group col-md-3">
						<label>Legajo</label>
						<input name="legajo" type="text" class="form-control form-control-sm"
							value="{{$trabajador->legajo}}" placeholder="">
					</div>
					<div class="form-group col-md-3">
						<label>Telefono</label>
						<input name="telefono" type="text" class="form-control form-control-sm"
							value="{{$trabajador->telefono}}" placeholder="">
					</div>
					<div class="form-group col-md-3">
						<label>DNI*</label>
						<input name="dni" type="number" class="form-control form-control-sm"
							value="{{$trabajador->dni}}" placeholder="" step="1" required>
					</div>

					<div class="form-group col-md-3">
						<label>Fecha de Nacimiento</label>
						<input name="fecha_nacimiento" type="text" class="form-control form-control-sm"
							value="{{ $trabajador->fecha_nacimiento ? $trabajador->fecha_nacimiento->format('d/m/Y') : '' }}"
							placeholder="">
					</div>


					<div class="form-group col-md-3">
						<label>Estado*</label>
						<select name="estado" class="form-control form-control-sm" required>
							@if ($trabajador->estado == 1)
							<option selected value="1">Activo</option>
							<option value="0">Inactivo</option>
							@else
							<option value="1">Activo</option>
							<option selected value="0">Inactivo</option>
							@endif
						</select>
					</div>

					<div class="form-group col-md-3">
						<label>Cliente*</label>
						<select name="id_cliente" class="form-control form-control-sm" required>
							<option value="">--Seleccionar--</option>
							@if($clientes) @foreach($clientes as $cliente):
							<option value="{{ $cliente->id }}" {{ $cliente->id==$trabajador->id_cliente ? 'selected' :
								'' }} >{{ $cliente->nombre }}</option>
							@endforeach @endif
						</select>
					</div>


					<div class="form-group col-md-3">
						<label>Sector*</label>
						<input name="sector" type="text" class="form-control form-control-sm"
							value="{{$trabajador->sector}}" placeholder="" required>
					</div>
					<div class="form-group col-md-3">
						<label>Calle</label>
						<input name="calle" type="text" class="form-control form-control-sm" placeholder=""
							value="{{$trabajador->calle}}">
					</div>
					<div class="form-group col-md-3">
						<label>Nro</label>
						<input name="nro" type="text" class="form-control form-control-sm" placeholder=""
							value="{{$trabajador->nro}}">
					</div>
					<div class="form-group col-md-3">
						<label>Entre calles</label>
						<input name="entre_calles" type="text" class="form-control form-control-sm" placeholder=""
							value="{{$trabajador->entre_calles}}">
					</div>
					<div class="form-group col-md-3">
						<label>Localidad</label>
						<input name="localidad" type="text" class="form-control form-control-sm" placeholder=""
							value="{{$trabajador->localidad}}">
					</div>
					<div class="form-group col-md-3">
						<label>Partido</label>
						<input name="partido" type="text" class="form-control form-control-sm" placeholder=""
							value="{{$trabajador->partido}}">
					</div>
					<div class="form-group col-md-3">
						<label>Codigo postal</label>
						<input name="cod_postal" type="text" class="form-control form-control-sm" placeholder=""
							value="{{$trabajador->cod_postal}}">
					</div>
					<div class="form-group col-md-6 border p-4 rounded">

						<div class="row">
							<div class="col-md-4">
								<label>Foto</label>
								<br>
								@if (isset($trabajador->foto) && !empty($trabajador->foto))
								<img style="width: 100%"
									src="{{asset('storage/nominas/fotos/'.$trabajador->id.'/'.$trabajador->hash_foto)}}">
								@else
								<span>
									<i class="fas fa-user fa-1x"></i>
									Sin foto cargada
								</span>
								@endif

							</div>
							<div class="col-md-8">
								<label>Subir/Reemplazar foto</label>
								<div class="input-group">
									<div class="custom-file">
										<input name="foto" id="input_file" type="file" class="custom-file-input"
											accept="image/*">
										<label for="input_file" class="custom-file-label">Subir Imagen</label>
									</div>
								</div>
							</div>
						</div>
					</div>



					<div class="form-group col-md-12">
						<label>Observaciones</label>
						<textarea class="form-control form-control-sm" name="observaciones" rows="2"
							cols="80">{{$trabajador->observaciones}}</textarea>
					</div>
					<div class="col-12">
						<button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Guardar
							cambios</button>
					</div>
				</div>
			</form>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>

@endsection