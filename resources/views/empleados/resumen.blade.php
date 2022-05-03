@extends('partials.layout')

@section('title', 'Empleado')

@section('content')


<div class="d-flex" id="wrapper">

	@include('partials.sidebar_empleados')

	<div id="page-content-wrapper">

		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		@if (!isset($clientes) || empty($clientes) || count($clientes) < 1)
		<div class="tarjeta">
			<section class="text-center px-md-5 mx-md-5 dark-grey-text">
				<div class="row mb-5">
					<div class="col-md-4 mx-auto">
						<div class="mb-4 pb-2">
							<i class="fas fa-tools fa-10x text_danger"></i>
						</div>
					</div>
				</div>
				<h3 class="font-weight-bold mb-4 pb-2 text_danger">No tienes empresas asignadas</h3>
				<p class="text-center mx-auto mb-4 pb-2">Comuníquese con la empresa o su superior para solicitar que le asignen alguna empresa</p>
			</section>
		</div>

		@else

		<div class="container">
			<section>
				<div class="row">


					<!-- Nómina -->
					<div class="col-md-4 col-lg-4 mb-4">
						<form action="{{ action('EmpleadosNominasController@listado') }}" class="card primary-color white-text" method="POST">
							@csrf

							<input type="hidden" name="estado" value="1">
							<button class="card-body d-flex justify-content-between align-items-center" >
								<div>
									<p class="h2-responsive font-weight-bold mt-n2 mb-0">{{$total_nomina}}</p>
									<p class="mb-0">Nómina (Activos)</p>
								</div>
								<div>
									<i class="fas fa-users fa-2x text-black-40"></i>
								</div>
							</button>
						</form>
					</div>

					<!-- Ausentes -->
					<div class="col-md-4 col-lg-4 mb-4">
						<form action="{{ action('EmpleadosNominasController@listado') }}" class="card red white-text" method="POST">
							@csrf
							<input type="hidden" name="ausentes" value="hoy">
							<button class="card-body d-flex justify-content-between align-items-center">
								<div>
									<p class="h2-responsive font-weight-bold mt-n2 mb-0">{{$ausentes_hoy}}</p>
									<p class="mb-0">Ausentes del día</p>
								</div>
								<div>
									<i class="fas fa-users fa-2x text-black-40"></i>
								</div>
							</button>
						</form>
					</div>

					<!-- Ausencias COVID -->
					<div class="col-md-4 col-lg-4 mb-4">
						<form action="{{ action('EmpleadosNominasController@listado') }}" class="card red white-text" method="POST">
							@csrf
							<input type="hidden" name="ausentes" value="covid">
							<button class="card-body d-flex justify-content-between align-items-center">
								<div>
									<p class="h2-responsive font-weight-bold mt-n2 mb-0">{{$ausencia_covid}}</p>
									<p class="mb-0">Ausencias del día por covid</p>
								</div>
								<div>
									<i class="fas fa-users fa-2x text-black-40"></i>
								</div>
							</button>
						</form>
					</div>


					<!-- Consultas Médicas x Mes -->
					<div class="col-md-4 col-lg-4 mb-4">
						<a href="{{route('empleados.consultas.medicas',['filtro'=>'mes'])}}" class="card light-blue lighten-1 white-text">
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
						<a href="{{route('empleados.consultas.enfermeria',['filtro'=>'mes'])}}" class="card light-blue lighten-1 white-text">
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


					<!-- Consultas día -->
					<div class="col-md-4 col-lg-4 mb-4">
						<div class="card light-blue lighten-1 white-text">
							<div class="card-body d-flex justify-content-between align-items-center">
								<div>
									<p class="h2-responsive font-weight-bold mt-n2 mb-0">{{$consultas_medicas + $consultas_enfermeria}}</p>
									<p class="mb-0">Consultas totales del día</p>
								</div>
								<div>
									<i class="fas fa-comments fa-2x text-black-40"></i>
								</div>
							</div>
						</div>
					</div>

					<!-- Testeos Positivos -->
					<div class="col-md-4 col-lg-3 mb-4">
						<a href="{{route('empleados.covid.testeos',['filtro'=>'positivos'])}}" class="card secondary-color-dark lighten-1 white-text">
							<div class="card-body d-flex justify-content-between align-items-center">
								<div>
									<p class="h2-responsive font-weight-bold mt-n2 mb-0">{{$testeos_positivos}}</p>
									<p class="mb-0">Testeos positivos</p>
								</div>
								<div>
									<i class="fas fa-disease fa-2x text-black-40"></i>
								</div>
							</div>
						</a>
					</div>


					<!-- 1 dosis -->
					<div class="col-md-4 col-lg-3 mb-4">
						<a href="{{route('empleados.covid.vacunas',['filtro'=>'dosis_1'])}}" class="card secondary-color-dark accent-2 white-text">
							<div class="card-body d-flex justify-content-between align-items-center">
								<div>
									<p class="h2-responsive font-weight-bold mt-n2 mb-0">{{$cant_vacunados_una_dosis}}</p>
									<p class="mb-0">Con 1 dosis</p>
								</div>
								<div>
									<i class="fas fa-disease fa-2x text-black-40"></i>
								</div>
							</div>
						</a>
					</div>


					<!-- 2 dosis -->
					<div class="col-md-4 col-lg-3 mb-4">
						<a href="{{route('empleados.covid.vacunas',['filtro'=>'dosis_2'])}}" class="card secondary-color-dark accent-2 white-text">
							<div class="card-body d-flex justify-content-between align-items-center">
								<div>
									<p class="h2-responsive font-weight-bold mt-n2 mb-0">{{$cant_vacunados_dos_dosis}}</p>
									<p class="mb-0">Con 2 dosis</p>
								</div>
								<div>
									<i class="fas fa-disease fa-2x text-black-40"></i>
								</div>
							</div>
						</a>
					</div>

					<!-- 3 dosis -->
					<div class="col-md-4 col-lg-3 mb-4">
						<a href="{{route('empleados.covid.vacunas',['filtro'=>'dosis_3'])}}" class="card secondary-color-dark accent-2 white-text">
							<div class="card-body d-flex justify-content-between align-items-center">
								<div>
									<p class="h2-responsive font-weight-bold mt-n2 mb-0">{{$cant_vacunados_tres_dosis}}</p>
									<p class="mb-0">Con 3 dosis</p>
								</div>
								<div>
									<i class="fas fa-disease fa-2x text-black-40"></i>
								</div>
							</div>
						</a>
					</div>

				</div>
			</section>
		</div>

		@endif

	{{-- Contenido de la pagina --}}
	</div>

</div>


@endsection
