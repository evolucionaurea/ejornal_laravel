@extends('partials.layout')

@section('title', 'Admin | Comunicaciones')

@section('content')


<div class="d-flex" id="wrapper">
	@include('partials.sidebar_admin')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Reportes / Comuinicaciones</h2>
			<p>Aquí puede ver los reportes del sistema</p>
		</div>

		@include('../mensajes_validacion')


		<div class="tarjeta">

			<div data-toggle="busqueda-fecha" class="d-flex align-items-center row">

				<div class="col-lg-8">
					<div class="row">
						<div class="col-md-4">
							<select name="cliente" class="form-control form-control-sm">
								<option value="">--Seleccionar Cliente--</option>
								@foreach($clientes as $cliente)
								<option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-4">
							<select name="ausentismo_tipo" class="form-control form-control-sm">
								<option value="">--Seleccionar Tipo de Ausentismo--</option>
								@foreach($ausentismo_tipos as $ausentismo_tipo)
								<option value="{{ $ausentismo_tipo->id }}">{{ $ausentismo_tipo->nombre }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-4">
							<select name="comunicacion_tipo" class="form-control form-control-sm">
								<option value="">--Seleccionar Tipo de Comunicación--</option>
								@foreach($comunicacion_tipos as $comunicacion_tipo)
								<option value="{{ $comunicacion_tipo->id }}">{{ $comunicacion_tipo->nombre }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-4">
							<input placeholder="Desde" name="from" type="text" class="form-control form-control-sm" value="">
						</div>
						<div class="col-md-4">
							<input placeholder="Hasta" name="to" type="text" class="form-control form-control-sm" value="">
						</div>

					</div>
				</div>

				<div class="col-lg-4">
					<div class="border-left p-4">
						<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro" ><i class="fas fa-search"></i> Buscar</button>
						<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro" ><i class="fas fa-list"></i> Mostrar todo</button>
					</div>
				</div>


		  </div>

		  <hr>

		  <div class="table-responsive">
				<table data-table="comunicaciones" class="table table-striped table-hover table-sm w-100">
					<thead>
						<tr>
						  <th>Trabajador</th>
						  <th>Cliente</th>
							<th>Tipo ausentismo</th>
							<th>Tipo comunicación</th>
							<th>User que registra</th>
							<th>Descripción</th>
							<th>Fecha de carga</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
		  </div>


		</div>

	</div>
</div>


{{-- @include("../scripts_reportes_comunicaciones") --}}

@endsection