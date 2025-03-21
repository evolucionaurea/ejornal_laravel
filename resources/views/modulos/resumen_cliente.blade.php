@php
	$newLocale = setlocale(LC_TIME, 'Spanish');
	$now = \Carbon\CarbonImmutable::now();
@endphp


<!-- AUSENTISMOS -->
<div class="row px-4">

	<div class="col-lg-12 pb-0">
		<h5 class="mb-0 font-weight-bold">AUSENTISMOS</h5>
		<div class="small font-italic text-muted">Los porcentajes se calculan en base al total de días ausentes según el período seleccionado.</div>
		<div class="small font-italic text-muted"><b>Nómina actual:</b> Valor de la última nómina disponible.</div>
		<div class="small font-italic text-muted"><b>Nómina Año Actual:</b> Valor promedio de nominas mes a mes.</div>
	</div>

	<!-- Mes Actual -->
	<div class="col-lg-3 mb-1">
		<a href="{{ '/'.$route[0].'/ausentismos?ausentes=mes-actual' }}" class="card purple white-text">
			<div class="card-body d-flex justify-content-between align-items-center p-3">
				<div>
					<div class="h3-responsive font-weight-bold mt-n2 mb-0">{{ number_format($ausentismos_mes_actual_indice,2,',','.') }}%</div>
					<div class="mb-0">Mes Actual</div>
					<div class="mb-0 small font-italic">Nómina: {{$nomina_actual}}</div>
					<div class="mb-0 small font-italic">Total Días: {{ $dias_mes_actual }}</div>
				</div>
				<div>
					<i class="fas fa-user-minus fa-3x text-black-40"></i>
				</div>
			</div>
		</a>
	</div>


	<!-- Mes Anterior -->
	<div class="col-lg-3 mb-1">
		<a href="{{ '/'.$route[0].'/ausentismos?ausentes=mes-anterior' }}" class="card purple white-text">
			<div class="card-body d-flex justify-content-between align-items-center p-3">
				<div>
					<div class="h3-responsive font-weight-bold mt-n2 mb-0">{{ number_format($ausentismos_mes_pasado_indice,2,',','.') }}%</div>
					<div class="mb-0">Mes Anterior</div>
					<div class="mb-0 small font-italic">Nómina: {{$nomina_mes_anterior}}</div>
					<div class="mb-0 small font-italic">Total Días: {{ $dias_mes_pasado }}</div>
				</div>
				<div>
					<i class="fas fa-user-minus fa-3x text-black-40"></i>
				</div>
			</div>
		</a>
	</div>


	<!-- Mes Año Anterior -->
	<div class="col-lg-3 mb-1">
		<a href="{{ '/'.$route[0].'/ausentismos?ausentes=mes-anio-anterior' }}" class="card purple white-text">
			<div class="card-body d-flex justify-content-between align-items-center p-3">
				<div>
					<div class="h3-responsive font-weight-bold mt-n2 mb-0">{{ number_format($ausentismos_mes_anio_anterior_indice,2,',','.') }}%</div>
					<div class="mb-0">Mismo Mes Año Anterior</div>
					<div class="mb-0 small font-italic">Nómina: {{$nomina_mes_anio_anterior}}</div>
					<div class="mb-0 small font-italic">Total Días: {{ $dias_mes_anio_anterior }}</div>
				</div>
				<div>
					<i class="fas fa-user-minus fa-3x text-black-40"></i>
				</div>
			</div>
		</a>
	</div>

	<!-- Año Actual -->
	<div class="col-lg-3 mb-1">
		<a href="{{ '/'.$route[0].'/ausentismos?ausentes=anio-actual' }}" class="card purple white-text">
			<div class="card-body d-flex justify-content-between align-items-center p-3">
				<div>
					<div class="h3-responsive font-weight-bold mt-n2 mb-0">{{ number_format($ausentismos_anio_actual_indice,2,',','.') }}%</div>
					<div class="mb-0">Año actual</div>
					<div class="mb-0 small font-italic">Prom. Nómina: {{$nomina_promedio_actual}}</div>
					<div class="mb-0 small font-italic">Total Días: {{ $dias_anio_actual }}</div>
				</div>
				<div>
					<i class="fas fa-user-minus fa-3x text-black-40"></i>
				</div>
			</div>
		</a>
	</div>

</div>





