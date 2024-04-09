@extends('partials.layout')

@section('title', 'Empleado')

@section('content')


<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">

			<h2>Certificados de un ausentismo</h2>
			<p>Aquí puedes ver y cargar nuevos certificados de ausentismo</p>

			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-gris-claro" href="{{ url('empleados/ausentismos') }}?{{$_SERVER['QUERY_STRING']}}">
					<i class="fas fa-arrow-circle-left fa-fw"></i> <span>Volver</span>
				</a>
				<button data-toggle="modal" data-target="#modal_certificado" class="btn-ejornal btn-ejornal-success" href="#">
					<i class="fas fa-plus-circle fa-fw"></i> <span>Crear Certificado</span>
				</button>
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

			{{-- HEADER --}}
			<div class="row">

				<div class="col-lg-4 col-md-3 col-sm-12 text-center">
					@if ($ausencia->trabajador->foto)
					<div class="foto-perfil" style="background-image: url({{ $ausencia->trabajador->photo_url }})"></div>
					@else
					<i class="fas fa-user fa-10x"></i>
					@endif
					<br>
					<br>
					<h5>
						<a href="{{url('empleados/nominas/'.$ausencia->trabajador->id)}}" class="text-info" title="Ver Historial">{{$ausencia->trabajador->nombre}}</a>
					</h5>
				</div>

				<div class="col-lg-8 col-md-9 col-sm-12">
					<h4 class="mb-1">Datos del Ausentismo</h4>
					<hr class="hr-line-dashed">

					<div class="row">
						<div class="col-lg-6">

							<ul class="list-group list-group-flush">
								<li class="list-group-item">
									<b>Tipo: </b> {{$ausencia->tipo->nombre}}
								</li>
								<li class="list-group-item">
									<b>Fecha inicio: </b> {{ (!empty($ausencia->fecha_inicio)) ?
									date('d/m/Y',strtotime($ausencia->fecha_inicio)) : "" }}
								</li>
								<li class="list-group-item">
									<b>Fecha final: </b> {{ (!empty($ausencia->fecha_final)) ?
									date('d/m/Y',strtotime($ausencia->fecha_final)) : "" }}
								</li>
								<li class="list-group-item">
									<b>Fecha en que regresó: </b> {{ (!empty($ausencia->fecha_regreso_trabajar)) ?
									date('d/m/Y',strtotime($ausencia->fecha_regreso_trabajar)) : "" }}
								</li>
								<li class="list-group-item">
									<b>Usuario que lo cargó:</b> {{ $ausencia->user }}
								</li>
							</ul>
						</div>

						<div class="col-lg-6">
							<ul class="list-group list-group-flush">
								<li class="list-group-item">
									<b>Email: </b> {{$ausencia->trabajador->email}}
								</li>
								<li class="list-group-item"><b>Estado: </b>
									@if ($ausencia->estado == 1)
									Activo
									@else
									Inactivo
									@endif
								</li>
								<li class="list-group-item">
									<b>Telefono: </b> {{$ausencia->trabajador->telefono}}
								</li>
								<li class="list-group-item">
									<b>Archivo adjunto: </b>
									@if ($ausencia->archivo == null)
									No se adjuntó un archivo
									@else
									<a target="_blank" class="btn btn-info btn-tiny"
										href="{{route('ausentismos.archivo', $ausencia->id)}}">
										<i class="fa fa-file fa-fw"></i> {{$ausencia->archivo}}
									</a>
									@endif
								</li>
							</ul>

						</div>
					</div>
				</div>

			</div>


			{{-- CERTIFICADOS --}}
			<div class="mt-5 table-responsive">
				<table data-table="ausentismos" class="table small table-bordered table-sm w-100 border">
					<thead>
						<tr class="bg-light">
							<th>Institución</th>
							<th>Fecha</th>
							<th>Última actualización</th>
							<th>Médico</th>
							<th>Matrícula Provincial</th>
							<th>Matrícula Nacional</th>
							<th>Diagnóstico</th>
							<th>Observaciones</th>
							<th>Ususario que registra</th>
							<th>Archivos</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody>
						@if($documentacion_ausentismo) @foreach($documentacion_ausentismo as $documentacion)
						<tr>
							<td class="align-middle">{{ $documentacion->institucion }}</td>
							<td class="align-middle">{{ $documentacion->fecha_documento->format('d/m/Y') }}</td>
							<td class="align-middle">{{ $documentacion->updated_at->format('d/m/Y') }}</td>
							<td class="align-middle">{{ $documentacion->medico }}</td>
							<td class="align-middle">{{ $documentacion->matricula_provincial }}</td>
							<td class="align-middle">{{ $documentacion->matricula_nacional }}</td>
							<td class="align-middle">{{ $documentacion->diagnostico }}</td>
							<td class="align-middle">{{ $documentacion->observaciones }}</td>
							<td class="align-middle">{{ $documentacion->user }}</td>
							<td class="align-middle">
								@if($documentacion->archivos)

								@foreach($documentacion->archivos as $archivo)
								<a target="_blank" class="btn btn-info btn-tiny mr-3 mb-1 d-block" href="{{route('documentacion_ausentismo.archivo', $archivo->id)}}">
									<i class="fa fa-download fa-fw"></i> <span>{{$archivo->archivo}}</span>
								</a>
								@endforeach

								@else
								<span>no se adjuntaron archivos</span>
								@endif
							</td>
							<td class="align-middle">
								<button data-toggle="editar-certificado" class="btn-ejornal btn-ejornal-dark" data-id="{{$documentacion->id}}">
									<i class="fas fa-pen fa-fw"></i> <span>Editar</span>
								</button>
							</td>
						</tr>
						@endforeach @endif
					</tbody>
				</table>

			</div>


		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>




