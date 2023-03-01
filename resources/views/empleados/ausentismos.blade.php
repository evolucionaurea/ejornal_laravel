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
			@if (auth()->user()->fichada == 1)
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-base" href="{{route('ausentismos.create')}}"><i class="fas fa-plus-circle"></i> Nuevo ausentismo</a>
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


<!-- Modal -->
<div class="modal fade" id="extensionLicenciaModal" tabindex="-1" aria-labelledby="extensionLicenciaModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<div class="alert alert-warning" role="alert">
					<h4 class="alert-heading">Importante!</h4>
					<p>
						Estará creando nueva documentacion de ausentismo y modificando la fecha final del ausentismo
						a la vez.
					</p>
				</div>
				<div class="row p-4">
					<form id="form_crear_evento_ausentismo"
						action="{{action('EmpleadosAusentismoDocumentacionController@store')}}"
						enctype="multipart/form-data" accept-charset="UTF-8" method="post">
						{{ csrf_field() }}
						<input type="hidden" name="id_ausentismo" value="">
						<input class="matricula_validada_hidden" type="hidden" name="" value="">
						<div class="form-row">
							<div class="form-group col-md-6">
								<label>Institución <span style="color: red;">*</span></label>
								<input required name="institucion" type="text" class="form-control" placeholder="">
							</div>
							<div class="form-group col-md-6">
								<label>Medico <span style="color: red;">*</span></label>
								<input required name="medico" type="text" class="form-control" placeholder="">
							</div>
							<div class="form-group col-md-6">
								<label>Matricula provincial</label>
								<input name="matricula_provincial" type="text" class="form-control" placeholder="">
							</div>
							<div class="form-group col-md-6">
								<label class="d-flex align-items-center">
									Matrícula nacional
									<i style="color: green; margin-left: 5px;"
										class="fas fa-check-circle matricula_tilde"></i>
									<i style="color: red; margin-left: 5px;"
										class="fas fa-times-circle matricula_cruz"></i>
								</label>
								<div class="d-flex">
									<input style="max-width: 200px; margin-right: 5px;" name="matricula_nacional"
										type="text" class="form-control nro_matricula_nacional" placeholder="">
									<a id="validar_matricula" class="btn-ejornal btn-ejornal-gris-claro" href="#"><i
											class="fas fa-user-check"></i> Validar</a>
								</div>
							</div>
							<div class="form-group col-md-6">
								<label>Fecha documento <span style="color: red;">*</span></label>
								<input name="fecha_documento" required id="data_picker_gral" type="datetime"
									class="form-control" placeholder="">
							</div>
							<div class="form-group col-md-6">
								<label>Nueva fecha final <span style="color: red;">*</span></label>
								<input name="fecha_final" required id="ausentismo_fecha_final" type="datetime"
									class="form-control" placeholder="">
							</div>
							<div class="form-group col-md-12">
								<label>Archivo <span style="color: red;">*</span> </label>
								<input required name="archivo" type="file" class="form-control-file">
							</div>
							<div class="form-group col-md-6">
								<label>Diagnostico <span style="color: red;">*</span></label>
								<textarea required name="diagnostico" class="form-control" rows="3"></textarea>
							</div>
							<div class="form-group col-md-6">
								<label>Observaciones</label>
								<textarea name="observaciones" class="form-control" rows="3"></textarea>
							</div>
						</div>
						<a id="submit_crear_documentacion_ausentismo" class="btn-ejornal btn-ejornal-success">Crear
							documentacion</a>
						<button type="button" class="btn-ejornal btn-ejornal-gris-claro"
							data-dismiss="modal">Cerrar</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection