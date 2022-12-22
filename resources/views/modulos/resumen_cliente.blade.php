@php
	$newLocale = setlocale(LC_TIME, 'Spanish');
	$now = \Carbon\CarbonImmutable::now();
@endphp




<!-- AUSENTISMOS -->
<div class="row px-4">

	<div class="col-lg-12 pb-0">
		<h5 class="mb-0 font-weight-bold">AUSENTISMOS</h5>
	</div>

	<!-- Mes Actual -->
	<div class="col-lg-3 mb-4">
		<div class="card purple white-text">
			<div class="card-body d-flex justify-content-between align-items-center p-3">
				<div>
					<div class="h3-responsive font-weight-bold mt-n2 mb-0">{{$ausentismos_mes_actual}}</div>
					<div class="mb-0">Mes Actual</div>
				</div>
				<div>
					<i class="fas fa-user-minus fa-3x text-black-40"></i>
				</div>
			</div>
		</div>
	</div>


	<!-- Mes Anterior -->
	<div class="col-lg-3 mb-4">
		<div class="card purple white-text">
			<div class="card-body d-flex justify-content-between align-items-center p-3">
				<div>
					<div class="h3-responsive font-weight-bold mt-n2 mb-0">{{$ausentismos_mes_pasado}}</div>
					<div class="mb-0">Mes Anterior</div>
				</div>
				<div>
					<i class="fas fa-user-minus fa-3x text-black-40"></i>
				</div>
			</div>
		</div>
	</div>


	<!-- Mes Año Anterior -->
	<div class="col-lg-3 mb-4">
		<div class="card purple white-text">
			<div class="card-body d-flex justify-content-between align-items-center p-3">
				<div>
					<div class="h3-responsive font-weight-bold mt-n2 mb-0">{{$ausentismos_mes_anio_anterior}}</div>
					<div class="mb-0">Mismo Mes Año Anterior</div>
				</div>
				<div>
					<i class="fas fa-user-minus fa-3x text-black-40"></i>
				</div>
			</div>
		</div>
	</div>

	<!-- Año Actual -->
	<div class="col-lg-3 mb-4">
		<div class="card purple white-text">
			<div class="card-body d-flex justify-content-between align-items-center p-3">
				<div>
					<div class="h3-responsive font-weight-bold mt-n2 mb-0">{{$ausentismos_anio_actual}}</div>
					<div class="mb-0">Año actual</div>
				</div>
				<div>
					<i class="fas fa-user-minus fa-3x text-black-40"></i>
				</div>
			</div>
		</div>
	</div>

</div>

<!-- ACCIDENTES -->
<div class="row px-4">

	<div class="col-lg-12 pb-0">
		<h5 class="mb-0 font-weight-bold">ACCIDENTES</h5>
	</div>

	<!-- Mes Actual -->
	<div class="col-lg-3 mb-4">
		<div class="card teal white-text">
			<div class="card-body d-flex justify-content-between align-items-center p-3">
				<div>
					<div class="h3-responsive font-weight-bold mt-n2 mb-0">{{$ausentismos_mes_actual ? round($accidentes_mes_actual/$ausentismos_mes_actual*100,1) : 0}}%</div>
					<div class="mb-0">Mes Actual</div>
					<div class="mb-0 small font-italic">Total: {{$accidentes_mes_actual}}</div>
				</div>
				<div>
					<i class="fas fa-users-medical fa-3x text-black-40"></i>
				</div>
			</div>
		</div>
	</div>

	<!-- Mes Anterior -->
	<div class="col-lg-3 mb-4">
		<div class="card teal white-text">
			<div class="card-body d-flex justify-content-between align-items-center p-3">
				<div>
					<div class="h3-responsive font-weight-bold mt-n2 mb-0">{{$ausentismos_mes_pasado ? round($accidentes_mes_pasado/$ausentismos_mes_pasado*100,1) : 0}}%</div>
					<div class="mb-0">Mes Anterior</div>
					<div class="mb-0 small font-italic">Total: {{$accidentes_mes_pasado}}</div>
				</div>
				<div>
					<i class="fas fa-users-medical fa-3x text-black-40"></i>
				</div>
			</div>
		</div>
	</div>

	<!-- Mes Anterior -->
	<div class="col-lg-3 mb-4">
		<div class="card teal white-text">
			<div class="card-body d-flex justify-content-between align-items-center p-3">
				<div>
					<div class="h3-responsive font-weight-bold mt-n2 mb-0">{{$ausentismos_mes_anio_anterior ? round($accidentes_mes_anio_anterior/$ausentismos_mes_anio_anterior*100,1) : 0}}%</div>
					<div class="mb-0">Mismo Mes Año Anterior</div>
					<div class="mb-0 small font-italic">Total: {{$accidentes_mes_anio_anterior}}</div>
				</div>
				<div>
					<i class="fas fa-users-medical fa-3x text-black-40"></i>
				</div>
			</div>
		</div>
	</div>

	<!-- Mes Anterior -->
	<div class="col-lg-3 mb-4">
		<div class="card teal white-text">
			<div class="card-body d-flex justify-content-between align-items-center p-3">
				<div>
					<div class="h3-responsive font-weight-bold mt-n2 mb-0">{{$ausentismos_anio_actual ? round($accidentes_anio_actual/$ausentismos_anio_actual*100,1) : 0}}%</div>
					<div class="mb-0">Año actual</div>
					<div class="mb-0 small font-italic">Total: {{$accidentes_anio_actual}}</div>
				</div>
				<div>
					<i class="fas fa-users-medical fa-3x text-black-40"></i>
				</div>
			</div>
		</div>
	</div>