<!-- ACCIDENTES -->
<div class="row px-4">

	<div class="col-lg-12 pb-0">
		<h5 class="mb-0 font-weight-bold">ACCIDENTES</h5>
	</div>

	<!-- Mes Actual -->
	<div class="col-lg-3 mb-1">
		<div class="card teal white-text">
			<div class="card-body d-flex justify-content-between align-items-center p-3">
				<div>
					<div class="h3-responsive font-weight-bold mt-n2 mb-0">{{ number_format($accidentes_mes_actual_indice,2,',','.') }}%</div>
					<div class="mb-0">Mes Actual</div>
					<div class="mb-0 small font-italic">Total días: {{ $dias_accidentes_mes_actual }}</div>
				</div>
				<div>
					<i class="fas fa-users-medical fa-3x text-black-40"></i>
				</div>
			</div>
		</div>
	</div>

	<!-- Mes Anterior -->
	<div class="col-lg-3 mb-1">
		<div class="card teal white-text">
			<div class="card-body d-flex justify-content-between align-items-center p-3">
				<div>
					<div class="h3-responsive font-weight-bold mt-n2 mb-0">{{ number_format($accidentes_mes_pasado_indice,2,',','.') }}%</div>
					<div class="mb-0">Mes Anterior</div>
					<div class="mb-0 small font-italic">Total días: {{ $dias_accidentes_mes_pasado }}</div>
				</div>
				<div>
					<i class="fas fa-users-medical fa-3x text-black-40"></i>
				</div>
			</div>
		</div>
	</div>

	<!-- Mes Año Anterior -->
	<div class="col-lg-3 mb-1">
		<div class="card teal white-text">
			<div class="card-body d-flex justify-content-between align-items-center p-3">
				<div>
					<div class="h3-responsive font-weight-bold mt-n2 mb-0">{{ number_format($accidentes_mes_anio_anterior_indice,2,',','.') }}%</div>
					<div class="mb-0">Mismo Mes Año Anterior</div>
					<div class="mb-0 small font-italic">Total días: {{ $dias_accidentes_mes_anio_anterior }}</div>
				</div>
				<div>
					<i class="fas fa-users-medical fa-3x text-black-40"></i>
				</div>
			</div>
		</div>
	</div>

	<!-- Año Actual -->
	<div class="col-lg-3 mb-1">
		<div class="card teal white-text">
			<div class="card-body d-flex justify-content-between align-items-center p-3">
				<div>
					<div class="h3-responsive font-weight-bold mt-n2 mb-0">{{ number_format($accidentes_anio_actual_indice,2,',','.') }}%</div>
					<div class="mb-0">Año actual</div>
					<div class="mb-0 small font-italic">Total días: {{ $dias_accidentes_anio_actual }}</div>
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
	<div class="col-lg-3 mb-4 mb-1">
		<div class="card pink white-text">
			<div class="card-body d-flex justify-content-between align-items-center p-3">
				<div>
					<div class="h3-responsive font-weight-bold mt-n2 mb-0">{{ number_format($incidentes_mes_actual_indice,2,',','.') }}%</div>
					<div class="mb-0">Mes Actual</div>
					<div class="mb-0 small font-italic">Total días: {{ $dias_incidentes_mes_actual }}</div>
				</div>
				<div>
					<i class="fas fa-user-times fa-3x text-black-40"></i>
				</div>
			</div>
		</div>
	</div>

	<!-- Mes Anterior -->
	<div class="col-lg-3 mb-4 mb-1">
		<div class="card pink white-text">
			<div class="card-body d-flex justify-content-between align-items-center p-3">
				<div>
					<div class="h3-responsive font-weight-bold mt-n2 mb-0">{{ number_format($incidentes_mes_pasado_indice,2,',','.') }}%</div>
					<div class="mb-0">Mes Anterior</div>
					<div class="mb-0 small font-italic">Total días: {{ $dias_incidentes_mes_pasado }}</div>
				</div>
				<div>
					<i class="fas fa-user-times fa-3x text-black-40"></i>
				</div>
			</div>
		</div>
	</div>

	<!-- Mes Anterior -->
	<div class="col-lg-3 mb-4 mb-1">
		<div class="card pink white-text">
			<div class="card-body d-flex justify-content-between align-items-center p-3">
				<div>
					<div class="h3-responsive font-weight-bold mt-n2 mb-0">{{ number_format($incidentes_mes_anio_anterior_indice,2,',','.') }}%</div>
					<div class="mb-0">Mismo Mes Año Anterior</div>
					<div class="mb-0 small font-italic">Total días: {{ $dias_incidentes_mes_anio_anterior }}</div>
				</div>
				<div>
					<i class="fas fa-user-times fa-3x text-black-40"></i>
				</div>
			</div>
		</div>
	</div>

	<!-- Mes Anterior -->
	<div class="col-lg-3 mb-4 mb-1">
		<div class="card pink white-text">
			<div class="card-body d-flex justify-content-between align-items-center p-3">
				<div>
					<div class="h3-responsive font-weight-bold mt-n2 mb-0">{{ number_format($incidentes_anio_actual_indice,2,',','.') }}%</div>
					<div class="mb-0">Año actual</div>
					<div class="mb-0 small font-italic">Total días: {{ $dias_incidentes_anio_actual }}</div>
				</div>
				<div>
					<i class="fas fa-user-times fa-3x text-black-40"></i>
				</div>
			</div>
		</div>
	</div>


</div>


<hr>


