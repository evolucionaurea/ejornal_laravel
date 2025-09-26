@extends('partials.layout')

@section('title', 'Cliente')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_clientes')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Listado de estudios médicos complementarios</h2>
			<p>Aquí puede ver el listado de estudios médicos complementarios de la empresa.</p>
		</div>

		@include('../mensajes_validacion')

		<div class="tarjeta">

			<div data-toggle="busqueda-fecha" class="d-flex align-items-center row">

				<div class="col-lg-6">
					<div class="row">

						<div class="col-md-12">
							<input type="text" name="search" class="form-control form-control-sm"
								placeholder="Filtrar por trabajador, dni, cuil, tel...">
						</div>

						<div class="col-md-6">
							<input placeholder="Fecha Desde" name="from" type="text"
								class="form-control form-control-sm" value="" readonly>
						</div>
						<div class="col-md-6">
							<input placeholder="Fecha Hasta" name="to" type="text" class="form-control form-control-sm"
								value="" readonly>
						</div>
						<div class="col-md-6">
							<select name="tipo" class="form-control form-control-sm">
								<option value="">--Seleccionar Tipo de Estudio--</option>
								@foreach($tipos as $tipo)
								<option value="{{ $tipo->id }}">{{ $tipo->name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-6">
							<select name="vencimiento" class="form-control form-control-sm">
								<option value="">--Con/Sin Vencimiento--</option>
								<option value="1">Con Vencimiento</option>
								<option value="0">Sin Vencimiento</option>
							</select>
						</div>
						<div class="col-md-6 d-none" data-toggle="vencimiento">
							<select name="vencimiento_estado" class="form-control form-control-sm">
								<option value="">--Seleccionar Estado--</option>
								<option value="vencidos">Vencidos</option>
								<option value="vencimiento_proximo">Vencimiento próximo (30 días)</option>
							</select>
						</div>
						<div class="col-md-6 d-none" data-toggle="vencimiento">
							<select name="completado" class="form-control form-control-sm">
								<option value="">--Completado / Sin Completar--</option>
								<option value="1">Completados</option>
								<option value="0">Sin Completar</option>
							</select>
						</div>

					</div>
				</div>

				<div class="col-lg-4">
					<div class="border-left p-4">
						<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro"><i
								class="fas fa-search"></i> Buscar</button>
						<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro"><i
								class="fas fa-list"></i> Mostrar todo</button>
					</div>
				</div>


			</div>

			<hr>


			<table data-table="preocupacionales" class="table table-striped table-hover table-sm">

				<!--Table head-->
				<thead>
					<tr>
						<th>Trabajador</th>
						<th>Legajo</th>
						<th>Fecha</th>
						<th>Tipo de Estudio</th>
						<th>Vencimiento</th>
						{{-- <th>Estado Venc.</th> --}}
						{{-- <th>Completado</th> --}}
						<th>Documentación</th>
						<th>Acciones</th>
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