</div>


<!-- INCIDENTES -->
<div class="row px-4">

	<div class="col-lg-12 pb-0">
		<h5 class="mb-0 font-weight-bold">INCIDENTES</h5>
	</div>

	<!-- Mes Actual -->
	<div class="col-lg-3 mb-4">
		<div class="card pink white-text">
			<div class="card-body d-flex justify-content-between align-items-center p-3">
				<div>
					<div class="h3-responsive font-weight-bold mt-n2 mb-0">{{$ausentismos_mes_actual ? round($incidentes_mes_actual/$ausentismos_mes_actual*100,1) : 0}}%</div>
					<div class="mb-0">Mes Actual</div>
					<div class="mb-0 small font-italic">Total: {{$incidentes_mes_actual}}</div>
				</div>
				<div>
					<i class="fas fa-user-times fa-3x text-black-40"></i>
				</div>
			</div>
		</div>
	</div>

	<!-- Mes Anterior -->
	<div class="col-lg-3 mb-4">
		<div class="card pink white-text">
			<div class="card-body d-flex justify-content-between align-items-center p-3">
				<div>
					<div class="h3-responsive font-weight-bold mt-n2 mb-0">{{$ausentismos_mes_pasado ? round($incidentes_mes_pasado/$ausentismos_mes_pasado*100,1) : 0}}%</div>
					<div class="mb-0">Mes Anterior</div>
					<div class="mb-0 small font-italic">Total: {{$incidentes_mes_pasado}}</div>
				</div>
				<div>
					<i class="fas fa-user-times fa-3x text-black-40"></i>
				</div>
			</div>
		</div>
	</div>

	<!-- Mes Anterior -->
	<div class="col-lg-3 mb-4">
		<div class="card pink white-text">
			<div class="card-body d-flex justify-content-between align-items-center p-3">
				<div>
					<div class="h3-responsive font-weight-bold mt-n2 mb-0">{{$ausentismos_mes_anio_anterior ? round($incidentes_mes_anio_anterior/$ausentismos_mes_anio_anterior*100,1) : 0}}%</div>
					<div class="mb-0">Mismo Mes Año Anterior</div>
					<div class="mb-0 small font-italic">Total: {{$incidentes_mes_anio_anterior}}</div>
				</div>
				<div>
					<i class="fas fa-user-times fa-3x text-black-40"></i>
				</div>
			</div>
		</div>
	</div>

	<!-- Mes Anterior -->
	<div class="col-lg-3 mb-4">
		<div class="card pink white-text">
			<div class="card-body d-flex justify-content-between align-items-center p-3">
				<div>
					<div class="h3-responsive font-weight-bold mt-n2 mb-0">{{$ausentismos_anio_actual ? round($incidentes_anio_actual/$ausentismos_anio_actual*100,1) : 0}}%</div>
					<div class="mb-0">Año actual</div>
					<div class="mb-0 small font-italic">Total: {{$incidentes_anio_actual}}</div>
				</div>
				<div>
					<i class="fas fa-user-times fa-3x text-black-40"></i>
				</div>
			</div>
		</div>
	</div>


</div>



<hr>

<div class="row">
	<div class="col-lg-6 ">
		<div class="tarjeta" >
			<h2 class="text-center">Ausentismos de {{ $now->formatLocalized('%B') }}</h2>
			<hr>
			<div data-toggle="blank-chart" class="alert alert-info d-none">No hay datos</div>
			<canvas id="chart_ausentismos_mes" height="480"></canvas>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="tarjeta">
			<h2 class="text-center">Ausentismos del año</h2>
			<hr>
			<div data-toggle="blank-chart" class="alert alert-info d-none">No hay datos</div>
			<canvas id="chart_ausentismos_anual" height="480"></canvas>
		</div>
	</div>
