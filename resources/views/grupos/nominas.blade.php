@extends('partials.layout')

@section('title', 'Grupos')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_grupos')

	<div id="page-content-wrapper">

		@include('partials.nav_sup')

		<div class="cabecera">
			<h2>Listado de trabajadores</h2>
			<p>Aquí puede ver el listado de trabajadores de la sucursal seleccionada: <b>{{ $cliente_actual->nombre }}</b></p>
		</div>


		<div class="tarjeta">

			<div data-toggle="busqueda" class="row align-items-center">

				<input type="hidden" name="filtro" value="{{ Request::get('filtro') }}">

				<div class="col-lg-3 form-group">
					<select name="estado" class="form-control form-control-sm">
						<option value="">--Activos/Inactivos--</option>
						<option value="1">Sólo Activos</option>
						<option value="0">Sólo Inactivos</option>
					</select>
				</div>

				<div class="col-lg-9 form-group">
					<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro" ><i class="fas fa-search"></i> Buscar</button>
					<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-list"></i> Mostrar todo</button>
				</div>

			</div>
			<hr>


			<table class="table table-striped table-hover table-sm tabla_nominas">

				<!--Table head-->
				<thead>
					<tr>
						<th class="th-lg">Nombre</th>
						<th class="th-lg">Email</th>
						<th class="th-lg">Tel</th>
						<th class="th-lg">DNI</th>
						<th class="th-lg">Estado</th>
						<th class="th-lg">Sector</th>
					</tr>
				</thead>
				<!--Table head-->

				<!--Table body-->
				<tbody></tbody>
				<!--Table body-->
			</table>
		</div>


	</div>

</div>


@endsection