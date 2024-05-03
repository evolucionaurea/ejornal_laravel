@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">

	@include('partials.sidebar_empleados')

	<div id="page-content-wrapper">

		@include('partials.nav_sup')

		<div class="cabecera">
			<h2>Creación de ausencias</h2>
			<p>Aquí puedes cargar a los ausencias que formarán parte de la nómina de la empresa</p>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-gris-claro" href="{{url('empleados/ausentismos')}}">
					<i class="fas fa-arrow-alt-circle-left fa-fw"></i> Volver
				</a>
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



		<form action="{{action('EmpleadosAusentismosController@store')}}" accept-charset="UTF-8" method="post"
			enctype="multipart/form-data">

			@csrf

			<div class="tarjeta">
				{{-- AUSENTISMO --}}

				<h4>Ausentismo</h4>

				<div class="form-row">
					<div class="form-group col-lg-3">
						<label>Trabajador *</label>
						<select required name="trabajador" class="form-control form-control-sm select_2">
							<option value="">--Seleccionar--</option>
							@foreach ($trabajadores as $trabajador)
							<option value="{{$trabajador->id}}" {{old("trabajador")==$trabajador->id ? 'selected' : ''}}
								>{{$trabajador->nombre}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group col-lg-3">
						<label>
							Tipo *
							@if (auth()->user()->permiso_desplegables == 1)
							<a style="color: #6f9eab; margin-right: 10px;" data-toggle="modal"
								data-target="#crear_tipo_ausentismo" href="#">
								<i class="fas fa-plus-circle"></i>
							</a>
							<a style="color: #6f9eab;" data-toggle="modal" data-target="#ver_tipo_ausentismo" href="#">
								<i class="fas fa-eye"></i>
							</a>
							@endif
						</label>
						<select required name="tipo" class="form-control form-control-sm select_2">
							<option value="">--Seleccionar--</option>
							@foreach ($ausentismo_tipos as $tipo)
							<option value="{{$tipo->id}}" {{old("tipo")==$tipo->id || session('id_tipo')==$tipo->id ?
								'selected' : ''}}>
								{{$tipo->nombre}}
							</option>
							@endforeach
						</select>
					</div>


					<div class="form-group col-lg-2">
						<label>Fecha inicio *</label>
						<input readonly required name="fecha_inicio" type="text" class="form-control" value="{{ old("
							fecha_inicio") }}">
					</div>

					<div class="form-group col-lg-2">
						<label>Fecha final *</label>
						<input readonly required name="fecha_final" type="text" class="form-control" value="{{ old("
							fecha_final") }}">
					</div>

					<div class="form-group col-lg-2">
						<label>Fecha Regreso</label>
						<input readonly name="fecha_regreso_trabajar" type="text" class="form-control" value="{{ old("
							fecha_regreso_trabajar") }}">
					</div>



					{{-- <div class="form-group col-lg-3">
						<label id="inputGroupFileAddon01">Documentación</label>

						<div class="input-group mb-3">
							<div class="custom-file">
								<input name="archivo" type="file" class="custom-file-input clickable"
									id="inputGroupFile01">
								<label class="custom-file-label" for="inputGroupFile01">...</label>
							</div>
						</div>

					</div> --}}

					<div class="form-group col-lg-12">
						<label>Comentario</label>
						<textarea name="comentario" class="form-control" rows="3">{{ old("comentario") }}</textarea>
					</div>

				</div>


			</div>

			{{-- COMUNICACION --}}
			<div class="tarjeta">

				<h4>Comunicación</h4>
				<div class="form-row">
					<div class="form-group col-lg-3">
						<label>
							Tipo *
							<a style="color: #6f9eab; margin-right: 10px;" data-toggle="modal"
								data-target="#crear_tipo_comunicacion" href="#">
								<i class="fas fa-plus-circle"></i>
							</a>
							<a style="color: #6f9eab;" data-toggle="modal" data-target="#ver_tipo_comunicacion"
								href="#">
								<i class="fas fa-eye"></i>
							</a>
						</label>
						<select required name="tipo_comunicacion" class="form-control form-control-sm select_2">
							<option value="">--Seleccionar--</option>
							@foreach ($tipo_comunicacion as $tipo_com)
							<option value="{{$tipo_com->id}}" {{ old('tipo_comunicacion')==$tipo_com->id ? 'selected' :
								'' }} >{{$tipo_com->nombre}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-lg-9">
						<label>Descripción *</label>
						<textarea required name="descripcion" class="form-control"
							rows="3">{{ old("descripcion") }}</textarea>
					</div>
				</div>

				{{-- <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Cargar ausencia y
					comunicación</button> --}}


			</div>

			{{-- DOCUMENTACION --}}
			<div class="tarjeta">

				<input type="hidden" name="matricula_validada" value="0">

				<div class="tarjeta-header d-flex align-items-center">

					<h4 class="mr-4 my-0">Certificado</h4>

					<div class="input-group">
						<div class="input-group-prepend">
							<div class="input-group-text">
								<input name="incluir_certificado" type="checkbox" {{ old("incluir_certificado")=='on'
									? 'checked' : '' }}>
							</div>
						</div>
						<div class="input-group-append">
							<small data-toggle="incluir-certificado" class="form-control clickable">Incluir
								Certificado</small>
						</div>

					</div>

				</div>


				<div id="certificado_content" class="tarjeta-body" style="{{ old(" incluir_certificado")=='on' ? ''
					: 'display:none' }}">
					<hr class="hr-line-dashed">

					<div class="form-row">
						<div class="form-group col-lg-3">
							<label>Institución <span style="color: red;">*</span></label>
							<input name="cert_institucion" type="text" class="form-control" placeholder=""
								value="{{ old('cert_institucion') }}">
						</div>
						<div class="form-group col-lg-3">
							<label>Médico <span style="color: red;">*</span></label>
							<input name="cert_medico" type="text" class="form-control" placeholder=""
								value="{{ old('cert_medico') }}">
						</div>
						<div class="form-group col-lg-3">
							<label>Matrícula provincial</label>
							<input name="cert_matricula_provincial" type="text" class="form-control" placeholder=""
								value="{{ old('cert_matricula_provincial') }}">
						</div>
						<div class="form-group col-lg-3">
							<label class="d-flex align-items-center">
								Matrícula nacional
								<i data-toggle="certificado-validar-icon" data-value="ok"
									style="color: green; margin-left: 5px;" class="fas fa-check-circle d-none"></i>
								<i data-toggle="certificado-validar-icon" data-value="fail"
									style="color: red; margin-left: 5px;" class="fas fa-times-circle d-none"></i>
							</label>
							<div class="d-flex">
								<input style="max-width: 200px; margin-right: 5px;" name="cert_matricula_nacional"
									type="text" class="form-control" placeholder=""
									value="{{ old('cert_matricula_nacional') }}">
								<button data-toggle="validar-matricula" class="btn-ejornal btn-ejornal-gris-claro"
									type="button">
									<i class="fas fa-user-check"></i> Validar
								</button>
							</div>
						</div>
						<div class="form-group col-lg-2">
							<label>Fecha documento <span style="color: red;">*</span></label>
							<input name="cert_fecha_documento" type="text" readonly class="form-control" placeholder=""
								value="{{ old('cert_fecha_documento') }}">
						</div>

						<div class="form-group col-lg-5">
							<label>Diagnóstico <span style="color: red;">*</span></label>
							<textarea name="cert_diagnostico" class="form-control"
								rows="3">{{ old('cert_diagnostico') }}</textarea>
						</div>
						<div class="form-group col-lg-5">
							<label>Observaciones</label>
							<textarea name="cert_observaciones" class="form-control"
								rows="3">{{ old('cert_observaciones') }}</textarea>
						</div>
					</div>

					<hr>

					<div class="row">
						<div class="form-group col-lg-6">

							<div class="table-responsive">
								<table data-table="certificaciones_archivos"
									class="table table-sm small w-100 table-bordered border">
									<thead>
										<tr class="bg-light">
											<th colspan="2">
												<label for="" class="mb-0">Adjuntar archivos <span
														style="color: red;">*</span></label>
												<span class="small text-muted font-italic">Puedes adjuntar más de 1
													archivo</span>
											</th>
										</tr>
									</thead>
									<tbody>
										{{-- @include('templates.tr-certificado-ausentismo') --}}
									</tbody>
									<tfoot>
										<tr class="bg-light">
											<th colspan="2">
												<button data-toggle="agregar-archivo-cert"
													class="btn btn-tiny btn-dark text-light" type="button">
													<i class="fal fa-plus fa-fw"></i> <span>Agregar archivo</span>
												</button>
											</th>
										</tr>
									</tfoot>
								</table>
							</div>

						</div>

					</div>
				</div>


			</div>

			<div class="text-center my-5">
				<button class="btn-ejornal btn-ejornal-success btn-ejornal-lg" type="submit" name="button">Cargar
					ausencia y comunicación</button>
			</div>


		</form>


	</div>