<!-- Modal Certificado -->
<div id="modal_certificado" class="modal fade" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" >Crear certificado</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">

				<div class="row p-4">
					<form data-form="form-certificado" action="{{action('EmpleadosAusentismoDocumentacionController@store')}}" enctype="multipart/form-data" accept-charset="UTF-8" method="post">

						{{ csrf_field() }}

						<input type="hidden" name="id_ausentismo" value="{{$ausencia->id}}">
						<input type="hidden" name="id" value="0">
						<input type="hidden" name="matricula_validada" value="0">

						<div class="form-row">
							<div class="form-group col-md-6">
								<label>Institución <span style="color: red;">*</span></label>
								<input required name="institucion" type="text" class="form-control" placeholder="">
							</div>
							<div class="form-group col-md-6">
								<label>Médico <span style="color: red;">*</span></label>
								<input required name="medico" type="text" class="form-control" placeholder="">
							</div>
							<div class="form-group col-md-6">
								<label>Matrícula Provincial</label>
								<input name="matricula_provincial" type="text" class="form-control" placeholder="">
							</div>
							<div class="form-group col-md-6">
								<label class="d-flex align-items-center">
									Matrícula Nacional
									<i data-toggle="certificado-validar-icon" data-value="ok" style="color: green; margin-left: 5px;" class="fas fa-check-circle d-none"></i>
									<i data-toggle="certificado-validar-icon" data-value="fail" style="color: red; margin-left: 5px;" class="fas fa-times-circle d-none"></i>
								</label>
								<div class="d-flex">
									<input style="max-width: 200px; margin-right: 5px;" name="matricula_nacional" type="text" class="form-control" placeholder="">
									<button data-toggle="validar-matricula" type="button" class="btn-ejornal btn-ejornal-gris-claro" href="#">
										<i class="fas fa-user-check fa-fw"></i> <span>Validar</span>
									</button>
								</div>
							</div>
							<div class="form-group col-md-6">
								<label>Fecha documento <span style="color: red;">*</span></label>
								<input name="fecha_documento" required id="data_picker_gral" type="text" class="form-control" placeholder="[click para desplegar calendario]" readonly>
							</div>
							<div class="form-group col-md-6"></div>
							{{-- <div class="form-group col-md-6">
								<label>Archivo <span style="color: red;">*</span> </label>
								<input required name="archivo" type="file" class="form-control-file">
							</div> --}}
							<div class="form-group col-md-6">
								<label>Diagnóstico <span style="color: red;">*</span></label>
								<textarea required name="diagnostico" class="form-control" rows="5"></textarea>
							</div>
							<div class="form-group col-md-6">
								<label>Observaciones</label>
								<textarea name="observaciones" class="form-control" rows="5"></textarea>
							</div>
						</div>

						<hr class="hr-line-dashed">

						<div class="table-responsive">
							<table data-table="certificaciones_archivos" class="table table-sm small w-100 table-bordered border">
								<thead>
									<tr class="bg-light">
										<th colspan="2">
											<label for="" class="mb-0">Adjuntar archivos <span style="color: red;">*</span></label>
											<span class="small text-muted font-italic">Puedes adjuntar más de 1 archivo</span>
										</th>
									</tr>
								</thead>
								<tbody>

									@include('templates.tr-certificado-ausentismo')

								</tbody>
								<tfoot>
									<tr class="bg-light">
										<th colspan="2">
											<button data-toggle="agregar-archivo-cert" class="btn btn-tiny btn-dark text-light" type="button">
												<i class="fal fa-plus fa-fw"></i> <span>Agregar archivo</span>
											</button>
										</th>
									</tr>
								</tfoot>
							</table>
						</div>


						<hr class="hr-line-dashed">

						<button class="btn-ejornal btn-ejornal-success">Crear Certificado</button>

					</form>
				</div>

			</div>

		</div>
	</div>
</div>


<!-- Editar Certificado -->
{{-- <div class="modal fade" id="modal_edit_docu_ausentismo" tabindex="-1"
	aria-labelledby="editar_documentos_ausentismo_titulo" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editar_documentos_ausentismo_titulo">Editar certificado</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="row p-4">

					<form id="form_editar_documentacion_ausencia" action="" enctype="multipart/form-data"
						accept-charset="UTF-8" method="post">
						{{ csrf_field() }}
						<input type="hidden" name="id_doc" value="">
						<input class="form_edit_ausencia_doc_id_ausentismo" type="hidden" name="id_ausentismo"
							value="{{$ausencia->id}}">
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
								<input disabled name="fecha_documento" required type="datetime" class="form-control"
									placeholder="">
							</div>
							<div class="form-group col-md-6">
								<label>Fecha en que lo estas modificando</label>
								<input disabled name="fecha_documento_ult_modif" required
									id="data_picker_edit_doc_ausentismo_ult_modif" type="datetime" class="form-control"
									placeholder="">
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
										<a class="btn-ejornal btn-ejornal-dark archivo_edit_doc_ausencia"
											href="{{route('documentacion_ausentismo.archivo', $documentacion->id)}}">
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
						<button class="btn-ejornal btn-ejornal-success btn_editar_documentacion_ausentismo"
							type="submit" name="button">Guardar cambios</button>
					</form>
				</div>

			</div>

		</div>
	</div>
</div>
 --}}

@endsection