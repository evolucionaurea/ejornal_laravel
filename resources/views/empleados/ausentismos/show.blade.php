@extends('partials.layout')

@section('title', 'Ausentismo')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">

		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}


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


		<div class="cabecera">
			<h2>Detalles del Ausentismo</h2>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-gris-claro" href="{{ url('empleados/ausentismos') }}?{{$_SERVER['QUERY_STRING']}}">
					<i class="fas fa-arrow-circle-left fa-fw"></i> <span>Volver</span>
				</a>
			</div>
		</div>

		<div class="tarjeta">

			<div class="row">
				<div class="col-lg-3 text-center">


					@if ($ausentismo->trabajador->foto)
					<div class="foto-perfil" style="background-image: url({{ $ausentismo->trabajador->photo_url }})">
					</div>
					@else
					<i class="fas fa-user fa-10x"></i>
					@endif

				</div>
				<div class="col-lg-4">
					<div class="font-weight-bold">{{ $ausentismo->trabajador->nombre }}</div>
					<ul class="list-group">
						<li class="list-group-item p-2 small">DNI: {!! $ausentismo->trabajador->dni ?? '<i class="text-muted">[no cargado]</i>' !!}</li>
						<li class="list-group-item p-2 small">CUIL: {!! $ausentismo->trabajador->email ?? '<i class="text-muted">[no cargado]</i>' !!}</li>
						<li class="list-group-item p-2 small">Teléfono: {!! $ausentismo->trabajador->telefono ?? '<i class="text-muted">[no cargado]</i>' !!}</li>
						<li class="list-group-item p-2 small">
							Estado:
							@if( $ausentismo->id_cliente != $ausentismo->trabajador->id_cliente)
							<span class="badge badge-dark">transferido</span>
							@else
							<span class="badge badge-{{ $ausentismo->trabajador->estado ? 'success' : 'danger' }}">{{ $ausentismo->trabajador->estado ? 'activo' : 'inactivo' }}</span>
							@endif
						</li>
						<li class="list-group-item p-2 small">Sector: {{ $ausentismo->trabajador->sector }}</li>
						<li class="list-group-item p-2 small">Fecha Alta: {{ $ausentismo->trabajador->created_at->format('d/m/Y') }}</li>
					</ul>
				</div>

				<div class="col-lg-4 border-left">
					<div class="font-weight-bold">Datos del Ausentismo</div>
					<ul class="list-group">
						<li class="list-group-item p-2 small">Tipo: {{ $ausentismo->tipo->nombre }}</li>
						<li class="list-group-item p-2 small">Fecha Inicio: {{ $ausentismo->fecha_inicio->format('d/m/Y') }}</li>
						<li class="list-group-item p-2 small">Fecha Final: {{ $ausentismo->fecha_final->format('d/m/Y') }}</li>
						<li class="list-group-item p-2 small">Total días: {{ $ausentismo->total_days }}</li>
						<li class="list-group-item p-2 small">Usuario que registró: {{ $ausentismo->user }}</li>
					</ul>
				</div>

			</div>

		</div>


		<div class="tarjeta">
			<div class="font-weight-bold">Comunicaciones</div>
			<hr class="my-2">
			@if($ausentismo->comunicaciones)
			<div class="table-responsive">
				<table class="table table-sm small table-bordered table-striped">
					<thead>
						@if( $ausentismo->id_cliente == $ausentismo->trabajador->id_cliente)
						<tr>
							<th colspan="5">
								<button data-toggle="modal" data-target="#cargar_comunicaciones_ausentismo" class="btn-ejornal btn-ejornal-success" >
									<i class="fas fa-plus-circle fa-fw"></i> <span>Crear Comunicación</span>
								</button>
							</th>
						</tr>
						@endif
						<tr>
							<th>Tipo</th>
							<th>Descripción</th>
							<th>Archivos</th>
							<th>Última Actualización</th>
							<th>Usuario que registra</th>
						</tr>
					</thead>
					<tbody>
						@foreach($ausentismo->comunicaciones as $comunicacion)
						<tr>
							<td class="align-middle">{{ $comunicacion->tipo->nombre }}</td>
							<td class="align-middle">{{ $comunicacion->descripcion }}</td>
							<td class="align-middle">
								@if($comunicacion->archivos->toArray())
								<button class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">Archivos</button>
								<li class="dropdown-menu">
									@foreach($comunicacion->archivos as $archivo)
									<a href="{{ route('comunicaciones.verArchivo', ['id' => $archivo->id_comunicacion, 'hash' => $archivo->hash_archivo]) }}" target="_blank" class="dropdown-item">{{ $archivo->archivo }}</a>
									@endforeach
								</li>
								@else
								<span class="text-muted font-italic">[no se adjuntaron archivos]</span>
								@endif
							</td>
							<td class="align-middle">{{ $comunicacion->updated_at->format('d/m/Y') }}</td>
							<td class="align-middle">{{ $comunicacion->user }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@else
			<div class="text-muted font-italic">No se cargaron comunicaciones</div>
			@endif
		</div>


		{{-- Certificaciones --}}

		<div class="tarjeta">
			<div class="font-weight-bold">Certificados</div>
			<hr class="my-2">

			@if($ausentismo->documentaciones)
			<div class="table-responsive">
				<table data-table="certificados" class="table table-sm small table-bordered table-striped">
					<thead>
						@if( $ausentismo->id_cliente == $ausentismo->trabajador->id_cliente)
						<tr>
							<th colspan="11">
								<button data-toggle="crear-certificado" data-ausenciaid="{{ $ausentismo->id }}" class="btn-ejornal btn-ejornal-success" >
									<i class="fas fa-plus-circle fa-fw"></i> <span>Crear Certificado</span>
								</button>
							</th>
						</tr>
						@endif
						<tr>
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
							<th></th>
						</tr>
					</thead>
					<tbody>
						@foreach($ausentismo->documentaciones as $documentacion)
						<tr data-id="{{ $documentacion->id }} ">
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
								<span class="text-muted font-italic">[no se adjuntaron archivos]</span>
								@endif
							</td>
							<td class="align-middle">

								@if( auth()->user()->id_cliente_actual == $ausentismo->trabajador->id_cliente)
								<button data-toggle="editar-certificado" class="btn-ejornal btn-ejornal-dark" >
									<i class="fas fa-pen fa-fw"></i> <span>Editar</span>
								</button>
								@endif

							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@else
			<div class="text-muted font-italic">No se cargaron certificados</div>
			@endif

		</div>

		{{-- @include('../../mensajes_validacion') --}}

		{{-- @if ($errors->any())
		@foreach ($errors->all() as $error)
		<div class="alert alert-danger alert-dismissible fade show mr-4 ml-4" role="alert">
			{{$error}}
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		@endforeach
		@endif --}}

		{{-- @include('modulos.perfil_trabajador') --}}

	</div>
</div>








<!-- Modal Crear Comunicación -->
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
						<input type="hidden" name="id_ausentismo" value="{{$ausentismo->id}}">
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
						<button class="btn-ejornal btn-ejornal-success" type="submit" name="button">Crear comunicación</button>
					</form>
				</div>

			</div>

		</div>
	</div>
</div>


@endsection