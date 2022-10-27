@extends('partials.layout')

@section('title', 'Grupos')

@section('content')

@inject('carbon', 'Carbon\Carbon')

<div class="d-flex" id="wrapper">

	@include('partials.sidebar_grupos')

	<div id="page-content-wrapper">

		@include('partials.nav_sup')

		<div class="container">

			@if ($cliente_actual)

			<!-- Ausentismos Empresa Actual -->
			<section>

				<div class="row">

					<div class="col-lg-3 col-md-6 mb-4">
						<div class="media white z-depth-1 rounded">
							<i class="fas fa-user-md fa-lg blue z-depth-1 p-4 rounded-left text-white mr-3"></i>
							<div class="media-body p-1">
								<p class="text-uppercase text-muted mb-1"><small>Accidentes mes actual</small></p>
								<h5 class="font-weight-bold mb-0">{{$accidentes_mes}}</h5>
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
								<h5 class="font-weight-bold mb-0">{{$ausentismos_mes}}</h5>
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


				<h5 class="font-weight-bold mt-4 text-center">Ausentismos en {{$cliente_actual->nombre}}</h5>


				<div class="row d-flex justify-content-center">
					<div class="col-lg-5 tarjeta">
						<h4 class="text-center">Ausentismos de {{ Str::ucfirst($carbon::now()->formatLocalized('%B')) }}</h4>
						<div data-toggle="blank-chart-ausentismos-mes" class="alert alert-info text-center d-none">No hay datos</div>
						<canvas id="chart_ausentismos_mes" height="380"></canvas>
					</div>
					<div class="col-lg-5 tarjeta">
						<h4 class="text-center">Ausentismos del año</h4>
						<div data-toggle="blank-chart-ausentismos-anual" class="alert alert-info text-center d-none">No hay datos</div>
						<canvas id="chart_ausentismos_anual" height="380"></canvas>
					</div>
				</div>



				<div class="row">

					<div class="col-6">
						<div class="tarjeta">
							<h4>Ausentismos de {{ Str::ucfirst($carbon::now()->formatLocalized('%B')) }} en %</h4>
							<table data-table="ausentismos-mes" class="table table-striped">
								<thead>
									<tr>
										<th scope="col">Trabajador</th>
										<th scope="col">Porcentaje</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>

					<div class="col-6">
						<div class="tarjeta">
							<h4>Ausentismos del año en %</h4>
							<table data-table="ausentismos-anual" class="table table-striped">
								<thead>
									<tr>
										<th scope="col">Trabajador</th>
										<th scope="col">Porcentaje</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>


					<!-- -->


					<div class="col-6">
						<div class="tarjeta">
							<h4>Top 10 trabajadores que mas días faltaron</h4>
							<table data-table="top_10_faltas" class="table table-striped">
								<thead>
									<tr>
										<th scope="col">Trabajador</th>
										<th scope="col">Días</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($ausentismos_top_10_dias as $top)
									<tr>
										<td>{{ $top->trabajador->nombre }}</td>
										<td>{{ $top->total_dias }}</td>
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
									@foreach ($ausentismos_top_10 as $top)
									<tr>
										<td>{{ $top->trabajador->nombre }}</td>
										<td>{{ $top->total }}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>

				</div>


			</section>

			@else
			<p class="text-muted"><i>Debes seleccionar un cliente de la lista para ver las estadísticas.</i></p>
			@endif

		</div>



	</div>

</div>


@endsection