<!-- CHARTS -->
<div class="row">


	<div class="col-lg-6 ">
		<div class="tarjeta" >
			<h2 class="text-center">Ausentismos de {{ $now->formatLocalized('%B') }}</h2>
			<div class="small text-muted text-center">(no incluye Accidentes e Incidentes)</div>
			<hr>
			<div data-toggle="blank-chart" class="alert alert-info d-none">No hay datos</div>
			<canvas id="chart_ausentismos_mes" height="480"></canvas>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="tarjeta">
			<h2 class="text-center">Ausentismos del año</h2>
			<div class="small text-muted text-center">(no incluye Accidentes e Incidentes)</div>
			<hr>
			<div data-toggle="blank-chart" class="alert alert-info d-none">No hay datos</div>
			<canvas id="chart_ausentismos_anual" height="480"></canvas>
		</div>
	</div>


	<div class="col-lg-12">
		<div class="tarjeta">
			<h2 class="text-center">Índice de Ausentismos del año actual</h2>
			<div class="small text-muted text-center">(no incluye Accidentes e Incidentes)</div>
			<hr>
			<div data-toggle="blank-chart" class="alert alert-info d-none">No hay datos</div>
			<canvas id="chart_indice_ausentismos_anual" height="480"></canvas>
		</div>
	</div>
</div>


<hr>


<!-- TABLAS AUSENTISMOS -->
<div class="row">

	<!-- Mes Actual -->
	<div class="col-lg-6">
		<div class="tarjeta ausentismos_mes_porcentajes">
			<h4 class="mb-1">Ausentismos en este mes</h4>
			<h6>{{ $now->formatLocalized('%B') }} {{ $now->formatLocalized('%Y') }}</h6>
			<div class="small text-muted">(no incluye Accidentes e Incidentes)</div>

			<div class="table-responsive">
				<table data-table="ausentismos-mes" class="table table-striped tabla table-sm small">
					<thead>
						<tr>
							<th scope="col">Tipo</th>
							<th scope="col">Cantidad de ausentismos</th>
							<th scope="col">Cantidad de días</th>
							<th scope="col">Índice de Ausentismo</th>
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

	<!-- Mes Anterior -->
	<div class="col-lg-6">
		<div class="tarjeta ausentismos_mes_porcentajes">
			<h4 class="mb-0">Ausentismos en el mes pasado</h4>
			<h6>{{ $now->subMonth()->formatLocalized('%B') }} {{ $now->formatLocalized('%Y') }}</h6>
			<div class="small text-muted">(no incluye Accidentes e Incidentes)</div>

			<div class="table-responsive">
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
	</div>

	<!-- Mes Año Anterior -->
	<div class="col-lg-6">
		<div class="tarjeta ausentismos_mes_porcentajes">
			<h4 class="mb-0">Ausentismos en el mismo mes del año anterior</h4>
			<h6>{{ $now->formatLocalized('%B') }} {{ $now->subYear()->formatLocalized('%Y') }}</h6>
			<div class="small text-muted">(no incluye Accidentes e Incidentes)</div>

			<div class="table-responsive">
				<table data-table="ausentismos-mes-anio-anterior" class="table table-striped tabla table-sm small">
					<thead>
						<tr>
							<th scope="col">Tipo</th>
							<th scope="col">Cantidad ausentismos</th>
							<th scope="col">Cantidad días</th>
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

	<!-- Año Actual -->
	<div class="col-lg-6">
		<div class="tarjeta ausentismos_anio_porcentajes">
			<h4 class="mb-0">Ausentismos del año actual</h4>
			<h6>{{ $now->firstOfYear()->formatLocalized('%B') }} - {{ $now->formatLocalized('%B') }} {{ $now->formatLocalized('%Y') }}</h6>
			<div class="small text-muted">(no incluye Accidentes e Incidentes)</div>

			<div class="table-responsive">
				<table data-table="ausentismos-anual" class="table table-striped table-sm small">
					<thead>
						<tr>
							<th scope="col">Tipo</th>
							<th scope="col">Cantidad ausentismos</th>
							<th scope="col">Cantidad días</th>
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

</div>


<hr>


<!-- TOP -->
<div class="row">

	<div class="col-lg-6">
		<div class="tarjeta">
			<h4 class="mb-2">Top 10 trabajadores que mas días faltaron</h4>
			<div class="small text-muted font-italic">Se computan los últimos 365 días. No incluye Accidentes e Incidentes.</div>
			<table data-table="top_10_faltas" class="table table-striped">
				<thead>
					<tr>
						<th scope="col">Trabajador</th>
						<th scope="col">Estado Actual</th>
						<th scope="col">Días</th>
					</tr>
				</thead>
				<tbody>
					@if ($ausentismos_top_10)
					@foreach ($ausentismos_top_10 as $top)
						<tr>
							<td>{{ $top->trabajador->nombre }}</td>
							<td>
							@if( $top->id_cliente!= $id_cliente)
								<span class="badge badge-dark">transferido</span>
							@elseif ($top->trabajador->regreso_trabajo)
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
			<div class="small text-muted font-italic">Se computan los últimos 365 días. No incluye Accidentes e Incidentes.</div>
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
							<td>
								<div>{{ $falta->trabajador->nombre }}</div>
								@if( $falta->id_cliente!=$id_cliente)
								<span class="badge badge-dark">transferido</span>
								@endif
							</td>
							<td>{{ $falta->total }}</td>
						</tr>
					@endforeach
					@endif
				</tbody>
			</table>
		</div>
	</div>

</div>