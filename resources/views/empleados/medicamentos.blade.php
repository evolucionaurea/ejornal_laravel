@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Movimiento de medicamentos</h2>
			<p>Aquí puede ver el movimiento de medicamentos de la empresa</p>
			@if (auth()->user()->fichada == 1 || auth()->user()->fichar == 0)
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-base" href="{{route('medicamentos.create')}}"><i
						class="fas fa-plus-circle"></i> Nuevo movimiento</a>
			</div>
			@else
			<div class="small text-muted"><i>Debe fichar para poder agregar un nuevo ausentismo</i></div>
			@endif
		</div>

		@include('../mensajes_validacion')

		<div class="tarjeta">

			@include('../../modulos.busqueda_fecha')

			<table data-table="movimientos-medicamentos"
				class="table table-striped table-hover table-sm table-bordered dt-responsive" style="width:100%">

				<!--Table head-->
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Ingreso</th>
						<th>Suministrados</th>
						<th>Egreso</th>
						<th>Stock</th>
						<th>Fecha ingreso</th>
						<th>Motivo</th>

						@if (auth()->user()->fichada == 1 || auth()->user()->fichar == 0)
						<th>Acciones</th>
						@endif
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
				<h5 class="modal-title" id="editar_stock_medicamentos_titulo">Egreso del medicamento</h5>
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
							<div class="form-group">
								<ul class="list-group list-group-flush">
									<li class="list-group-item">
										Si hubo alguna pérdida de este medicamento, ruptura o se retiró del stock
										disponible por algún motivo
										debe cargarlo aquí.
									</li>
									<li class="list-group-item">
										Si usted a suministrado un medicamento a un paciente no debe cargarlo aquí. Los
										medicamentos
										suministrados se descuentan del stock automáticamente.
									</li>
								</ul>
								<br>
								<label>Egreso</label>
								<input name="egreso" type="number" class="form-control form-control-sm" placeholder=""
									step="1">
							</div>
							<button type="submit" class="btn-ejornal btn-ejornal-success">Guardar cambios</button>
							<button type="button" class="btn-ejornal btn-ejornal-gris-claro"
								data-dismiss="modal">Cerrar</button>
						</form>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>




@endsection