</div>




<!-- TABLAS AUSENTISMOS -->
<div class="row">

	<!-- Mes Actual -->
	<div class="col-lg-6">
		<div class="tarjeta ausentismos_mes_porcentajes">
			<h4>Ausentismos en {{ $now->formatLocalized('%B') }} {{ $now->formatLocalized('%Y') }}</h4>
			<table data-table="ausentismos-mes" class="table table-striped tabla">
				<thead>
					<tr>
						<th scope="col">Tipo</th>
						<th scope="col">Porcentaje</th>
						<th scope="col">Cantidad</th>
					</tr>
				</thead>
				<tbody>
					{{-- Se carga por JS --}}
				</tbody>
			</table>
		</div>
	</div>

	<!-- Mes Anterior -->
	<div class="col-lg-6">
		<div class="tarjeta ausentismos_mes_porcentajes">
			<h4>Ausentismos en {{ $now->subMonth()->formatLocalized('%B') }} {{ $now->formatLocalized('%Y') }}</h4>
			<table data-table="ausentismos-mes-anterior" class="table table-striped tabla">
				<thead>
					<tr>
						<th scope="col">Tipo</th>
						<th scope="col">Porcentaje</th>
						<th scope="col">Cantidad</th>
					</tr>
				</thead>
				<tbody>
					{{-- Se carga por JS --}}
				</tbody>
			</table>
		</div>
	</div>

	<!-- Mes Año Anterior -->
	<div class="col-lg-6">
		<div class="tarjeta ausentismos_mes_porcentajes">
			<h4>Ausentismos en {{ $now->formatLocalized('%B') }} {{ $now->subYear()->formatLocalized('%Y') }}</h4>
			<table data-table="ausentismos-mes-anio-anterior" class="table table-striped tabla">
				<thead>
					<tr>
						<th scope="col">Tipo</th>
						<th scope="col">Porcentaje</th>
						<th scope="col">Cantidad</th>
					</tr>
				</thead>
				<tbody>
					{{-- Se carga por JS --}}
				</tbody>
			</table>
		</div>
	</div>

	<!-- Año Actual -->
	<div class="col-lg-6">
		<div class="tarjeta ausentismos_anio_porcentajes">
			<h4>Ausentismos de {{ $now->firstOfYear()->formatLocalized('%B') }} a {{ $now->formatLocalized('%B') }} {{ $now->formatLocalized('%Y') }}</h4>
			<table data-table="ausentismos-anual" class="table table-striped">
				<thead>
					<tr>
						<th scope="col">Tipo</th>
						<th scope="col">Porcentaje</th>
						<th scope="col">Cantidad</th>
					</tr>
				</thead>
				<tbody>
					{{-- Se carga por JS --}}
				</tbody>
			</table>
		</div>
	</div>

</div>


<hr>



<div class="row">

	<div class="col-lg-6">
		<div class="tarjeta">
			<h4 class="mb-2">Top 10 trabajadores que mas días faltaron</h4>
			<div class="small text-muted font-italic">Se computan los últimos 365 días.</div>
			<table data-table="top_10_faltas" class="table table-striped">
				<thead>
					<tr>
						<th scope="col">Trabajador</th>
						<th scope="col">Estado Actual</th>
						<th scope="col">Dias</th>
					</tr>
				</thead>
				<tbody>
					@if ($ausentismos_top_10)
					@foreach ($ausentismos_top_10 as $top)
						<tr>
							<td>{{ $top->trabajador->nombre }}</td>
							<td>
							@if ($top->trabajador->regreso_trabajo)
								<span class="badge badge-danger">ausente</span>
							@endif
							</td>
							<td>{{ $top->total_dias }}</td>
						</tr>
					@endforeach
					@endif
				</tbody>
			</table>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="tarjeta">
			<h4 class="mb-2">Top trabajadores que mas veces solicitaron faltar</h4>
			<div class="small text-muted font-italic">Se computan los últimos 365 días.</div>
			<table data-table="top_10_solicitudes_faltas" class="table table-striped">
				<thead>
					<tr>
						<th scope="col">Trabajador</th>
						<th scope="col">Cantidad</th>
					</tr>
				</thead>
				<tbody>
					@if($ausentismos_top_10_solicitudes)
					@foreach ($ausentismos_top_10_solicitudes as $falta)
						<tr>
							<td>{{ $falta->trabajador->nombre }}</td>
							<td>{{ $falta->total }}</td>
						</tr>
					@endforeach
					@endif
				</tbody>
			</table>
		</div>
	</div>

</div>