{{-- SEARCH --}}
<div data-toggle="busqueda-fecha" class="row">


	<div class="col-md-6 d-flex flex-wrap align-items-center">

		<div class="row">

			<div class="col-lg-8">
				<input placeholder="Filtrar por Nombre, Consulta, Legajo, Derivación..." name="search" type="text"
					class="form-control form-control-sm" value="">
			</div>


			<div class="col-lg-4">
				<input placeholder="DNI" name="dni" type="text" class="form-control form-control-sm" value="">
			</div>

			<div class="col-lg-4">
				<input placeholder="Desde" name="from" type="text" class="form-control form-control-sm" value="">
			</div>
			<div class="col-lg-4">
				<input placeholder="Hasta" name="to" type="text" class="form-control form-control-sm" value="">
			</div>

			<div class="col-lg-4">
				<select id="estado" name="estado" class="form-control form-control-sm">
					<option value="">-Activos/Inactivos-</option>
					<option value="1">Activos</option>
					<option value="0">Inactivos</option>
				</select>
			</div>

		</div>
	</div>


	<div class="col-lg-4 border-left align-items-center d-flex flex-wrap">

		<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro mb-2">
			<i class="fas fa-search"></i> <span>Buscar</span>
		</button>
		<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro mb-2" href="#!">
			<i class="fas fa-list"></i> <span>Mostrar todo</span>
		</button>
		<button data-toggle="export" data-href="{{ url(Route::currentRouteName().'/exportar') }}" type="button"
			class="btn-ejornal btn-info mb-2">
			<i class="fas fa-file-excel"></i> <span>Exportar</span>
		</button>
	</div>

</div>
<hr>