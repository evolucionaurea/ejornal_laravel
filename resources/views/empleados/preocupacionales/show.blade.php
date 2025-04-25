@extends('partials.layout')

@section('title', 'Estudio Médico Complementario')

@section('content')

<div class="d-flex" id="wrapper">

	@include('partials.sidebar_empleados')

	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		<div class="cabecera">
			<h2>Estudio Médico Complementario</h2>
			<p>Visualización en detalle de un estudio médico complementario.</p>
		</div>


		<div class="tarjeta">

			<div class="tarjeta">

				<div class="row">
					<div class="col-lg-3 text-center">


						@if ($preocupacional->trabajador->foto)
						<div class="foto-perfil" style="background-image: url({{ $preocupacional->trabajador->photo_url }})">
						</div>
						@else
						<i class="fas fa-user fa-10x"></i>
						@endif

					</div>
					<div class="col-lg-4">
						<div class="font-weight-bold">{{ $preocupacional->trabajador->nombre }}</div>
						<ul class="list-group">
							<li class="list-group-item p-2 small">DNI: {!! $preocupacional->trabajador->dni ?? '<i class="text-muted">[no cargado]</i>' !!}</li>
							<li class="list-group-item p-2 small">CUIL: {!! $preocupacional->trabajador->email ?? '<i class="text-muted">[no cargado]</i>' !!}</li>
							<li class="list-group-item p-2 small">Teléfono: {!! $preocupacional->trabajador->telefono ?? '<i class="text-muted">[no cargado]</i>' !!}</li>
							<li class="list-group-item p-2 small">
								Estado:
								@if( $preocupacional->id_cliente != $preocupacional->trabajador->id_cliente)
								<span class="badge badge-dark">transferido</span>
								@else
								<span class="badge badge-{{ $preocupacional->trabajador->estado ? 'success' : 'danger' }}">{{ $preocupacional->trabajador->estado ? 'activo' : 'inactivo' }}</span>
								@endif
							</li>
							<li class="list-group-item p-2 small">Sector: {{ $preocupacional->trabajador->sector }}</li>
							<li class="list-group-item p-2 small">Fecha Alta: {{ $preocupacional->trabajador->created_at->format('d/m/Y') }}</li>
						</ul>
					</div>

					<div class="col-lg-4 border-left">
						<div class="font-weight-bold">Datos del Estudio</div>
						<ul class="list-group">
							<li class="list-group-item p-2 small">Tipo: {{ $preocupacional->tipo->name }}</li>
							<li class="list-group-item p-2 small">Resultado: {{ $preocupacional->resultado }}</li>
							<li class="list-group-item p-2 small">Fecha: {{ $preocupacional->fecha->format('d/m/Y') }}</li>
							<li class="list-group-item p-2 small">Usuario que registró: {{ $preocupacional->user }}</li>
						</ul>
					</div>

				</div>

			</div>

		</div>


	</div>

</div>



@endsection
