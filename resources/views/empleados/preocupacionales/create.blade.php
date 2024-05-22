@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Creación de estudios médicos complementarios</h2>
			<p>Aquí puedes cargar a los estudios médicos complementarios de la empresa.</p>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-gris-claro" href="{{url('empleados/preocupacionales')}}"><i class="fas fa-arrow-alt-circle-left"></i>Volver</a>
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
			<form action="{{action('EmpleadosPreocupacionalesController@store')}}" accept-charset="UTF-8" method="post" enctype="multipart/form-data">
				@csrf
				<div class="form-row">

					<div class="form-group col-md-3">
						<label>Trabajador</label>
						<select data-toggle="select2" name="trabajador" class="form-control form-control-sm select_2" required>
							<option value="">--Seleccionar--</option>
							@foreach ($trabajadores as $trabajador)
							<option value="{{$trabajador->id}}">{{$trabajador->nombre}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-3">
						<label>Fecha</label>
						<input name="fecha" type="text" class="form-control" value="{{ old("fecha") }}" required>
					</div>

					<div class="form-group col-md-6">
						<label>Documentación</label>
						<div class="custom-file">
							<input name="archivo" type="file" class="custom-file-input">
							<label for="" class="custom-file-label">Seleccionar archivo...</label>
						</div>
					</div>

					<div class="form-group col-md-3">
						<label for="">¿Tiene Vencimiento?</label>
						<select name="tiene_vencimiento" class="form-control" required>
							<option value="0">No</option>
							<option value="1">Si</option>
						</select>
					</div>

					<div data-toggle="vencimiento" class="form-group col-md-3 d-none">
						<label for="">Fecha de Vencimiento</label>
						<input name="fecha_vencimiento" type="text" class="form-control" >
					</div>
					<div data-toggle="vencimiento" class="form-group col-md-3 d-none">
						<label for="">Completado</label>
						<select name="completado" class="form-control">
							<option value="0">No</option>
							<option value="1">Si</option>
						</select>
					</div>

				</div>

				<hr>
				<div class="form-group">
					<label>Observaciones</label>
					<textarea name="observaciones" class="form-control" rows="6" required>{{ old("observaciones") }}</textarea>
				</div>
				<button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Crear preocupacional</button>
			</form>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>

@endsection