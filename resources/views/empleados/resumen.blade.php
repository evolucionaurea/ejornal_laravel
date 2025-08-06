@extends('partials.layout')

@section('title', 'Empleado')

@section('content')


<div class="d-flex" id="wrapper">

	@include('partials.sidebar_empleados')

	<div id="page-content-wrapper">

		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		@if (!isset($clientes) || empty($clientes) || count($clientes) < 1) <div class="tarjeta">
			<section class="text-center px-md-5 mx-md-5 dark-grey-text">
				<div class="row mb-5">
					<div class="col-md-4 mx-auto">
						<div class="mb-4 pb-2">
							<i class="fas fa-tools fa-10x text_danger"></i>
						</div>
					</div>
				</div>
				<h3 class="font-weight-bold mb-4 pb-2 text_danger">No tienes empresas asignadas</h3>
				<p class="text-center mx-auto mb-4 pb-2">Comuníquese con la empresa o su superior para solicitar que le
					asignen alguna empresa</p>
			</section>
	</div>

	@else



	{{-- TARJETAS --}}
	<section class="tarjeta">
		<div class="row">


			<!-- Nómina -->
			<div class="col-md-4 col-lg-4 mb-4">
				<a href="{{ route('/empleados/nominas',['estado'=>1]) }}" class="card primary-color white-text">
					<div class="card-body d-flex justify-content-between align-items-center">
						<div>
							<p class="h2-responsive font-weight-bold mt-n2 mb-0">{{$total_nomina}}</p>
							<p class="mb-0">Nómina (Activos)</p>
						</div>
						<div>
							<i class="fas fa-users fa-2x text-black-40"></i>
						</div>
					</div>
				</a>
			</div>

			<!-- Ausentes -->
			<div class="col-md-4 col-lg-4 mb-4">
				<a href="{{ route('/empleados/ausentismos',['ausentes'=>'hoy']) }}" class="card red white-text">
					<input type="hidden" name="ausentes" value="hoy">
					<div class="card-body d-flex justify-content-between align-items-center">
						<div>
							<p class="h2-responsive font-weight-bold mt-n2 mb-0">{{$ausentes_hoy}}</p>
							<p class="mb-0">Ausentes del día</p>
						</div>
						<div>
							<i class="fas fa-users fa-2x text-black-40"></i>
						</div>
					</div>
				</a>
			</div>

			<!-- Ausencias COVID -->
			{{-- <div class="col-md-4 col-lg-4 mb-4">
				<a href="{{ route('/empleados/nominas',['ausentes'=>'covid']) }}" class="card red white-text">
					<input type="hidden" name="ausentes" value="covid">
					<div class="card-body d-flex justify-content-between align-items-center">
						<div>
							<p class="h2-responsive font-weight-bold mt-n2 mb-0">{{$ausencia_covid}}</p>
							<p class="mb-0">Ausencias del día por covid</p>
						</div>
						<div>
							<i class="fas fa-users fa-2x text-black-40"></i>
						</div>
					</div>
				</a>
			</div> --}}


			<!-- Consultas Médicas x Mes -->
			<div class="col-md-4 col-lg-4 mb-4">
				<a href="{{route('empleados.consultas.medicas',['filtro'=>'mes'])}}"
					class="card light-blue lighten-1 white-text">
					<div class="card-body d-flex justify-content-between align-items-center">
						<div>
							<p class="h2-responsive font-weight-bold mt-n2 mb-0">{{$medicas_mes}}</p>
							<p class="mb-0">Consultas médicas cargadas del mes</p>
						</div>
						<div>
							<i class="fas fa-comments fa-2x text-black-40"></i>
						</div>
					</div>
				</a>
			</div>

			<!-- Consultas Enfermería x Mes -->
			<div class="col-md-4 col-lg-4 mb-4">
				<a href="{{route('empleados.consultas.enfermeria',['filtro'=>'mes'])}}"
					class="card light-blue lighten-1 white-text">
					<div class="card-body d-flex justify-content-between align-items-center">
						<div>
							<p class="h2-responsive font-weight-bold mt-n2 mb-0">{{$enfermerias_mes}}</p>
							<p class="mb-0">Consultas enfermería cargadas del mes</p>
						</div>
						<div>
							<i class="fas fa-comments fa-2x text-black-40"></i>
						</div>
					</div>
				</a>
			</div>

			<!-- Consultas Nutricionales x Mes -->
			<div class="col-md-4 col-lg-4 mb-4">
				<a href="{{route('empleados.consultas.nutricionales',['filtro'=>'mes'])}}"
					class="card light-blue lighten-1 white-text">
					<div class="card-body d-flex justify-content-between align-items-center">
						<div>
							<p class="h2-responsive font-weight-bold mt-n2 mb-0">{{$nutricionales_mes}}</p>
							<p class="mb-0">Consultas nutricionales cargadas del mes</p>
						</div>
						<div>
							<i class="fas fa-comments fa-2x text-black-40"></i>
						</div>
					</div>
				</a>
			</div>


			<!-- Consultas día -->
			<div class="col-md-4 col-lg-4 mb-4">
				<div class="card light-blue lighten-1 white-text">
					<div class="card-body d-flex justify-content-between align-items-center">
						<div>
							<p class="h2-responsive font-weight-bold mt-n2 mb-0">{{$consultas_medicas +
								$consultas_enfermeria + $consultas_nutricionales}}</p>
							<p class="mb-0">Consultas totales del día</p>
						</div>
						<div>
							<i class="fas fa-comments fa-2x text-black-40"></i>
						</div>
					</div>
				</div>
			</div>



		</div>
	</section>


	{{-- ESTUDIOS COMPLEMENTARIOS --}}
	<section class="tarjeta">
		<h5 class="mb-1">Vencimientos de Estudios Complementarios</h5>
		<div class="font-italic small text-muted">Se mostrarán los estudio complementarios próximos a vencer (hasta 60
			días) y aquellos que hayan vencido.</div>
		<hr>

		<form data-toggle="busqueda-preocupacionales">
			<input type="hidden" name="completado" value="0">
			<input type="hidden" name="vencimiento" value="1">
			<input type="hidden" name="vencimiento_dias" value="60">
			<input type="hidden" name="search" value="">
		</form>

		<div class="table-responsivex">
			<table data-table="preocupacionales" class="table table-striped table-sm small bg-light w-100">
				<thead>
					<tr>
						<th>Trabajador</th>
						<th>Fecha</th>
						<th>Tipo de Estudio</th>
						<th>Vencimiento</th>
						{{-- <th>Estado Venc.</th> --}}
						{{-- <th>Completado</th> --}}
						<th>Documentación</th>
						<th class="text-right">Acciones <i class="fa fa-question-circle fa-fw"
								data-swal="Click en el <i class='fa fa-check-circle text-success'></i> para marcar al estudio como completado"></i>
						</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>

	</section>




	@endif

	{{-- Contenido de la pagina --}}
</div>

</div>


@endsection