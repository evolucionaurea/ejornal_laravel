@extends('partials.layout')

@section('title', 'Admin')

@section('content')


<div class="d-flex" id="wrapper">
	@include('partials.sidebar_admin')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Reportes</h2>
			<p>Aquí puede ver los reportes del sistema</p>
		</div>

		@include('../mensajes_validacion')



		<div class="tarjeta" id="accordion">


			<div class="card">
				<div data-toggle="collapse" data-target="#collapse_medicas"
					class="card-header d-flex justify-content-between align-items-center clickable">
					<h4 class="mb-0">Consultas Médicas</h4>
					<i class="fal fa-chevron-circle-down"></i>
				</div>
				<div id="collapse_medicas" class="collapse show">

					<div class="p-4">

						<form data-form="form-medicas" class="row d-flex align-items-center">
							<div class="col-lg-2">
								<input placeholder="Buscar trabajador" name="keywords" type="text"
									class="form-control form-control-sm" value="">
							</div>

							<div class="col-lg-2">
								<input placeholder="Desde" name="fecha_inicio" type="text"
									class="form-control form-control-sm" value="">
							</div>

							<div class="col-lg-2">
								<input placeholder="Hasta" name="fecha_final" type="text"
									class="form-control form-control-sm" value="">
							</div>
							<div class="col-lg-3">
								<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro btn-sm">
									<i class="fas fa-search"></i> <span>Buscar</span>
								</button>
								<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro btn-sm">
									<i class="fas fa-list"></i> <span>Mostrar todo</span>
								</button>
							</div>
						</form>

						<hr>
						<table data-table="medicas" class="table table-striped table-hover table-sm w-100">

							<!--Table head-->
							<thead>
								<tr>
									<th>Nómina</th>
									<th>Cliente</th>
									{{-- <th>Temp. Auxiliar</th>
									<th>Peso</th>
									<th>Altura</th> --}}
									<th>Derivación</th>
									<th>Diagnóstico</th>
									<th>Fecha</th>
								</tr>
							</thead>
							<!--Table head-->

							<!--Table body-->
							<tbody class="resultados_reporte_consultas_medicas"></tbody>
							<!--Table body-->
						</table>
					</div>
				</div>
			</div>

			<hr>

			<div class="card">
				<div data-toggle="collapse" data-target="#collapse_enfermeria"
					class="card-header d-flex justify-content-between align-items-center clickable">
					<h4 class="mb-0">Consultas Enfermería</h4>
					<i class="fal fa-chevron-circle-down"></i>
				</div>

				<div id="collapse_enfermeria" class="collapse show">
					<div class="p-4">

						<form data-form="form-enfermeria" class="row d-flex align-items-center">

							<div class="col-lg-2">
								<input placeholder="Buscar trabajador" name="keywords" type="text"
									class="form-control form-control-sm" value="">
							</div>

							<div class="col-lg-2">
								<input placeholder="Desde" name="fecha_inicio" type="text"
									class="form-control form-control-sm" value="">
							</div>

							<div class="col-lg-2">
								<input placeholder="Hasta" name="fecha_final" type="text"
									class="form-control form-control-sm" value="">
							</div>
							<div class="col-lg-3">
								<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro btn-sm">
									<i class="fas fa-search"></i> <span>Buscar</span>
								</button>
								<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro btn-sm">
									<i class="fas fa-list"></i> <span>Mostrar todo</span>
								</button>
							</div>
						</form>

						<hr>
						<table data-table="enfermeria" class="table table-striped table-hover table-sm w-100">

							<!--Table head-->
							<thead>
								<tr>
									<th>Nómina</th>
									<th>Cliente</th>
									{{-- <th>Temp. Auxiliar</th>
									<th>Peso</th>
									<th>Altura</th> --}}
									<th>Derivación</th>
									<th>Diagnóstico</th>
									<th>Fecha</th>
								</tr>
							</thead>
							<!--Table head-->

							<!--Table body-->
							<tbody class="resultados_reporte_consultas_medicas"></tbody>
							<!--Table body-->
						</table>

					</div>

				</div>
			</div>


			<hr>

			<div class="card">
				<div data-toggle="collapse" data-target="#collapse_nutricionales"
					class="card-header d-flex justify-content-between align-items-center clickable">
					<h4 class="mb-0">Consultas Nutricionales</h4>
					<i class="fal fa-chevron-circle-down"></i>
				</div>

				<div id="collapse_nutricionales" class="collapse show">
					<div class="p-4">

						<form data-form="form-nutricionales" class="row d-flex align-items-center">

							<div class="col-lg-2">
								<input placeholder="Buscar trabajador" name="keywords" type="text"
									class="form-control form-control-sm" value="">
							</div>

							<div class="col-lg-2">
								<input placeholder="Desde" name="fecha_inicio" type="text"
									class="form-control form-control-sm" value="">
							</div>

							<div class="col-lg-2">
								<input placeholder="Hasta" name="fecha_final" type="text"
									class="form-control form-control-sm" value="">
							</div>
							<div class="col-lg-3">
								<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro btn-sm">
									<i class="fas fa-search"></i> <span>Buscar</span>
								</button>
								<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro btn-sm">
									<i class="fas fa-list"></i> <span>Mostrar todo</span>
								</button>
							</div>
						</form>

						<hr>
						<table data-table="nutricionales" class="table table-striped table-hover table-sm w-100">

							<!--Table head-->
							<thead>
								<tr>
									<th>Nómina</th>
									<th>Cliente</th>
									{{-- <th>Temp. Auxiliar</th>
									<th>Peso</th>
									<th>Altura</th> --}}
									<th>Derivación</th>
									<th>Diagnóstico</th>
									<th>Fecha</th>
								</tr>
							</thead>
							<!--Table head-->

							<!--Table body-->
							<tbody class="resultados_reporte_consultas_medicas"></tbody>
							<!--Table body-->
						</table>

					</div>

				</div>
			</div>


		</div>





		{{-- <div class="tarjeta">
			<div class="d-flex justify-content-between row">
				<h4 class="col-md-2 col-lg-5">Consultas Enfermería</h4>
				<div class="col-md-10 col-lg-7 d-flex">
					<input placeholder="Desde" id="reporte_consultas_enfermerias_desde" name="fecha_inicio"
						type="datetime" class="form-control form-control-sm mr-2 w-25 p-3" value="">
					<input placeholder="Hasta" id="reporte_consultas_enfermerias_hasta" name="fecha_final"
						type="datetime" class="form-control form-control-sm mr-2 w-25 p-3" value="">
					<a style="height: 35px; padding-top: 6px;" id="reporte_consultas_enfermerias_filtro"
						class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-search"></i> Buscar</a>
					<a style="height: 35px; padding-top: 6px;" id="reporte_consultas_enfermerias_todo"
						class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-list"></i> Mostrar
						todo</a>
				</div>
			</div>
			<table class="table table-striped table-hover table-sm tabla_reporte_consultas_enfermerias">

				<!--Table head-->
				<thead>
					<tr>
						<th>Nomina</th>
						<th>Cliente</th>
						<th>Temp. Auxiliar</th>
						<th>Peso</th>
						<th>Altura</th>
						<th>Derivación</th>
						<th>Diagnóstico</th>
						<th>Fecha</th>
					</tr>
				</thead>
				<!--Table head-->

				<!--Table body-->
				<tbody class="resultados_reporte_consultas_enfermerias"></tbody>
				<!--Table body-->
			</table>
		</div> --}}


		{{-- Contenido de la pagina --}}
	</div>
</div>


{{-- @include("../scripts_reportes_consultas") --}}
{{-- @include("../modal_reportes") --}}

@endsection