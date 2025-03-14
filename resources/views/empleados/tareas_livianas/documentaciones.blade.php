@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Certificados de una tarea adecuada</h2>
			<p>Aquí puedes ver y cargar nuevos certificados de esta tarea adecuada</p>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-gris-claro" href="{{ url('empleados/tareas_livianas') }}">
					<i class="fas fa-arrow-circle-left"></i> Volver
				</a>

				@if(auth()->user()->id_cliente_actual == $tarea_liviana->id_cliente)
				<a data-toggle="modal" data-target="#cargar_documentos_tarea_liviana" class="btn-ejornal btn-ejornal-success" href="#">
					<i class="fas fa-plus-circle"></i> Crear certificado
				</a>
				@endif

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
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-lg-4 col-md-3 col-sm-12 text-center">
							<i class="fas fa-user fa-10x"></i>
							<br>
							<br>
							<h5>{{$tarea_liviana->nombre}}</h5>

							@if(auth()->user()->id_cliente_actual != $tarea_liviana->id_cliente)
							<span class="badge badge-dark">transferido</span>
							@endif
						</div>
						<div class="col-lg-4 col-md-5 col-sm-12">
							<ul class="list-group list-group-flush">
								<li class="list-group-item">
									<b>Tipo: </b> {{$tarea_liviana->nombre_tarea_liviana}}
								</li>
								<li class="list-group-item">
									<b>Fecha inicio: </b> {{ (!empty($tarea_liviana->fecha_inicio)) ?
									date('d/m/Y',strtotime($tarea_liviana->fecha_inicio)) : "" }}
								</li>
								<li class="list-group-item">
									<b>Fecha final: </b> {{ (!empty($tarea_liviana->fecha_final)) ?
									date('d/m/Y',strtotime($tarea_liviana->fecha_final)) : "" }}
								</li>
								<li class="list-group-item">
									<b>Fecha en que regresó: </b> {{ (!empty($tarea_liviana->fecha_regreso_trabajar)) ?
									date('d/m/Y',strtotime($tarea_liviana->fecha_regreso_trabajar)) : "" }}
								</li>
							</ul>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12">
							<ul class="list-group list-group-flush">
								<li class="list-group-item">
									<b>Email: </b> {{$tarea_liviana->email}}
								</li>
								<li class="list-group-item"><b>Estado: </b>
									@if ($tarea_liviana->estado == 1)
									Activo
									@else
									Inactivo
									@endif
								</li>
								<li class="list-group-item">
									<b>Telefono: </b> {{$tarea_liviana->telefono}}
								</li>
								<li class="list-group-item">
									<b>Archivo adjunto: </b>
									<br>
									<br>
									@if ($tarea_liviana->archivo == null)
									No se adjuntó un archivo
									@else
									<a class="btn-ejornal btn-ejornal-gris-claro"
										href="{{route('tareas_livianas.archivo', $tarea_liviana->id)}}">
										<i class="fa fa-file"></i>{{$tarea_liviana->archivo}}
									</a>
									@endif
								</li>
							</ul>
						</div>
					</div>
				</div>

				<div class="col-md-12">
					@if (isset($documentacion_tarea_liviana) && !empty($documentacion_tarea_liviana) &&
					count($documentacion_tarea_liviana) > 0)

					@foreach ($documentacion_tarea_liviana as $documentacion)
					<div class="accordion mb-4" id="accordionExample">
						<div class="card">
							<div class="card-header bg-dark text-white cabecera_consultas_historial" id="headingOne">
								<h2 class="mb-0 d-flex">
									<button class="btn btn-link btn-block text-left" type="button"
										data-toggle="collapse" data-target="#collapse_{{ $documentacion->id }}"
										aria-expanded="true" aria-controls="collapse_{{ $documentacion->id }}">
										<b>Institución:</b> {{$documentacion->institucion}}
									</button>
									<i class="fal fa-chevron-circle-down text-white"></i>
								</h2>
							</div>
							<div id="collapse_{{ $documentacion->id }}" class="collapse show"
								aria-labelledby="headingOne" data-parent="#accordionExample">
								<div class="card-body">
									<div class="card_consultas">
										<div class="card_consulta">
											<ul class="list-group list-group-flush">
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Fecha del
														documento:
													</span>
													{{ (!empty($documentacion->fecha_documento)) ?
													date('d/m/Y',strtotime($documentacion->fecha_documento)) : "" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Fecha última
														actualización:
													</span>
													{{ (!empty($documentacion->updated_at)) ?
													date('d/m/Y',strtotime($documentacion->updated_at)) : "" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Médico: </span>
													{{$documentacion->medico}}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Matrícula
														provincial: </span>
													{{ (!empty($documentacion->matricula_provincial)) ?
													$documentacion->matricula_provincial : "No fue cargada" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">
														Matrícula nacional:
													</span>
													{{ (!empty($documentacion->matricula_nacional)) ?
													$documentacion->matricula_nacional : "No fue cargada" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Diagnóstico:
													</span>
													<p>{{$documentacion->diagnostico}}</p>
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Observaciones:
													</span>
													<p>{{$documentacion->observaciones}}</p>
												</li>
												@if ($documentacion->user != null)
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">User que
														registra: </span>
													<p>{{$documentacion->user}}</p>
												</li>
												@endif
											</ul>
											<br>

											<h6 style="font-weight: 600;" class="text_black">Acciones:</h6>
											<div class="d-flex mr-4">

												@if(auth()->user()->id_cliente_actual == $tarea_liviana->id_cliente)
												<small class="text-muted">
													<h6 class="">Editar: </h6>
													<a class="editar_documentos_tarea_liviana btn btn-primary btn-sm"
														href="#!" data-id="{{$documentacion->id}}">
														<i class="fas fa-pen"></i>
													</a>
												</small>
												@endif

												<small class="text-muted">
													<h6 class="ml-4 mb-3">Archivo subido: </h6>
													<a class="btn-ejornal btn-ejornal-gris-claro ml-4"
														href="{{route('documentacion_liviana.archivo', $documentacion->id)}}">
														<i class="fa fa-file"></i>{{$documentacion->archivo}}
													</a>
												</small>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					@endforeach

					@else
					<div class="alert alert-danger" role="alert">
						<h4 class="alert-heading">Sin datos</h4>
						<p>No hay certificados de esta tarea liviana</p>
					</div>
					@endif


				</div>
			</div>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>




<!-- Modal -->
<div class="modal fade" id="cargar_documentos_tarea_liviana" tabindex="-1"
	aria-labelledby="cargar_documentos_tarea_liviana_titulo" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="cargar_documentos_tarea_liviana_titulo">Crear certificado</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="row p-4">
					<form id="form_crear_evento_tarea_liviana"
						action="{{action('EmpleadosTareasLivianasDocumentacion@store')}}" enctype="multipart/form-data"
						accept-charset="UTF-8" method="post">
						{{ csrf_field() }}
						<input type="hidden" name="id_tarea_liviana" value="{{$tarea_liviana->id}}">
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
						<button id="submit_crear_documentacion_tarea_liviana" class="btn-ejornal btn-ejornal-success">Crear certificado</button>
					</form>
				</div>

			</div>

		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="modal_edit_docu_tarea_liviana" tabindex="-1"
	aria-labelledby="editar_documentos_tarea_liviana_titulo" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editar_documentos_tarea_liviana_titulo">Editar certificado</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="row p-4">

					<form id="form_editar_documentacion_tarea_liviana" action="" enctype="multipart/form-data"
						accept-charset="UTF-8" method="post">
						{{ csrf_field() }}
						<input type="hidden" name="id_doc" value="">
						<input class="form_edit_tarea_liviana_doc_id_tarea_liviana" type="hidden"
							name="id_tarea_liviana" value="{{$tarea_liviana->id}}">
						<input name="_method" type="hidden" value="PUT">
						<div class="form-row">
							<div class="form-group col-md-6">
								<label>Institución</label>
								<input required name="institucion" type="text" class="form-control" placeholder="">
							</div>
							<div class="form-group col-md-6">
								<label>Medico</label>
								<input required name="medico" type="text" class="form-control" placeholder="">
							</div>
							<div class="form-group col-md-6">
								<label>Matricula provincial</label>
								<input name="matricula_provincial" type="text" class="form-control" placeholder="">
							</div>
							<div class="form-group col-md-6">
								<label>Matrícula nacional</label>
								<input disabled name="matricula_nacional" type="text" class="form-control"
									placeholder="">
							</div>
							<div class="form-group col-md-6">
								<label>Fecha documento original</label>
								<input disabled name="fecha_documento" required id="data_picker_edit_doc_tarea_liviana"
									type="datetime" class="form-control" placeholder="">
							</div>
							<div class="form-group col-md-6">
								<label>Fecha en que lo estas modificando</label>
								<input disabled name="fecha_documento_ult_modif" required
									id="data_picker_edit_doc_tarea_liviana_ult_modif" type="datetime"
									class="form-control" placeholder="">
							</div>
							<div class="form-group col-md-6">
								<label>Diagnostico</label>
								<textarea required name="diagnostico" class="form-control" rows="3"></textarea>
							</div>
							<div class="form-group col-md-6">
								<label>Observaciones</label>
								<textarea name="observaciones" class="form-control" rows="3"></textarea>
							</div>
							<div class="form-group col-md-12">
								<div class="alert alert-warning" role="alert">
									<h4 class="alert-heading">Archivos</h4>
									<p>Si subes un nuevo archivo pisará el actual</p>
									<hr>
									@if (isset($documentacion) && !empty($documentacion))
									<p class="mb-0">
										<a class="btn-ejornal btn-ejornal-dark archivo_edit_doc_tarea_liviana"
											href="{{route('documentacion_liviana.archivo', $documentacion->id)}}">
											<i class="fa fa-file"></i> Descargar archivo actual
										</a>
									</p>
									@endif
									<br>
									<label>Subir nuevo archivo</label>
									<input name="archivo" type="file" class="form-control-file">
								</div>

							</div>
						</div>
						<button class="btn-ejornal btn-ejornal-success btn_editar_documentacion_tarea_liviana"
							type="submit" name="button">Guardar cambios</button>
					</form>
				</div>

			</div>

		</div>
	</div>
</div>


@endsection