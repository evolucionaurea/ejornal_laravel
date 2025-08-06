@extends('partials.layout')

@section('title', 'Admin | Tareas Adecuadas')

@section('content')


<div class="d-flex" id="wrapper">
	@include('partials.sidebar_admin')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Reportes / Tareas Adecuadas</h2>
			<p>Aquí puede ver los reportes del sistema</p>
		</div>

		@include('../mensajes_validacion')


		<div class="tarjeta">

			<div data-toggle="busqueda-fecha" class="d-flex align-items-center row">

				<div class="col-lg-6 border-right">
					<div class="row">

						<div class="col-md-12">
							<input type="text" name="search" class="form-control form-control-sm" placeholder="Filtrar por trabajador, dni, cuil, tel...">
						</div>

						<div class="col-md-6">
							<select name="cliente_id" class="form-control form-control-sm">
								<option value="">--Seleccionar Cliente--</option>
								@foreach($clientes as $cliente)
								<option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-6">
							<select name="tipo" class="form-control form-control-sm">
								<option value="">--Seleccionar Tipo de Estudio--</option>
								@foreach($tipos as $tipo)
								<option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
								@endforeach
							</select>
						</div>

						<div class="col-md-6">
							<input placeholder="Fecha Desde" name="from" type="text" class="form-control form-control-sm" value="">
						</div>
						<div class="col-md-6">
							<input placeholder="Fecha Hasta" name="to" type="text" class="form-control form-control-sm" value="">
						</div>

					</div>
				</div>

				<div class="col-lg-4">
					<div class=" p-4">
						<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro" ><i class="fas fa-search"></i> Buscar</button>
						<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro" ><i class="fas fa-list"></i> Mostrar todo</button>
					</div>
				</div>


		  </div>

		  <hr>

		  <div class="table-responsive">
				<table data-table="tareas-livianas" class="table table-striped table-hover table-sm w-100">
					<thead>
						<tr>
						  <th>Trabajador</th>
						  <th>Cliente</th>
						  <th>Fecha Inicio</th>
						  <th>Fecha Final</th>
						  <th>Fecha de Carga</th>
							<th>Tipo</th>
							<th>User que registra</th>
							<th>Documentación</th>
							<th>Comunicaciones</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
		  </div>


		</div>

	</div>
</div>


@endsection