</div>





<!-- Modal Crear tipo ausentismo -->
<div class="modal fade" id="crear_tipo_ausentismo" tabindex="-1" aria-labelledby="tipo_ausentismo_titulo"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="tipo_ausentismo_titulo">Crear tipo de Ausencia</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<form action="{{action('EmpleadosAusentismosController@tipo')}}" accept-charset="UTF-8" method="post">
					{{ csrf_field() }}
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Nombre</label>
								<input name="nombre" type="text" class="form-control form-control-sm" placeholder="">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Genera Ausentismo</label>
								<select class="form-control form-control-sm" name="incluir_indice">
									<option value="1">Si</option>
									<option value="0">No</option>
								</select>
							</div>
						</div>
					</div>
					<button type="submit" class="btn-ejornal btn-ejornal-success">Crear tipo</button>
					<button type="button" class="btn-ejornal btn-ejornal-gris-claro"
						data-dismiss="modal">Cerrar</button>
				</form>

			</div>
		</div>
	</div>
</div>
<!-- Modal Ver tipo Ausentismo -->
<div class="modal fade" id="ver_tipo_ausentismo" tabindex="-1" aria-labelledby="ver_tipo_ausentismo_titulo"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="ver_tipo_ausentismo_titulo">Tipos de Ausencia cargados</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">


				<div class="row">
					<div class="col-md-12">
						<table class="table table-striped table-hover table-sm small">
							<thead>
								<tr>
									<th>Tipo</th>
									<th class="text-center">Genera Ausentismo</th>
									<th class="text-right">Acciones</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($ausentismo_tipos as $tipo)
								<tr>
									<td class="align-middle">{{$tipo->nombre}}</td>
									<td class="text-center align-middle">
										@if($tipo->incluir_indice)
										<span>si</span>
										@else
										<span>no</span>
										@endif
									</td>
									<td class="align-middle">
										<div class="acciones_tabla text-right justify-content-end">

											<a data-id="{{ $tipo->id }}" data-text="{{ $tipo->nombre }}"
												data-color="{{ $tipo->color }}"
												data-indice="{{ $tipo->incluir_indice }}"
												class=" btn_editar_tipo_ausentismo" title="Edit" data-toggle="modal"
												data-target="#editar_tipo_ausentismo">
												<i class="fas fa-pen"></i>
											</a>
											<form class="" action="{{route('ausentismos.tipo_delete', $tipo->id)}}"
												method="post">
												{{ csrf_field() }}
												<input type="hidden" name="_method" value="DELETE">
												<button title="Eliminar" type="submit">
													<i class="fas fa-trash"></i>
												</button>
											</form>
										</div>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
