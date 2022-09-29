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
				<div class="row d-none">
					<div class="col-lg-3 col-md-6 mb-4">
						<a href="#" class="media white z-depth-1 rounded">
							<i class="fas fa-briefcase fa-lg blue z-depth-1 p-4 rounded-left text-white mr-3"></i>
							<div class="media-body p-1">
								<p class="text-uppercase text-muted mb-1"><small>Empresas del grupo</small></p>
								<h5 class="font-weight-bold mb-0">{{count($grupo->clientes)}}</h5>
							</div>
						</a>
					</div>
				</div>

				<div class="tarjeta">
					<h4>Empresas del grupo: {{$grupo->clientes->count()}}</h4>
					<table data-table="ausentismos" class="table table-striped">
						<thead>
							<tr>
								<th scope="col"> <b>Nombre</b></th>
								<th class="text-right">Total Nómina</th>
								<th class="text-right">Ausentes hoy</th>
								<th class="text-right">Ausentismos {{ Str::ucfirst($carbon::now()->formatLocalized('%B')) }}</th>
								<th class="text-right">Ausentismos del año</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($clientes_nominas->clientes as $cliente)
							<tr>
								<td>{{$cliente->nombre}}</td>
								<td class="text-right">{{$cliente->nominas_count}}</td>
								<td class="text-right">{{$cliente->ausentismos_count}}</td>
								<td class="text-right">{{$cliente->ausentismos_mes_count}}</td>
								<td class="text-right">{{$cliente->ausentismos_year_count}}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>

			</section>

			<hr>

			<!-- Ausentismos Empresa Actual -->
			<section>

				<h5 class="font-weight-bold mt-4 text-center">Ausentismos en {{$cliente_actual->nombre}}</h5>


				<div class="row d-flex justify-content-center">
					<div class="col-md-5 tarjeta">
						<h4 class="text-center">Ausentismos de {{ Str::ucfirst($carbon::now()->formatLocalized('%B')) }}</h4>
						<div data-toggle="blank-chart-ausentismos-mes" class="alert alert-info text-center d-none">No hay datos</div>
						<canvas id="chart_ausentismos_mes" height="280"></canvas>
					</div>
					<div class="col-md-5 tarjeta">
						<h4 class="text-center">Ausentismos del año</h4>
						<div data-toggle="blank-chart-ausentismos-anual" class="alert alert-info text-center d-none">No hay datos</div>
						<canvas id="chart_ausentismos_anual" height="280"></canvas>
					</div>
				</div>

			</section>


		</div>



	</div>

</div>


@endsection
