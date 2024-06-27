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
			<p>Aquí puedes ver los reportes del sistema.</p>
		</div>

		@include('../mensajes_validacion')

		<div class="tarjeta">

		  <div data-toggle="busqueda-fecha" class="d-flex align-items-center row">

				<div class="col-lg-6">
					<div class="row">
						<div class="col-md-6">
							<select name="cliente" class="form-control form-control-sm">
								<option value="">--Seleccionar Cliente--</option>
								@foreach($clientes as $cliente)
								<option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-6">
							<select name="tipo" class="form-control form-control-sm">
								<option value="">--Seleccionar Tipo de Ausentismo--</option>
								@foreach($ausentismo_tipos as $ausentismo_tipo)
								<option value="{{ $ausentismo_tipo->id }}">{{ $ausentismo_tipo->nombre }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-6">
							<input placeholder="Desde" name="from" type="text" class="form-control form-control-sm" value="">
						</div>
						<div class="col-md-6">
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


			<table data-table="certificaciones" class="table table-striped table-hover table-sm">

				<!--Table head-->
				<thead>
					<tr>
					  <th>Cliente</th>
						<th>Trabajador</th>
						<th>User que registra</th>
						<th>Tipo</th>
						<th>Fecha inicio</th>
						<th>Fecha final</th>
						<th>Días ausente</th>
						<th style="width:300px">Certificados</th>
					</tr>
				</thead>
				<!--Table head-->

				<!--Table body-->
				<tbody class="resultados_reporte_certificaciones">
					{{-- Se llena por JS --}}
				</tbody>
				<!--Table body-->
			</table>
		</div>


		{{-- Contenido de la pagina --}}
	</div>
</div>

@endsection

{{-- @include("../scripts_reportes_certificaciones") --}}
{{-- @include("../modal_reportes") --}}
