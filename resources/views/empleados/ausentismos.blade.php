@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Listado de ausentismos</h2>
			<p>Aquí puede ver el listado de ausentismos de la empresa</p>
			@if (auth()->user()->fichada == 1 || !auth()->user()->fichar)
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-base" href="{{route('ausentismos.create')}}"><i
						class="fas fa-plus-circle"></i> Nuevo ausentismo</a>
			</div>
			@else
			<div class="small text-muted"><i>Debe fichar para poder agregar un nuevo ausentismo</i></div>
			@endif
		</div>

		@include('mensajes_validacion')

		<div class="tarjeta">

			@include('modulos.ausentismos_tabla')

		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>


<!-- Modal Extension de licencia -->
<div class="modal fade" id="extensionLicenciaModal" tabindex="-1" aria-labelledby="extensionLicenciaModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<div class="alert alert-warning" role="alert">
					<h4 class="alert-heading">Importante!</h4>
					<p>
						Estará creando una nueva comunicación de ausentismo y modificando la fecha final del ausentismo
						a la vez.
					</p>
				</div>
				<div class="row p-4">
					<form id="form_crear_evento_ausentismo" style="width: 100%;"
						action="{{action('EmpleadosAusentismosController@extensionComunicacion')}}"
						enctype="multipart/form-data" accept-charset="UTF-8" method="post">
						{{ csrf_field() }}
						<input type="hidden" name="id_ausentismo" value="">
						<div class="form-row">
							<div class="form-group col-md-6">
								<label>Tipo de comunicación</label>
								<select class="form-control" name="id_tipo">
									@foreach ($tipo_comunicaciones as $tipo)
									<option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group col-md-6">
								<label>Nueva fecha final <span style="color: red;">*</span></label>
								<input name="fecha_final" required id="ausentismo_fecha_final" type="datetime"
									class="form-control" placeholder="">
							</div>
							<div class="form-group col-md-12">
								<label>Descripcion</label>
								<textarea required required name="descripcion" class="form-control" rows="3"></textarea>
							</div>
						</div>
						<button class="btn-ejornal btn-ejornal-success" type="submit" name="button">Crear
							comunicación</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection