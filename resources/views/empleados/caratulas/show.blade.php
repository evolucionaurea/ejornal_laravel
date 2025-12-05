@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		<div class="cabecera">
			<h2>Historial de Carátulas de {{ $trabajador->nombre }}</h2>
			<p>Listado de ediciones de la carátula para este trabajador en <b>{{ $trabajador->cliente->nombre }}</b>
			</p>
			<div class="row">
				<div class="col-md-3">
					<a class="btn-ejornal btn-ejornal-gris-claro" href="{{ url('empleados/caratulas') }}">
						<i class="fas fa-arrow-circle-left"></i> <span>Volver</span>
					</a>
				</div>
			</div>
		</div>

		@include('mensajes_validacion')

		<div class="tarjeta">

			<!-- Formulario de filtros -->
			<div data-toggle="busqueda-fecha" class="row">

				<input type="hidden" data-toggle="no-reset" name="id_nomina" value="{{ $trabajador->id }}" class="form-control">


				<div class="col-lg-6">
					
					<div class="row">
						<div class="col-lg-4">
							<input placeholder="Desde" name="from" type="text" class="form-control form-control-sm" value="" readonly>
						</div>
						<div class="col-lg-4">
							<input placeholder="Hasta" name="to" type="text" class="form-control form-control-sm" value="" readonly>
						</div>
					</div>
				</div>

				
				<div class="col-lg-4 border-left align-items-center d-flex">

					<div class="mr-1 mb-1">
						<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro">
							<i class="fas fa-search"></i> Buscar
						</button>
					</div>
					<div class="mr-1 mb-1">
						<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro" href="#!">
							<i class="fas fa-list"></i> Mostrar todo
						</button>
					</div>

					<div class="mr-1 mb-1">
						<button data-toggle="export" data-href="{{ url('empleados/caratulas/exportar', $trabajador->id) }}"
							class="btn-ejornal btn-info">
							<i class="fas fa-file-excel"></i> Exportar
						</button>
					</div>

				</div>

			</div>

			<table data-table="caratulas" class="table table-striped table-sm tabla" >
				<thead>
					<tr>
						<th>ID</th>
						<th>Patologías</th>
						<th>User que lo carga</th>
						<th>Medicación habitual</th>
						<th>Antecedentes</th>
						<th>Alergias</th>
						<th>Peso</th>
						<th>Altura</th>
						<th>IMC</th>
						<th>Fecha</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
</div>


@endsection