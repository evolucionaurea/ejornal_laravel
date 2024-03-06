@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Creación de ausencias</h2>
			<p>Aquí puedes cargar a los ausencias que formarán parte de la nómina de la empresa</p>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-gris-claro" href="{{url('empleados/ausentismos')}}"><i
						class="fas fa-arrow-alt-circle-left"></i>Volver</a>
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
			<h4>Ausentismo</h4>

			<form action="{{action('EmpleadosAusentismosController@store')}}" accept-charset="UTF-8" method="post"
				enctype="multipart/form-data">

				@csrf

				<div class="form-row">
					<div class="form-group col-md-3">
						<label>Trabajador</label>
						<select required name="trabajador" class="form-control form-control-sm select_2">
							<option value="">--Seleccionar--</option>
							@foreach ($trabajadores as $trabajador)
							<option value="{{$trabajador->id}}" {{old("trabajador")==$trabajador->id ? 'selected' : ''}}
								>{{$trabajador->nombre}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-3">
						<label>
							Tipo
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
								'selected' : ''}}>{{$tipo->nombre}}
							</option>
							@endforeach
						</select>
					</div>


					<div class="form-group col-md-3">
						<label>Fecha inicio</label>
						<input readonly required name="fecha_inicio" type="text" class="form-control" value="{{ old("
							fecha_inicio") }}">
					</div>

					<div class="form-group col-md-3">
						<label>Fecha final</label>
						<input readonly required name="fecha_final" type="text" class="form-control" value="{{ old("
							fecha_final") }}">
					</div>

					<div class="form-group col-md-3">
						<label>Fecha en que regresó</label>
						<input readonly name="fecha_regreso_trabajar" type="text" class="form-control" value="{{ old("
							fecha_regreso_trabajar") }}">
					</div>



					<div class="form-group col-md-3">
						<label id="inputGroupFileAddon01">Documentación</label>

						<div class="input-group mb-3">
							<div class="custom-file">
								<input name="archivo" type="file" class="custom-file-input clickable"
									id="inputGroupFile01">
								<label class="custom-file-label" for="inputGroupFile01">...</label>
							</div>
						</div>


						{{-- <input name="archivo" type="file" class="form-control-file"> --}}
					</div>

					<div class="form-group col-md-6">
						<label>Comentario</label>
						<textarea name="comentario" class="form-control" rows="3">{{ old("comentario") }}</textarea>
					</div>

				</div>

				<hr>
				<h4>Comunicación</h4>
				<div class="form-row">
					<div class="form-group col-md-3">
						<label>
							Tipo
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
					<div class="form-group col-md-9">
						<label>Descripción</label>
						<textarea required name="descripcion" class="form-control"
							rows="3">{{ old("descripcion") }}</textarea>
					</div>
				</div>

				<button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Cargar ausencia y
					comunicación</button>

			</form>
		</div>

		{{-- Contenido de la pagina --}}
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