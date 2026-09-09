@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Consultas</h2>
			<p>Aquí puede ver la consulta del trabajador de la nomina seleccionado</p>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-gris-claro" href="{{ url('empleados/consultas/otras') }}">
					<i class="fas fa-arrow-circle-left"></i>Volver
				</a>
			</div>
		</div>

		@include('../mensajes_validacion')

		<div class="tarjeta">
			<div class="row">
				<!-- Información de la Nómina -->
				<div class="col-md-6 mb-3">
					<div class="card" style="min-height:290px">
						<div class="card-header bg-primary text-white">
							Información del trabajador de la Nómina
						</div>
						<div class="card-body">
							<h5 class="card-title">{{ $otra_consulta->nomina->nombre }}</h5>
							<div class="row">
								<div class="col-md-6">
									<p class="card-text"><strong>Email:</strong> {{ $otra_consulta->nomina->email }}</p>
									<p class="card-text"><strong>Teléfono:</strong> {{ $otra_consulta->nomina->telefono }}
									</p>
									<p class="card-text"><strong>DNI:</strong> {{ $otra_consulta->nomina->dni }}</p>
									<p class="card-text"><strong>Legajo:</strong>
										{!! $otra_consulta->nomina->legajo ?: '<i class="text-muted">[No cargado]</i>' !!}
									</p>
								</div>
								<div class="col-md-6">
									<p class="card-text"><strong>Estado:</strong> {{ $otra_consulta->nomina->estado == 1 ?
										'Activo' : 'Inactivo' }}</p>

									@if($otra_consulta->nomina->fecha_nacimiento)

									<p class="card-text"><strong>Fecha de nacimiento:</strong> {{ \Carbon\Carbon::parse($otra_consulta->nomina->fecha_nacimiento)->format('d/m/Y') }}</p>
									<p class="card-text"><strong>Edad:</strong>
										{{ \Carbon\Carbon::parse($otra_consulta->nomina->fecha_nacimiento)->age }} años
									</p>

									@else 
									<p class="card-text">										
										<strong>Fecha de Nacimiento:</strong>
										<i class="text-muted">[No cargada]</i>
									</p>
									@endif
									
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Información del Cliente -->
				<div class="col-md-6 mb-3">
					<div class="card" style="min-height:290px">
						<div class="card-header bg-success text-white">
							Carátula del Trabajador
						</div>
						<div class="card-body">

							@if($otra_consulta->nomina->ultima_caratula)

							<div class="row">
								<div class="col-lg-6">
									<p class="card-text">
										<strong>Últimas Patalogías</strong>
										@if($otra_consulta->nomina->ultima_caratula->patologias)										
											@foreach ($otra_consulta->nomina->ultima_caratula->patologias as $patologia)
											<span class="badge badge-danger mr-1">{{ $patologia->nombre }}</span>
											@endforeach										
										@else 
										<span class="text-muted font-italic">[no fueron cargadas]</span>
										@endif 
									</p>

									<p class="card-text"><b>Medicación habitual:</b> {{ $otra_consulta->nomina->ultima_caratula->medicacion_habitual }}</p>
									<p class="card-text"><b>Antecedentes:</b> {{ $otra_consulta->nomina->ultima_caratula->antecedentes }}</p>
									<p class="card-text"><b>Alergias:</b> {{ $otra_consulta->nomina->ultima_caratula->alergias }}</p>
									
								</div>

								<div class="col-lg-6">
									<p class="card-text"><b>Peso Actual:</b> {{ $otra_consulta->nomina->ultima_caratula->peso }}</p>
									<p class="card-text"><b>Altura:</b> {{ $otra_consulta->nomina->ultima_caratula->altura }}</p>
									<p class="card-text"><b>IMC:</b> {{ $otra_consulta->nomina->ultima_caratula->imc }}</p>
								</div>
							</div>

							<div class="small text-muted font-italic border-top pt-2">Última actualización: {{ $otra_consulta->nomina->ultima_caratula->created_at_formatted }} hs.</div>


							@else 

							<div class="text-muted font-italic">No se ha cargado carátula</div>
							<hr>
							<a href="{{ url('empleados/caratulas/create', $otra_consulta->nomina->id) }}" class="btn btn-primary">
								<i class="fal fa-plus"></i>
								<span>Crear Carátula</span>
							</a>

							@endif
							
						</div>
					</div>
				</div>

				<!-- Información de la Consulta -->
				<div class="col-md-12 mb-3">
					<div class="card">
						<div class="card-header bg-info text-white">
							Información de la Consulta
						</div>
						<div class="card-body">
							
							<h5>Tipo de consulta: {{ $otra_consulta->profesional_tipo->nombre }}</h5>
							<h6 class="card-title">Fecha de Atención: {{ \Carbon\Carbon::parse($otra_consulta->fecha)->format('d/m/Y') }}</h6>

							<p class="card-text">
								<strong>Usuario que lo registró:</strong>
								@if ($otra_consulta->user != null)
								{{ $otra_consulta->user->nombre }}
								@else
								{{ 'No guardado' }}
								@endif
							</p>
							<hr>
							
							<p class="card-text">
								<strong>Motivo:</strong>
								{{ ucfirst($otra_consulta->motivo) }}
							</p>
							<p class="card-text">
								<strong>Desarrollo:</strong>
								{{ ucfirst($otra_consulta->desarrollo) }}
							</p>
							
						</div>
					</div>
				</div>
			</div>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>

@endsection