@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Creación de estudios medicos complementarios</h2>
			<p>Aquí puedes cargar a los estudios medicos complementarios de la empresa</p>
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
						<select name="trabajador" class="form-control form-control-sm select_2">
							@foreach ($trabajadores as $trabajador)
							<option value="{{$trabajador->id}}">{{$trabajador->nombre}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-md-3">
						<label>Fecha</label>
						<input id="fecha_estudio_complementario" name="fecha" type="text" class="form-control" value="{{ old("fecha") }}">
					</div>
					<div class="form-group col-md-6">
						<label>Documentación</label>
						<input name="archivo" type="file" class="form-control-file">
					</div>
					<div class="form-group col-md-6">
						<label>Observaciones</label>
						<textarea name="observaciones" class="form-control" rows="3">{{ old("observaciones") }}</textarea>
					</div>
				</div>
				<button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Crear preocupacional</button>
			</form>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>

@endsection


<script type="text/javascript">

window.addEventListener("load", function(event) {

	$('.select_2').select2();

});

</script>
