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
					<h4>Empresas del grupo: {{$grupo->clientes->count()}}</h4>
					<table data-table="ausentismos" class="table table-striped table-sm">
						<thead>
							<tr>
								<th scope="col"> <b>Nombre</b></th>
								<th class="text-right">Total Nómina</th>
								<th class="text-right">Ausentes hoy</th>
								<th class="text-right">Ausentismos {{ Str::ucfirst($carbon::now()->formatLocalized('%B')) }}</th>
								<th class="text-right">Ausentismos Mes Pasado</th>
								<th class="text-right">Ausentismos del año</th>
								<th class="text-right">Accidentes Mes Actual</th>
								<th class="text-right">Accidentes Mes Pasado</th>
							</tr>
						</thead>
						<tbody>
							@php
							 	$nominas_count_total = 0;
								$ausentismos_count_total = 0;
								$ausentismos_mes_count_total = 0;
								$ausentismos_mes_pasado_count_total = 0;
								$ausentismos_year_count_total = 0;
								$accidentes_mes_count_total = 0;
								$accidentes_mes_pasado_count_total = 0;
							 @endphp
							@foreach ($clientes_nominas->clientes as $cliente)
							@php
								$nominas_count_total += $cliente->nominas_count;
								$ausentismos_count_total += $cliente->ausentismos_count;
								$ausentismos_mes_count_total += $cliente->ausentismos_mes_count;
								$ausentismos_mes_pasado_count_total += $cliente->ausentismos_mes_pasado_count;
								$ausentismos_year_count_total += $cliente->ausentismos_year_count;
								$accidentes_mes_count_total += $cliente->accidentes_mes_count;
								$accidentes_mes_pasado_count_total += $cliente->accidentes_mes_pasado_count;
							@endphp
							<tr>
								<td>{{$cliente->nombre}}</td>
								<td class="text-right">{{$cliente->nominas_count}}</td>
								<td class="text-right">{{$cliente->ausentismos_count}}</td>
								<td class="text-right">{{$cliente->ausentismos_mes_count}}</td>
								<td class="text-right">{{$cliente->ausentismos_mes_pasado_count}}</td>
								<td class="text-right">{{$cliente->ausentismos_year_count}}</td>
								<td class="text-right">{{$cliente->accidentes_mes_count}}</td>
								<td class="text-right">{{$cliente->accidentes_mes_pasado_count}}</td>
							</tr>
							@endforeach
						</tbody>
						<tfoot>
							<tr class="alert-success">
								<th>Total</th>
								<th class="text-right">{{$nominas_count_total}}</th>
								<th class="text-right">{{$ausentismos_count_total}}</th>
								<th class="text-right">{{$ausentismos_mes_count_total}}</th>
								<th class="text-right">{{$ausentismos_mes_pasado_count_total}}</th>
								<th class="text-right">{{$ausentismos_year_count_total}}</th>
								<th class="text-right">{{$accidentes_mes_count_total}}</th>
								<th class="text-right">{{$accidentes_mes_pasado_count_total}}</th>
							</tr>
						</tfoot>
					</table>
				</div>


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

			</section>


		</div>



	</div>

</div>


@endsection
