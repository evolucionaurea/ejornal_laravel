@extends('partials.layout')

@section('title', 'Clientes')

@section('content')

<div class="d-flex" id="wrapper">

	@include('partials.sidebar_clientes')

	<div id="page-content-wrapper">

		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		@php
			$newLocale = setlocale(LC_TIME, 'Spanish');
			$now = \Carbon\Carbon::now();
		@endphp


		<div class="container">
			<section>

				<div class="row">
					<div class="col-lg-3 col-md-6 mb-4">
						<div class="media white z-depth-1 rounded">
							<i class="fas fa-user-md fa-lg blue z-depth-1 p-4 rounded-left text-white mr-3"></i>
							<div class="media-body p-1">
								<p class="text-uppercase text-muted mb-1"><small>Accidentes mes actual</small></p>
								<h5 class="font-weight-bold mb-0">{{$accidentes_mes_actual}}</h5>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-md-6 mb-4">
							<div class="media white z-depth-1 rounded">
									<i class="fas fa-user-md fa-lg pink z-depth-1 p-4 rounded-left text-white mr-3"></i>
									<div class="media-body p-1">
											<p class="text-uppercase text-muted mb-1"><small>Accidentes mes pasado</small></p>
											<h5 class="font-weight-bold mb-0">{{$accidentes_mes_pasado}}</h5>
									</div>
							</div>
					</div>

					<div class="col-lg-3 col-md-6 mb-4">
							<div class="media white z-depth-1 rounded">
									<i class="fas fa-user-times fa-lg teal z-depth-1 p-4 rounded-left text-white mr-3"></i>
									<div class="media-body p-1">
											<p class="text-uppercase text-muted mb-1"><small>Ausentismos mes actual</small></p>
											<h5 class="font-weight-bold mb-0">{{$ausentismos_mes_actual}}</h5>
									</div>
							</div>
					</div>

					<div class="col-lg-3 col-md-6 mb-4">
							<div class="media white z-depth-1 rounded">
									<i class="fas fa-user-times fa-lg pink z-depth-1 p-4 rounded-left text-white mr-3"></i>
									<div class="media-body p-1">
											<p class="text-uppercase text-muted mb-1"><small>Ausentismos mes pasado</small></p>
											<h5 class="font-weight-bold mb-0">{{$ausentismos_mes_pasado}}</h5>
									</div>
							</div>
					</div>
				</div>


				<div class="row">
					<div class="col-lg-3 col-md-6 mb-4">
						<div class="media white z-depth-1 rounded">
							<i class="fas fa-user-md fa-lg secondary-color-dark z-depth-1 p-4 rounded-left text-white mr-3"></i>
							<div class="media-body p-1">
								<p class="text-uppercase text-muted mb-1"><small>Ausencias del día por Covid</small></p>
								<h5 class="font-weight-bold mb-0">{{$ausencia_covid}}</h5>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-md-6 mb-4">
							<div class="media white z-depth-1 rounded">
									<i class="fas fa-user-md fa-lg secondary-color-dark z-depth-1 p-4 rounded-left text-white mr-3"></i>
									<div class="media-body p-1">
											<p class="text-uppercase text-muted mb-1"><small>Con al menos 1 dosis</small></p>
											<h5 class="font-weight-bold mb-0">{{$cant_vacunados_una_dosis}}</h5>
									</div>
							</div>
					</div>

					<div class="col-lg-3 col-md-6 mb-4">
							<div class="media white z-depth-1 rounded">
									<i class="fas fa-user-times fa-lg secondary-color-dark z-depth-1 p-4 rounded-left text-white mr-3"></i>
									<div class="media-body p-1">
											<p class="text-uppercase text-muted mb-1"><small>Con 2 dosis</small></p>
											<h5 class="font-weight-bold mb-0">{{$cant_vacunados_dos_dosis}}</h5>
									</div>
							</div>
					</div>

					<div class="col-lg-3 col-md-6 mb-4">
							<div class="media white z-depth-1 rounded">
									<i class="fas fa-user-times fa-lg secondary-color-dark z-depth-1 p-4 rounded-left text-white mr-3"></i>
									<div class="media-body p-1">
											<p class="text-uppercase text-muted mb-1"><small>Con 3 dosis</small></p>
											<h5 class="font-weight-bold mb-0">{{$cant_vacunados_tres_dosis}}</h5>
									</div>
							</div>
					</div>
				</div>



					<div class="row d-flex justify-content-center">
							<div class="col-md-5 tarjeta">
									<h2 class="text-center">Ausentismos de {{ $now->formatLocalized('%B') }}</h2>
									<div class="alert alert-info resumen_graficos_ausentismos_mes">
										No hay datos
									</div>
									<canvas id="chart_accidentes"></canvas>
							</div>
							<div class="col-md-5 tarjeta">
									<h2 class="text-center">Ausentismos del año</h2>
									<div class="alert alert-info resumen_graficos_ausentismos_anual">
										No hay datos
									</div>
									<canvas id="chart_accidentes_anual"></canvas>
							</div>
					</div>
			</section>
		</div>


		<div class="row">
			<div class="col-6">
				<div class="tarjeta ausentismos_mes_porcentajes">
					<h4>Ausentismos de {{ $now->formatLocalized('%B') }}</h4>
					<table class="table table-striped tabla">
						<thead>
							<tr>
								<th scope="col">Tipo</th>
								<th scope="col">Porcentaje</th>
							</tr>
						</thead>
						<tbody>
							{{-- Se carga por JS --}}
						</tbody>
					</table>
				</div>
			</div>

			<div class="col-6">
				<div class="tarjeta ausentismos_anio_porcentajes">
					<h4>Ausentismos del año %</h4>
					<table class="table table-striped">
						<thead>
							<tr>
								<th scope="col">Tipo</th>
								<th scope="col">Porcentaje</th>
							</tr>
						</thead>
						<tbody>
							{{-- Se carga por JS --}}
						</tbody>
					</table>
				</div>
			</div>

		</div>

		<div class="row">
			<div class="col-6">
				<div class="tarjeta">
					<h4>Top 10 trabajadores que mas días faltaron</h4>
					<table data-table="top_10_faltas" class="table table-striped">
						<thead>
							<tr>
								<th scope="col">Trabajador</th>
								<th scope="col">Dias</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($top_10_ausentismos as $top)
								<tr>
									<td>{{$top['info']->trabajador}}</td>
									<td>{{$top['dias_ausente']}}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>

			<div class="col-6">
				<div class="tarjeta">
					<h4>Top trabajadores que mas veces solicitaron faltar</h4>
					<table data-table="top_10_solicitudes_faltas" class="table table-striped">
						<thead>
							<tr>
								<th scope="col">Trabajador</th>
								<th scope="col">Cantidad</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($faltas_final as $falta)
								<tr>
									<td>{{$falta['trabajador']}}</td>
									<td>{{$falta['cant']}}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>

		</div>


			{{-- Contenido de la pagina --}}
	</div>

</div>


@endsection
