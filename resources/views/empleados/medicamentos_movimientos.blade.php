@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Movimiento de medicamentos</h2>
			<p>Aquí puede ver el movimiento de cada cambio que fue sucediendo en los medicamentos</p>
		</div>

		@include('../mensajes_validacion')

		<div class="tarjeta">

			<div data-toggle="busqueda-filtros" class="row align-items-center">

				<input type="hidden" name="filtro" value="{{ Request::get('filtro') }}">

				<div class="col-lg-6">

					<div class="row border-right">

						<div class="col-lg-6">
							<input name="search" class="form-control form-control-sm" placeholder="Filtrar..." value="{{ Request::get('search') }}">
						</div>

						<div class="col-lg-6">
							<div class="input-group input-group-sm">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="fa fa-calendar"></i></span>
								</div>
								<input name="from" type="text" class="form-control" placeholder="Desde...">
							</div>
						</div>

						<div class="col-lg-6">
							<div class="input-group input-group-sm">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="fa fa-calendar"></i></span>
								</div>
								<input name="to" type="text" class="form-control" placeholder="Hasta...">
							</div>
						</div>

						<div class="col-lg-6">
							<select name="tipo" class="form-control form-control-sm" >
								<option value="">--Tipo de Consulta--</option>
								<option value="enfermeria">Enfermería</option>
								<option value="medica">Médica</option>
							</select>
						</div>

					</div>


				</div>

				<div class="col-lg-6 form-group">
					<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro mb-1" ><i class="fas fa-search"></i> Buscar</button>
					<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro mb-1" href="#!"><i class="fas fa-list"></i> Mostrar todo</button>
					<button data-toggle="export" data-href="{{url(Route::currentRouteName().'/exportar')}}" target="_blank" class="btn-ejornal btn-info mb-1" ><i class="fas fa-file-excel"></i> Exportar</button>
				</div>


			</div>
			<hr>

			<table data-table="medicamentos-movimientos" class="table table-striped table-hover table-sm">

				<!--Table head-->
				<thead>
					<tr>
						<th>Medicamento</th>
						<th>Tipo Consulta</th>
						<th>Cargado por</th>
						<th>Empresa</th>
						<th>Para</th>
						<th>Ingreso</th>
						<th>Suministrados</th>
						<th>Egreso</th>
						<th>Motivo</th>
						<th>Registro creado</th>
					</tr>
				</thead>
				<!--Table head-->

				<!--Table body-->
				<tbody></tbody>
				<!--Table body-->
			</table>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>


@endsection