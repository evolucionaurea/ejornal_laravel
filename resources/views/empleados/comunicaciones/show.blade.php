@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">

			<h2>Comunicaciones de un ausentismo</h2>
			<p>Aquí puedes ver y cargar nuevas comunicaciones de ausentismo</p>

			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-gris-claro"
					href="{{ url('empleados/ausentismos') }}?{{$_SERVER['QUERY_STRING']}}">
					<i class="fas fa-arrow-circle-left fa-fw"></i> <span>Volver</span>
				</a>
				<a data-toggle="modal" data-target="#cargar_comunicaciones_ausentismo"
					class="btn-ejornal btn-ejornal-success" href="#">
					<i class="fas fa-plus-circle fa-fw"></i> <span>Crear Comunicación</span>
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

		<div class="tarjeta">

			{{-- HEADER --}}

			<div class="row">

				<div class="col-lg-4 col-md-3 col-sm-12 text-center">
					@if ($ausencia->trabajador->foto)
					<div class="foto-perfil" style="background-image: url({{ $ausencia->trabajador->photo_url }})">
					</div>
					@else
					<i class="fas fa-user fa-10x"></i>
					@endif
					<br>
					<br>
					<h5>
						<a href="{{url('empleados/nominas/'.$ausencia->trabajador->id)}}" class="text-info"
							title="Ver Historial">{{$ausencia->trabajador->nombre}}</a>
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


			{{-- COMUNICACIONES --}}
			<hr class="hr-line-dashed">
			<h4>Comunicaciones:</h4>
			<div class="mt-1 table-responsive">
				<table data-table="comunicaciones" class="table small table-bordered table-sm w-100 border">
					<thead>
						<tr class="bg-light">
							<th>Tipo</th>
							<th>Descripción</th>
							<th>Archivo</th>
							<th>Última Actualización</th>
							<th>Usuario que la creó</th>
						</tr>
					</thead>
					<tbody>
						@if($ausencia->comunicaciones)
						@foreach($ausencia->comunicaciones as $comunicacion)
						<tr>
							<td class="align-middle">{{ $comunicacion->tipo->nombre }}</td>
							<td class="align-middle">{{ $comunicacion->descripcion }}</td>
							<td class="align-middle">
								@if ($comunicacion->archivos->isNotEmpty())
								<div class="btn-group" role="group">
									<button type="button" class="btn btn-info btn-sm dropdown-toggle text-white"
										data-toggle="dropdown" aria-expanded="false">
										Archivos
									</button>
									<div class="dropdown-menu">
										@foreach ($comunicacion->archivos as $archivo)
										<a class="dropdown-item"
											href="{{ route('comunicaciones.verArchivo', ['id' => $archivo->id_comunicacion, 'hash' => $archivo->hash_archivo]) }}"
											target="_blank">
											{{ $archivo->archivo }}
										</a>
										@endforeach
									</div>
								</div>
								@else
								No se adjuntó
								@endif
							</td>
							<td class="align-middle">{{ $comunicacion->updated_at->format('d/m/Y') }}</td>
							<td class="align-middle">{{ $comunicacion->user ?? $ausencia->user }}</td>
						</tr>
						@endforeach @endif
					</tbody>
				</table>

			</div>

		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>




<!-- Modal -->
<div class="modal fade" id="cargar_comunicaciones_ausentismo" tabindex="-1"
	aria-labelledby="cargar_comunicaciones_ausentismo_titulo" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="cargar_comunicaciones_ausentismo_titulo">Crear Comunicación</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="row p-4">
					<form style="width: 100%;" action="{{action('EmpleadosComunicacionesController@store')}}"
						enctype="multipart/form-data" accept-charset="UTF-8" method="post">
						{{ csrf_field() }}
						<input type="hidden" name="id_ausentismo" value="{{$ausencia->id}}">
						<div class="form-row">
							<div class="form-group col-md-12">
								<label>Tipo de comunicación</label>
								<select class="form-control" name="id_tipo">
									@foreach ($tipo_comunicaciones as $tipo)
									<option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group col-md-12">
								<label>Descripción</label>
								<textarea required name="descripcion" class="form-control" rows="3"></textarea>
							</div>
							<div class="form-group col-md-12">
								<label>Archivos</label>
								<input type="file" multiple name="archivos[]" id="">
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