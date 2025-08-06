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

				@if( $ausencia->id_cliente == $ausencia->trabajador->id_cliente)
				<button data-toggle="crear-certificado" data-ausenciaid="{{ $ausencia->id }}" class="btn-ejornal btn-ejornal-success" >
					<i class="fas fa-plus-circle fa-fw"></i> <span>Crear Certificado</span>
				</button>
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

			{{-- HEADER --}}
			<div class="row">
				<div class="col-lg-3 text-center">

					@if ($ausencia->trabajador->foto)
					<div class="foto-perfil" style="background-image: url({{ $ausencia->trabajador->photo_url }})">
					</div>
					@else
					<i class="fas fa-user fa-10x"></i>
					@endif

				</div>
				<div class="col-lg-4">
					<div class="font-weight-bold">{{ $ausencia->trabajador->nombre }}</div>
					<ul class="list-group">
						<li class="list-group-item p-2 small">DNI: {!! $ausencia->trabajador->dni ?? '<i class="text-muted">[no cargado]</i>' !!}</li>
						<li class="list-group-item p-2 small">CUIL: {!! $ausencia->trabajador->email ?? '<i class="text-muted">[no cargado]</i>' !!}</li>
						<li class="list-group-item p-2 small">Teléfono: {!! $ausencia->trabajador->telefono ?? '<i class="text-muted">[no cargado]</i>' !!}</li>
						<li class="list-group-item p-2 small">
							Estado:
							@if( $ausencia->id_cliente != $ausencia->trabajador->id_cliente)
							<span class="badge badge-dark">transferido</span>
							@else
							<span class="badge badge-{{ $ausencia->trabajador->estado ? 'success' : 'danger' }}">{{ $ausencia->trabajador->estado ? 'activo' : 'inactivo' }}</span>
							@endif
						</li>
						<li class="list-group-item p-2 small">Sector: {{ $ausencia->trabajador->sector }}</li>
						<li class="list-group-item p-2 small">Fecha Alta: {{ $ausencia->trabajador->created_at->format('d/m/Y') }}</li>
					</ul>
				</div>

				<div class="col-lg-4 border-left">
					<div class="font-weight-bold">Datos del Ausentismo</div>
					<ul class="list-group">
						<li class="list-group-item p-2 small">Tipo: {{ $ausencia->tipo->nombre }}</li>
						<li class="list-group-item p-2 small">Fecha Inicio: {{ $ausencia->fecha_inicio->format('d/m/Y') }}</li>
						<li class="list-group-item p-2 small">Fecha Final: {{ $ausencia->fecha_final->format('d/m/Y') }}</li>
						<li class="list-group-item p-2 small">Total días: {{ $ausencia->total_days }}</li>
						<li class="list-group-item p-2 small">Usuario que registró: {{ $ausencia->user }}</li>
					</ul>
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

								@if( auth()->user()->id_cliente_actual == $ausencia->trabajador->id_cliente)
								<button data-toggle="editar-certificado" class="btn-ejornal btn-ejornal-dark" >
									<i class="fas fa-pen fa-fw"></i> <span>Editar</span>
								</button>
								@endif

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