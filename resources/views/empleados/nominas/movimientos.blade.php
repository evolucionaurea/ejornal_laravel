@extends('partials.layout')

@section('title', 'Clientes')

@section('content')

<div class="d-flex" id="wrapper">

	@include('partials.sidebar_empleados')

	<div id="page-content-wrapper">
		@include('partials.nav_sup')
		<div class="cabecera">
			<h2>Movimientos de los trabajadores de la nómina</h2>
			<p>Si un trabajador se mueve de una empresa a otra (o entre sucursales de la misma empresa) se verá reflejado en este listado.</p>

			<div class="form-group">
				<a href="{{url('empleados/nominas')}}" class="btn btn-dark btn-sm">
					<i class="fa fa-angle-double-left fa-fw"></i>
					<span>Volver al listado</span>
				</a>
			</div>

			<hr>


			<div data-toggle="busqueda-filtros" class="row">
				<div class="col-lg-6">

					<div class="row">
						<div class="mb-1 col-md-6">
							<input name="search" class="form-control form-control-sm" placeholder="Filtrar por nombre..." value="" >
						</div>
						<div class="mb-1 col-md-6">
							<select name="cliente" class="form-control form-control-sm">
								<option value="">--Seleccionar Cliente--</option>
							</select>
						</div>
					</div>
				</div>

				<div class="col-lg-4 border-left align-items-center d-flex flex-wrap">
					<div class="mr-1 mb-1">
						<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro mb-2">
							<i class="fas fa-search"></i> <span>Buscar</span>
						</button>
					</div>
				</div>
			</div>

			<hr>

			<table data-table="movimientos" class="table table-striped table-hover table-sm dt-responsive">
				<thead>
					<tr>
						<th>Trabajador</th>
						<th>Cliente</th>
						<th>Usuario</th>
						<th>Fecha Creado</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>

		</div>

	</div>
</div>


@endsection
