@extends('partials.layout')

@section('title', 'Admin | Reporte de Actividad de Usuarios')

@section('content')





<div class="d-flex" id="wrapper">
	@include('partials.sidebar_admin')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')



		<div class="cabecera">
			<h2>Reportes / Actividad Usuarios</h2>
			<p>Los reportes de actividad de los usuarios</p>
		</div>


		<div class="tarjeta">

			<div data-toggle="busqueda-fecha" class="d-flex align-items-center row">

				<div class="col-lg-6">
					<div class="row">

						<div class="col-lg-6">
							<select name="user" class="form-control">
								<option value="">--Seleccionar Usuario--</option>
								@foreach($users as $user)
								<option value="{{ $user->nombre }}">{{ $user->nombre }}</option>
								@endforeach
							</select>
						</div>

						<div class="col-lg-6">
							<select name="cliente" class="form-control">
								<option value="">--Seleccionar Cliente--</option>
								@foreach($clientes as $cliente)
								<option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
								@endforeach
							</select>
						</div>

						<div class="col-lg-6">
							<input placeholder="Desde" name="from_date" type="text" class="form-control" value="">
						</div>

						<div class="col-lg-6">
							<input placeholder="Hasta" name="to_date" type="text" class="form-control" value="">
						</div>

					</div>
				</div>

				<div class="col-lg-4">
					<div class="border-left p-4">
						<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro" ><i class="fas fa-search"></i> Buscar</button>
						<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro" ><i class="fas fa-list"></i> Mostrar todo</button>

						<button data-toggle="export" data-href="{{ url(Route::currentRouteName().'/exportar') }}" type="button" class="btn-ejornal btn-info"><i class="fas fa-file-excel"></i> Exportar</button>
					</div>
				</div>


		  </div>

		  <hr>


		  <div class="table-responsive">
				<table data-table="actividades" class="table table-striped table-hover table-sm w-100">
					<thead>
						<tr>
						  <th>Usuario</th>
						  <th>Cliente</th>
							<th>Fecha</th>
							<th>Actividad</th>
							<th>Trabajador</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
		  </div>


		</div>



	</div>

</div>

@endsection