@extends('partials.layout')
@section('title','Monitoreo Avanzado')
@section('content')
<div class="d-flex" id="wrapper">
	@include('partials.sidebar_admin')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')
		<div class="container-fluid mt-4">
			<h2 class="mb-4 text-secondary">
				<i class="fas fa-tachometer-alt"></i> Dashboard de Monitoreo
			</h2>

			{{-- Tarjetas de Estadisticas --}}
			@php
			// Paleta de tonos oscuros (hex)
			$darkColors = [
			'#2f5491',
			'#60227a',
			'#239176',
			'#ab5029',
			'#801639',
			'#1b6967',
			];
			@endphp

			<div class="row">
				@foreach($cards as $c)
				@php
				$color = $darkColors[$loop->index % count($darkColors)];
				@endphp
				<div class="col-lg-2 col-md-4 mb-4">
					<div class="d-flex align-items-center p-3 rounded text-white"
						style="background-color: {{ $color }};">
						<div class="mr-3">
							<i class="fas fa-{{ $c['icon'] }} fa-2x"></i>
						</div>
						<div>
							<small class="text-uppercase">{{ $c['label'] }}</small>
							<div class="h4 mb-0">{{ $c['value'] ?? 'N/A' }}{{ $c['unit'] }}</div>
						</div>
					</div>
				</div>
				@endforeach
			</div>




			{{-- Errores graves y warnings --}}
			<div class="row mb-4">
				<div class="col-md-6">
					<div class="card">
						<div class="card-header bg-danger text-white">Últimos Fatal Errors</div>
						<div class="card-body" style="max-height:200px; overflow:auto">
							@forelse($errorsFatal as $msg)
							<p>
								<code class="bg-dark text-white rounded d-block p-1 mb-1">
							{{ $msg->line }}
						</code>
							</p>
							@empty
							<p class="text-muted">No hay fatal errors registrados.</p>
							@endforelse
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card">
						<div class="card-header bg-warning text-dark">Últimos Warnings</div>
						<div class="card-body" style="max-height:200px; overflow:auto">
							@forelse($errorsWarn as $msg)
							<p>
								<code class="bg-dark text-white rounded d-block p-1 mb-1">
							{{ $msg->line }}
						</code>
							</p>
							@empty
							<p class="text-muted">No hay warnings registrados.</p>
							@endforelse
						</div>
					</div>
				</div>
			</div>



			<div class="row mb-4">

				{{-- INICIO: Nuevo Bloque Conexiones Activas --}}
				<div class="col-6">
					<div class="card">
						<div class="card-header bg-primary text-white">
							<i class="fas fa-database"></i> Conexiones Activas DB
						</div>
						<div class="card-body p-0" style="max-height:200px; overflow:auto;">
							<table class="table mb-0">
								<thead class="thead-light">
									<tr>
										<th>Conexión</th>
										<th>Activas</th>
									</tr>
								</thead>
								<tbody>
									@forelse($dbConnections as $connName => $count)
									<tr>
										<td>{{ $connName }}</td>
										<td>{{ $count }}</td>
									</tr>
									@empty
									<tr>
										<td colspan="2" class="text-center text-muted">
											No se detectaron conexiones activas o no disponible.
										</td>
									</tr>
									@endforelse
								</tbody>
							</table>
						</div>
					</div>
				</div>
				{{-- FIN: Nuevo Bloque Conexiones Activas --}}


				{{-- INICIO: Nuevo Bloque QPS --}}
				<div class="col-6">
					<div class="card">
						<div class="card-header bg-secondary text-white">
							<i class="fas fa-tachometer-alt"></i> Consultas por Segundo (QPS)
						</div>
						<div class="card-body p-0" style="max-height:200px; overflow:auto;">
							<table class="table mb-0">
								<thead class="thead-light">
									<tr>
										<th>Conexión</th>
										<th>QPS</th>
									</tr>
								</thead>
								<tbody>
									@forelse($qpsMetrics as $conn => $qps)
									<tr>
										<td>{{ $conn }}</td>
										<td>{{ is_numeric($qps) ? $qps : $qps }}</td>
									</tr>
									@empty
									<tr>
										<td colspan="2" class="text-center text-muted">
											No se pudo calcular QPS.
										</td>
									</tr>
									@endforelse
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			{{-- FIN: Nuevo Bloque QPS --}}



			{{-- Gráficos de errores por mes y uso de rutas --}}
			<div class="row">
				<div class="col-md-6 mb-4">
					<div class="card">
						<div class="card-header">
							<i class="fas fa-chart-bar text-info"></i> Errores últimos 15 días
						</div>
						<div class="card-body">
							<canvas id="chartTraffic" height="120"></canvas>
						</div>
					</div>
				</div>
				<div class="col-md-6 mb-4">
					<!-- CORRECCIÓN: col-md-6 en lugar de col-md=6 -->
					<div class="card">
						<div class="card-header">
							<i class="fas fa-chart-pie text-success"></i> Uso de Rutas (Top 5)
						</div>
						<div class="card-body">
							<canvas id="chartServices" height="120"></canvas>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
@endsection


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
	document.addEventListener('DOMContentLoaded', ()=> {
	// Errores últimos 2 meses (barra con colores)
	new Chart(document.getElementById('chartTraffic'), {
		type: 'bar',
		data: {
			labels: @json($trafficLabels),
			datasets: [
				{
					label: 'Fatal Errors',
					data: @json($trafficDataError),
					backgroundColor: 'rgba(255, 99, 132, 0.5)',
					borderColor: 'rgba(255, 99, 132, 1)',
					borderWidth: 1
				},
				{
					label: 'Warnings',
					data: @json($trafficDataWarn),
					backgroundColor: 'rgba(255, 206, 86, 0.5)',
					borderColor: 'rgba(255, 206, 86, 1)',
					borderWidth: 1
				}
			]
		},
		options: {
			responsive: true,
			scales: {
				yAxes: [{
					ticks:{ beginAtZero: true }
				}]
			}
		}
	});

	// Uso de rutas top 5 (pie con paleta de colores)
	const pieColors = [
		'rgba(54, 162, 235, 0.6)',
		'rgba(75, 192, 192, 0.6)',
		'rgba(153, 102, 255, 0.6)',
		'rgba(255, 159, 64, 0.6)',
		'rgba(255, 205, 86, 0.6)'
	];
	const pieBorders = pieColors.map(c =>
		c.replace('0.6', '1')
	);

	new Chart(document.getElementById('chartServices'), {
		type: 'pie',
		data: {
			labels: @json($serviceLabels),
			datasets: [{
				data: @json($serviceValues),
				backgroundColor: pieColors.slice(0, @json(count($serviceValues))),
				borderColor:    pieBorders.slice(0, @json(count($serviceValues))),
				borderWidth: 1
			}]
		},
		options: {
			responsive: true
		}
	});
});
</script>