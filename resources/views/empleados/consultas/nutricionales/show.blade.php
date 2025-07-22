@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Consulta nutricional</h2>
			<p>Aquí puede ver la consulta nutricional del trabajador de la nomina seleccionado</p>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-gris-claro" href="{{ url('empleados/consultas/nutricionales') }}">
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
							<h5 class="card-title">{{ $nutricional->nomina->nombre }}</h5>
							<div class="row">
								<div class="col-md-6">
									<p class="card-text"><strong>Email:</strong> {{ $nutricional->nomina->email }}</p>
									<p class="card-text"><strong>Teléfono:</strong> {{ $nutricional->nomina->telefono }}
									</p>
									<p class="card-text"><strong>DNI:</strong> {{ $nutricional->nomina->dni }}</p>
									<p class="card-text"><strong>Legajo:</strong>
										{!! $nutricional->nomina->legajo ?: '<i class="text-muted">[No cargado]</i>' !!}
									</p>
								</div>
								<div class="col-md-6">
									<p class="card-text"><strong>Estado:</strong> {{ $nutricional->nomina->estado == 1 ?
										'Activo' : 'Inactivo' }}</p>

									@if($nutricional->nomina->fecha_nacimiento)

									<p class="card-text"><strong>Fecha de nacimiento:</strong> {{ \Carbon\Carbon::parse($nutricional->nomina->fecha_nacimiento)->format('d/m/Y') }}</p>
									<p class="card-text"><strong>Edad:</strong>
										{{ \Carbon\Carbon::parse($nutricional->nomina->fecha_nacimiento)->age }} años
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

							@if($nutricional->nomina->ultima_caratula)

							<div class="row">
								<div class="col-lg-6">
									<p class="card-text">
										<strong>Últimas Patalogías</strong>
										@if($nutricional->nomina->ultima_caratula->patologias)										
											@foreach ($nutricional->nomina->ultima_caratula->patologias as $patologia)
											<span class="badge badge-danger mr-1">{{ $patologia->nombre }}</span>
											@endforeach										
										@else 
										<span class="text-muted font-italic">[no fueron cargadas]</span>
										@endif 
									</p>

									<p class="card-text"><b>Medicación habitual:</b> {{ $nutricional->nomina->ultima_caratula->medicacion_habitual }}</p>
									<p class="card-text"><b>Antecedentes:</b> {{ $nutricional->nomina->ultima_caratula->antecedentes }}</p>
									<p class="card-text"><b>Alergias:</b> {{ $nutricional->nomina->ultima_caratula->alergias }}</p>
									
								</div>

								<div class="col-lg-6">
									<p class="card-text"><b>Peso:</b> {{ $nutricional->nomina->ultima_caratula->peso }}</p>
									<p class="card-text"><b>Altura:</b> {{ $nutricional->nomina->ultima_caratula->altura }}</p>
									<p class="card-text"><b>IMC:</b> {{ $nutricional->nomina->ultima_caratula->imc }}</p>
								</div>
							</div>


							@else 

							<div class="text-muted font-italic">No se ha cargado carátula</div>
							<hr>
							<a href="{{ route('empleados.nominas.caratulas.create', $nutricional->nomina->id) }}" class="btn btn-primary">
								<i class="fal fa-plus"></i>
								<span>Crear Carátula</span>
							</a>

							@endif
							
						</div>
					</div>
				</div>

				<!-- Información de la Consulta Nutricional -->
				<div class="col-md-12 mb-3">
					<div class="card">
						<div class="card-header bg-info text-white">
							Información de la Consulta Nutricional
						</div>
						<div class="card-body">
							<h5 class="card-title">Fecha de Atención: {{
								\Carbon\Carbon::parse($nutricional->fecha_atencion)->format('d/m/Y') }}</h5>
							<p class="card-text">
								<strong>Tipo de consulta:</strong>
								{{ ucfirst($nutricional->tipo) }}
							</p>
							<p class="card-text">
								<strong>User que lo registra:</strong>
								@if ($nutricional->user != null)
								{{ $nutricional->user }}
								@else
								{{ 'No guardado' }}
								@endif
							</p>

							<div class="row">
								@if($nutricional->tipo == 'inicial')
								<!-- Consulta Inicial -->
								<div class="col-md-6">
									<div>
										<strong>Objetivos:</strong>
										<p>{{ $nutricional->objetivos }}</p>
									</div>
									<div>
										<strong>Gustos Alimentarios:</strong>
										<p>{{ $nutricional->gustos_alimentarios }}</p>
									</div>
									<div>
										<strong>Comidas Diarias:</strong>
										<p>{{ $nutricional->comidas_diarias }}</p>
									</div>
								</div>
								<div class="col-md-6">
									<div>
										<strong>Descanso:</strong>
										<p>{{ $nutricional->descanso }}</p>
									</div>
									<div>
										<strong>Intolerancias Digestivas:</strong>
										<p>{{ $nutricional->intolerancias_digestivas }}</p>
									</div>
									<div>
										<strong>Alergias Alimentarias:</strong>
										<p>{{ $nutricional->alergias_alimentarias }}</p>
									</div>
								</div>
								@elseif($nutricional->tipo == 'seguimiento')
								<!-- Consulta de Seguimiento -->
								<div class="col-md-6">
									<div>
										<strong>Actividad Física:</strong>
										<p>{{ $nutricional->act_fisica }}</p>
									</div>
									<div>
										<strong>Circunferencia de Cintura:</strong>
										<p> {{ $nutricional->circunferencia_cintura }} cm</p>
									</div>
									<div>
										<strong>Porcentaje de Masa Grasa:</strong>
										<p>{{ $nutricional->porcent_masa_grasa }} %</p>
									</div>
									<div>
										<strong>Porcentaje de Masa Muscular:</strong>
										<p>{{ $nutricional->porcent_masa_muscular }} %</p>
									</div>
								</div>

								<div class="col-md-6">
									<div>
										<strong>Tránsito Intestinal:</strong>
										<p> {{ $nutricional->transito_intestinal }}</p>
									</div>
									<div>
										<strong>Evolución:</strong>
										<p> {{ $nutricional->evolucion }}</p>
									</div>
									<div>
										<strong>Medicaciones:</strong>
										<p> {{ $nutricional->medicaciones }}</p>
									</div>
									<div>
										<strong>Próxima Cita:</strong>
										@if($nutricional->prox_cita)
										<p>{{ \Carbon\Carbon::parse($nutricional->prox_cita)->format('d/m/Y') }}</p>
										@else 
										<div class="small text-muted font-italic">[No cargada]</div>
										@endif
									</div>
								</div>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>

@endsection