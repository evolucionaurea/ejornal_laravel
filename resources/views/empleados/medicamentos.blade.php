@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Listado de medicamentos</h2>
			<p>Aquí puede ver el listado de medicamentos de la empresa</p>
			@if (auth()->user()->fichada == 1 || !auth()->user()->fichar)
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-base" href="{{route('medicamentos.create')}}"><i class="fas fa-plus-circle"></i> Nuevo movimiento</a>
			</div>
			@else
			<div class="small text-muted"><i>Debe fichar para poder agregar un nuevo ausentismo</i></div>
			@endif
		</div>

		@include('../mensajes_validacion')

		<div class="tarjeta">

			<div data-toggle="busqueda-filtros" class="row align-items-center">

				<input type="hidden" name="filtro" value="{{ Request::get('filtro') }}">
				<div class="col-lg-3">
					<input name="search" class="form-control form-control-sm" placeholder="Filtrar..." value="{{ Request::get('search') }}">
				</div>
				<div class="col-lg-4 border-left">
					<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro mb-2" ><i class="fas fa-search"></i> Buscar</button>
					<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro mb-2" href="#!"><i class="fas fa-list"></i> Mostrar todo</button>
					<button data-toggle="export" data-href="{{url(Route::currentRouteName().'/exportar')}}" target="_blank" class="btn-ejornal btn-info mb-2" ><i class="fas fa-file-excel"></i> Exportar</button>
				</div>
			</div>
			<hr>

			<table data-table="movimientos-medicamentos" class="table table-striped table-hover table-sm table-bordered dt-responsive" >

				<!--Table head-->
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Stock</th>
						<th>Total Ingresos</th>
						<th>Total Suministrados</th>
						<th>Total Egresos</th>
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



<!-- Modal Editar Stock medicamentos -->
<div class="modal fade" id="editar_stock_medicamentos" tabindex="-1" aria-labelledby="editar_stock_medicamentos_titulo"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editar_stock_medicamentos_titulo">Egreso del medicamento: <span data-content="medicamento"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="row">
					<div class="col-md-12">
						<form class="form_editar_stock_medicamentos" action="" accept-charset="UTF-8" method="post">
							{{ csrf_field() }}
							<input type="hidden" name="_method" value="PUT">

							<div class="row">
								<div class="col-lg-4 form-group">
									<label>Egreso</label>
									<input name="egreso" type="number" class="form-control" placeholder="Ingrese la cantidad" step="1" min="1" required>
								</div>
								<div class="col-lg-8 form-group">
									<label>Motivo</label>
									<textarea name="motivo" rows="4" class="form-control" required></textarea>
								</div>
							</div>
							<hr>
							<div class="alert alert-info">
								<i class="fa fa-info-circle fa-fw"></i> Si hubo alguna pérdida de este medicamento, ruptura o se retiró del stock disponible por algún motivo debe cargarlo aquí.
							</div>

							<div class="alert alert-danger">
								<i class="fa fa-exclamation-triangle fa-fw"></i> Si usted a suministrado un medicamento a un paciente no debe cargarlo aquí. Los medicamentos suministrados se descuentan del stock automáticamente.
							</div>
							<hr>


							<button type="submit" class="btn-ejornal btn-ejornal-success"><i class="fa fa-save fa-fw"></i> Guardar cambios</button>
						</form>


					</div>
				</div>

			</div>
		</div>
	</div>
</div>




@endsection