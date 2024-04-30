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
				<button data-toggle="crear-certificado" data-ausenciaid="{{ $ausencia->id }}" class="btn-ejornal btn-ejornal-success" >
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
				<table data-table="certificados" class="table small table-bordered table-sm w-100 border">
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
								<span>no se adjuntaron archivos</span>
								@endif
							</td>
							<td class="align-middle">
								<button data-toggle="editar-certificado" class="btn-ejornal btn-ejornal-dark" >
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



@endsection