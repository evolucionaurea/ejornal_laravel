<div data-toggle="busqueda-filtros" class="row">
	<div class="col-lg-6">

		<div class="row">
			<div class="mb-1 col-md-6">
				<input name="search" class="form-control form-control-sm" placeholder="Filtrar por nombre, dni o cliente..." value="" >
			</div>

			@if($clientes)
			<div class="mb-1 col-md-6">
				<select name="cliente_id" class="form-control form-control-sm">
					<option value="">--Seleccionar Cliente--</option>
					 @foreach($clientes as $cliente)
					<option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
					@endforeach
				</select>
			</div>
			@endif

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