<!-- Modal Editar tipo ausentismo -->
<div class="modal fade" id="editar_tipo_ausentismo" tabindex="-1" aria-labelledby="editar_tipo_ausentismo_titulo"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editar_tipo_ausentismo_titulo">Editar tipo de ausentismo</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="{{action('EmpleadosAusentismosController@editarTipo')}}" accept-charset="UTF-8"
					method="post">
					{{ csrf_field() }}
					<input name="id_tipo" type="hidden" value="">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Nombre</label>
								<input name="tipo_editado" type="text" class="form-control form-control-sm"
									placeholder="Nombre">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Color</label>
								<input name="color" type="text" class="form-control form-control-sm"
									placeholder="Color">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Genera Ausentismo</label>
								<select class="form-control form-control-sm" name="editar_incluir_indice">
									<option value="0">No</option>
									<option value="1">Si</option>
								</select>
							</div>
						</div>
					</div>
					<button type="submit" class="btn-ejornal btn-ejornal-success">Editar</button>
					<button type="button" class="btn-ejornal btn-ejornal-gris-claro"
						data-dismiss="modal">Cerrar</button>
				</form>

			</div>
		</div>
	</div>
</div>



<!-- Modal Crear tipo comunicacion -->
<div class="modal fade" id="crear_tipo_comunicacion" tabindex="-1" aria-labelledby="tipo_comunicacion_titulo"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="tipo_comunicacion_titulo">Crear tipo de Comunicación</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="row">
					<div class="col-md-12">
						<form action="{{action('EmpleadosComunicacionesController@tipo')}}" accept-charset="UTF-8"
							method="post">
							{{ csrf_field() }}
							<div class="form-group">
								<label>Nombre</label>
								<input name="nombre" type="text" class="form-control form-control-sm" placeholder="">
							</div>
							<button type="submit" class="btn-ejornal btn-ejornal-success">Crear tipo</button>
							<button type="button" class="btn-ejornal btn-ejornal-gris-claro"
								data-dismiss="modal">Cerrar</button>
						</form>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
<!-- Modal Ver tipo Comunicacion -->
<div class="modal fade" id="ver_tipo_comunicacion" tabindex="-1" aria-labelledby="ver_tipo_comunicacion_titulo"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="ver_tipo_comunicacion_titulo">Tipos de Comunicación cargados</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">


				<div class="row">
					<div class="col-md-12">
						<table class="table table-striped table-hover table-sm ">
							<thead>
								<tr>
									<th>Tipo</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($tipo_comunicacion as $value)
								<tr>
									<td>{{$value->nombre}}</td>
									<td class="acciones_tabla" scope="row">
										<form class="" action="{{route('comunicaciones.tipo_delete', $value->id)}}"
											method="post">
											{{ csrf_field() }}
											<input type="hidden" name="_method" value="DELETE">
											<button title="Eliminar" type="submit">
												<i class="fas fa-trash"></i>
											</button>
										</form>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>




@endsection