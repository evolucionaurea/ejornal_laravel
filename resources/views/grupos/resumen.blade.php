@extends('partials.layout')

@section('title', 'Grupos')

@section('content')

@inject('carbon', 'Carbon\Carbon')

<div class="d-flex" id="wrapper">

	@include('partials.sidebar_grupos')

	<div id="page-content-wrapper">

		@include('partials.nav_sup')

		<div class="container">

			<!-- Ausentismos Totales -->
			<section>


				<div class="tarjeta">
					<h4 class="mb-1">Empresas del grupo: {{$grupo->clientes->count()}}</h4>
					{{-- <h6 class="small">Se muestran valores en base a trabajadores activos solamente.</h6> --}}

					<table data-table="ausentismos" class="table table-striped table-sm">
						<thead>
							<tr>
								<th scope="col"> <b>Nombre</b></th>
								<th class="text-right">Total Nómina</th>
								{{-- <th class="text-right">Ausentes hoy <i class="fa fa-question-circle fa-fw" data-swal="Se contabilizan sólamente los trabajadores activos."></i></th> --}}
								<th class="text-right">Ausentismos Mes Actual <i class="fa fa-question-circle fa-fw" data-swal="Porcentaje de ausentismos en relación a la nómina actual. NO incluye Accidentes o Incidentes"></i></th>
								<th class="text-right">Ausentismos Mes Anterior</th>
								<th class="text-right">Ausentismos Mismo Mes Año Anterior</th>
								<th class="text-right">Ausentismos del Año</th>
							</tr>
						</thead>
						<tbody>

							@foreach ($clientes_nominas->clientes as $cliente)

							<tr>
								<td>{{$cliente->nombre}}</td>
								<td class="text-right">{{$cliente->nominas_count}}</td>
								{{-- <td class="text-right">{{$cliente->ausentismos_count}}</td> --}}
								<td class="text-right">{{ number_format($cliente->ausentismos->ausentismos_mes_actual_indice,2,',','.') }}%</td>
								<td class="text-right">{{ number_format($cliente->ausentismos->ausentismos_mes_pasado_indice,2,',','.') }}%</td>
								<td class="text-right">{{ number_format($cliente->ausentismos->ausentismos_mes_anio_anterior_indice,2,',','.') }}%</td>
								<td class="text-right">{{ number_format($cliente->ausentismos->ausentismos_anio_actual_indice,2,',','.') }}%</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>



				{{-- CHARTS --}}
				<div class="row d-flex justify-content-center">

					<div class="col-lg-6">
						<div class="tarjeta">
							<h4 class="text-center mb-1">Ausentismos de {{ Str::ucfirst($carbon::now()->formatLocalized('%B')) }}</h4>
							<div class="small text-muted text-center">no incluye Accidentes e Incidentes</div>
							<div data-toggle="blank-chart" class="alert alert-info text-center d-none">No hay datos</div>
							<canvas id="chart_ausentismos_mes" height="380"></canvas>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="tarjeta">
							<h4 class="text-center mb-1">Ausentismos del año</h4>
							<div class="small text-muted text-center">no incluye Accidentes e Incidentes</div>
							<div data-toggle="blank-chart-ausentismos" class="alert alert-info text-center d-none">No hay datos</div>
							<canvas id="chart_ausentismos_anual" height="380"></canvas>
						</div>
					</div>


					{{-- MES A MES --}}
					<div class="col-lg-12">
						<div class="tarjeta">
							<h2 class="text-center">Índice de Ausentismos del año actual</h2>
							<div class="small text-muted text-center">no incluye Accidentes e Incidentes</div>
							<hr>
							<div data-toggle="blank-chart" class="alert alert-info d-none">No hay datos</div>
							<canvas id="chart_indice_ausentismos_anual" height="480"></canvas>
						</div>
					</div>

				</div>



				{{-- TABLAS --}}

				<div class="row">

					<!-- Mes Actual -->
					<div class="col-lg-6">
						<div class="tarjeta ausentismos_mes_porcentajes">
							<h4 class="mb-0">Ausentismos del mes actual</h4>
							<h6>{{ $carbon::now()->formatLocalized('%B') }} {{ $carbon::now()->formatLocalized('%Y') }}</h6>
							<div class="small text-muted">(no incluye Accidentes e Incidentes)</div>
							<table data-table="ausentismos-mes" class="table table-striped tabla table-sm small">
								<thead>
									<tr>
										<th scope="col">Tipo</th>
										<th scope="col">Cantidad ausentismos</th>
										<th scope="col">Cantidad de días</th>
										<th scope="col">Índice de ausentismo</th>
									</tr>
								</thead>
								<tbody>
									{{-- Se carga por JS --}}
								</tbody>
								<tfoot>
									<tr class="alert-info">
										<th>Total</th>
										<th data-content="total-ausentismos"></th>
										<th data-content="total-dias"></th>
										<th data-content="total-percent"></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>

					<!-- Mes Anterior -->
					<div class="col-lg-6">
						<div class="tarjeta ausentismos_mes_porcentajes">
							<h4 class="mb-0">Ausentismos del mes pasado</h4>
							<h6>{{ $carbon::now()->subMonth()->formatLocalized('%B') }} {{ $carbon::now()->subMonth()->formatLocalized('%Y') }}</h6>
							<div class="small text-muted">(no incluye Accidentes e Incidentes)</div>
							<table data-table="ausentismos-mes-anterior" class="table table-striped tabla table-sm small">
								<thead>
									<tr>
										<th scope="col">Tipo</th>
										<th scope="col">Cantidad de ausentismos</th>
										<th scope="col">Cantidad de días</th>
										<th scope="col">Índice de ausentismo</th>
									</tr>
								</thead>
								<tbody>
									{{-- Se carga por JS --}}
								</tbody>
								<tfoot>
									<tr class="alert-info">
										<th>Total</th>
										<th data-content="total-ausentismos"></th>
										<th data-content="total-dias"></th>
										<th data-content="total-percent"></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>

					<!-- Mes Año Anterior -->
					<div class="col-lg-6">
						<div class="tarjeta ausentismos_mes_porcentajes">
							<h4 class="mb-0">Ausentismos en el mismo mes del año anterior</h4>
							<h6>{{ $carbon::now()->formatLocalized('%B') }} {{ $carbon::now()->subYear()->formatLocalized('%Y') }}</h6>
							<div class="small text-muted">(no incluye Accidentes e Incidentes)</div>
							<table data-table="ausentismos-mes-anio-anterior" class="table table-striped tabla table-sm small">
								<thead>
									<tr>
										<th scope="col">Tipo</th>
										<th scope="col">Cantidad de ausentismos</th>
										<th scope="col">Cantidad de días</th>
										<th scope="col">Índice de ausentismo</th>
									</tr>
								</thead>
								<tbody>
									{{-- Se carga por JS --}}
								</tbody>
								<tfoot>
									<tr class="alert-info">
										<th>Total</th>
										<th data-content="total-ausentismos"></th>
										<th data-content="total-dias"></th>
										<th data-content="total-percent"></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>

					<!-- Año Actual -->
					<div class="col-lg-6">
						<div class="tarjeta ausentismos_anio_porcentajes">
							<h4 class="mb-0">Ausentismos del año actual</h4>
							<h6>{{ $carbon::now()->firstOfYear()->formatLocalized('%B') }} a {{ $carbon::now()->formatLocalized('%B') }} {{ $carbon::now()->formatLocalized('%Y') }}</h6>
							<div class="small text-muted">(no incluye Accidentes e Incidentes)</div>
							<table data-table="ausentismos-anual" class="table table-striped table-sm small">
								<thead>
									<tr>
										<th scope="col">Tipo</th>
										<th scope="col">Cantidad de ausentismos</th>
										<th scope="col">Cantidad de días</th>
										<th scope="col">Índice de ausentismo</th>
									</tr>
								</thead>
								<tbody>
									{{-- Se carga por JS --}}
								</tbody>
								<tfoot>
									<tr class="alert-info">
										<th>Total</th>
										<th data-content="total-ausentismos"></th>
										<th data-content="total-dias"></th>
										<th data-content="total-percent"></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>

				</div>

			</section>

		</div>

	</div>

</div>


@endsection
