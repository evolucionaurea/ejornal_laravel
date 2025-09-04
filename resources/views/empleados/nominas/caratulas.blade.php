@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		<div class="cabecera">
			<h2>Listado de carátulas</h2>
			<p>Aquí puede ver el listado de las últimas carátulas de los trabajadores de la empresa.</p>
			
			@if (auth()->user()->fichada == 1 || !auth()->user()->fichar)
			<div class="cabecera_acciones">
				<a href="{{ route('empleados.nominas.caratulas.create') }}" class="btn btn-primary">
					<i class="fal fa-plus"></i>
					<span>Crear carátula</span>
				</a>
			</div>
			@else 
			<a data-toggle="modal" data-target="#fichada_huella" class="small text-muted font-italic">
				<i class="fa fa-fingerprint  fa-fw"></i> Debes fichar para crear una nueva carátula
			</a>
			@endif
		</div>

		@include('mensajes_validacion')

		<div class="tarjeta">

			<div data-toggle="busqueda-fecha" class="row">

				<input type="hidden" name="filtro" value="{{ Request::get('filtro') }}">				

				<div class="col-lg-6">

					<div class="row">

						<div class="mb-1 col-md-6">
							<input name="search" class="form-control form-control-sm"
								placeholder="Buscar por nombre, dni, cuil, legajo o tel..."
								value="{{ Request::get('search') }}"></input>
						</div>

						<div class="mb-1 col-md-6">
							<select name="estado" class="form-control form-control-sm">
								<option value="">--Seleccionar Filtro--</option>
								<option value="1" >Trabajadores Activos</option>
								<option value="0" >Trabajadores Inactivos</option>
							</select>
						</div>
					</div>

				</div>

				<div class="col-lg-4 border-left align-items-center d-flex">

					<div class="mr-1 mb-1">
						<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro"><i class="fas fa-search"></i>
							Buscar</button>
					</div>
					<div class="mr-1 mb-1">
						<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-list"></i>
							Mostrar todo</button>
					</div>

					<div class="mr-1 mb-1">
						<button data-toggle="export" data-href="{{ 'caratulas/exportar' }}"
							class="btn-ejornal btn-info"><i class="fas fa-file-excel"></i> Exportar</button>
					</div>

				</div>


			</div>
			<hr>


			<table data-table="caratulas" class="table table-striped table-sm tabla" >
				<thead>
					<tr>
						<th>ID</th>
						<th>Trabajador</th>
						<th>Patologías</th>
						<th>Usuario que cargó</th>
						<th>Peso / Altura / IMC</th>
						<th>Medicación habitual</th>
						<th>Antecedentes</th>
						<th>Alergias</th>
						<th>Última modificación</th>
						<th class="text-right">Acciones</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>			
		</div>
	</div>

</div>

@endsection