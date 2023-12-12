@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Listado de tareas adecuadas</h2>
			<p>Aquí puede ver el listado de tareas adecuadas de la empresa</p>
			@if (auth()->user()->fichada == 1)
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-base" href="{{route('tareas_livianas.create')}}"><i class="fas fa-plus-circle"></i> Nueva tarea adecuada</a>
			</div>
			@else
			<div class="small text-muted"><i>Debe fichar para poder agregar una nueva tarea adecuada</i></div>
			@endif
		</div>

		@include('../mensajes_validacion')

		<div class="tarjeta">


			<div data-toggle="busqueda-fecha" class="row align-items-center">

				<input type="hidden" name="filtro" value="{{ Request::get('filtro') }}">

				<div class="col-lg-2 form-group">
					<select name="tipo" class="form-control form-control-sm select_2">
						<option value="">--Todos los tipos--</option>
						@foreach ($tipos as $tipo)
						<option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
						@endforeach
					</select>
				</div>


				<div class="col-lg-2 form-group">
					<input placeholder="Desde" name="from" type="datetime" class="form-control form-control-sm"
						value="{{$fecha_inicio ?? ''}}">
				</div>

				<div class="col-lg-2 form-group">
					<input placeholder="Hasta" name="to" type="datetime" class="form-control form-control-sm"
						value="{{$fecha_final ?? ''}}">
				</div>


				<div class="col-lg-6 form-group">
					<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro">
						<i class="fas fa-search"></i> Buscar
					</button>
					<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro" href="#!">
						<i class="fas fa-list"></i> Mostrar todo
					</button>
					<button data-toggle="export" data-href="{{url(Route::currentRouteName().'/exportar')}}"
						target="_blank" class="btn-ejornal btn-info"><i class="fas fa-file-excel"></i> Exportar</button>
				</div>

			</div>
			<hr>


			<table class="table table-striped table-bordered table-hover table-sm tabla_tareas_livianas">

				<!--Table head-->
				<thead>
					<tr>
						<th>Trabajador</th>
						<th>DNI</th>
						<th>Sector</th>
						<th>Tipo</th>
						<th>Fecha inicio</th>
						<th>Fecha final</th>
						<th>Fecha retorno a tareas habituales</th>
						<th>Hoy</th>

						<th>Acciones</th>

					</tr>
				</thead>
				<!--Table head-->

				<!--Table body-->
				<tbody></tbody>
				<!--Table body-->
			</table>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>


<!-- Modal Extension de Licencia -->
<div class="modal fade" id="extensionLicenciaAdecuadaModal" tabindex="-1"
	aria-labelledby="extensionLicenciaAdecuadaModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<div class="alert alert-warning" role="alert">
					<h4 class="alert-heading">Importante!</h4>
					<p>
						Estará creando nueva documentacion de tarea liviana y modificando la fecha final de la tarea
						liviana a la vez.
					</p>
				</div>
				<div class="row p-4">
					<form id="form_crear_evento_tareas_livianas"
						action="{{action('EmpleadosTareasLivianasDocumentacion@store')}}" enctype="multipart/form-data"
						accept-charset="UTF-8" method="post">
						{{ csrf_field() }}
						<input type="hidden" name="id_tarea_liviana" value="">
						<input class="matricula_validada_liviana_hidden" type="hidden" name="" value="">
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
										class="fas fa-check-circle matricula_tilde_liviana"></i>
									<i style="color: red; margin-left: 5px;"
										class="fas fa-times-circle matricula_cruz_liviana"></i>
								</label>
								<div class="d-flex">
									<input style="max-width: 200px; margin-right: 5px;" name="matricula_nacional"
										type="text" class="form-control nro_matricula_nacional" placeholder="">
									<a id="validar_matricula_liviana" class="btn-ejornal btn-ejornal-gris-claro"
										href="#"><i class="fas fa-user-check"></i> Validar</a>
								</div>
							</div>
							<div class="form-group col-md-6">
								<label>Fecha documento <span style="color: red;">*</span></label>
								<input name="fecha_documento" required id="data_picker_gral" type="datetime"
									class="form-control" placeholder="">
							</div>
							<div class="form-group col-md-6">
								<label>Nueva fecha final <span style="color: red;">*</span></label>
								<input name="fecha_final" required id="tarea_liviana_fecha_final" type="datetime"
									class="form-control" placeholder="">
							</div>
							<div class="form-group col-md-12">
								<label>Archivo <span style="color: red;">*</span> </label>
								<input required name="archivo" type="file" class="form-control-file">
							</div>
							<div class="form-group col-md-6">
								<label>Diagnóstico <span style="color: red;">*</span></label>
								<textarea required name="diagnostico" class="form-control" rows="3"></textarea>
							</div>
							<div class="form-group col-md-6">
								<label>Observaciones</label>
								<textarea name="observaciones" class="form-control" rows="3"></textarea>
							</div>
						</div>
						<a id="submit_crear_documentacion_tarea_liviana" class="btn-ejornal btn-ejornal-success">Crear
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