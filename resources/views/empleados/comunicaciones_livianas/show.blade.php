@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Comunicaciones adecuadas</h2>
			<p>Aquí puedes ver y cargar nuevas comunicaciones adecuadas</p>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-gris-claro" href="{{ url('empleados/tareas_livianas') }}">
					<i class="fas fa-arrow-circle-left"></i>Volver
				</a>

				@if( auth()->user()->id_cliente_actual == $tarea_liviana->id_cliente)
				<a data-toggle="modal" data-target="#cargar_comunicaciones_liviana" class="btn-ejornal btn-ejornal-success" href="#">
					<i class="fas fa-plus-circle"></i> Crear comunicación adecuada
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

							@if( auth()->user()->id_cliente_actual != $tarea_liviana->id_cliente)
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
					@if (isset($comunicacione_tarea_liviana) && !empty($comunicacione_tarea_liviana) &&
					count($comunicacione_tarea_liviana) > 0)

					@foreach ($comunicacione_tarea_liviana as $comunicacion)
					<div class="accordion mb-4" id="accordionExample">
						<div class="card">
							<div class="card-header bg-dark text-white cabecera_consultas_historial" id="headingOne">
								<h2 class="mb-0 d-flex">
									<button class="btn btn-link btn-block text-left" type="button"
										data-toggle="collapse" data-target="#collapse_{{ $comunicacion->id }}"
										aria-expanded="true" aria-controls="collapse_{{ $comunicacion->id }}">
										<h6><b>Comunicación:</b> {{$comunicacion->nombre}}</h6>
									</button>
									<i class="fal fa-chevron-circle-down text-white"></i>
								</h2>
							</div>
							<div id="collapse_{{ $comunicacion->id }}" class="collapse show"
								aria-labelledby="headingOne" data-parent="#accordionExample">
								<div class="card-body">
									<div class="card_consultas">
										<div class="card_consulta">
											<ul class="list-group list-group-flush">
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Descripcion:
													</span>
													{{ (!empty($comunicacion->descripcion)) ?
													$comunicacion->descripcion : "" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Última
														actualización: </span>
													{{ (!empty($comunicacion->updated_at)) ?
													date('d/m/Y',strtotime($comunicacion->updated_at)) :
													"" }}
												</li>
											</ul>
											<br>
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
						<p>No hay documentaciones livianas de esta tarea liviana</p>
					</div>
					@endif


				</div>
			</div>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>




<!-- Modal -->
<div class="modal fade" id="cargar_comunicaciones_liviana" tabindex="-1"
	aria-labelledby="cargar_comunicaciones_liviana_titulo" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="cargar_comunicaciones_liviana_titulo">Crear Comunicacion adecuada</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="row p-4">
					<form style="width: 100%;" action="{{action('EmpleadosComunicacionesLivianas@store')}}"
						enctype="multipart/form-data" accept-charset="UTF-8" method="post">
						{{ csrf_field() }}
						<input type="hidden" name="id_tarea_liviana" value="{{$tarea_liviana->id}}">
						<div class="form-row">
							<div class="form-group col-md-12">
								<label>Tipo de comunicación adecuada</label>
								<select class="form-control" name="id_tipo">
									@foreach ($tipos_comunicaciones_livianas as $tipo)
									<option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group col-md-12">
								<label>Descripcion</label>
								<textarea required name="descripcion" class="form-control" rows="3"></textarea>
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