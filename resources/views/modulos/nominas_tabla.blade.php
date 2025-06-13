<div data-toggle="busqueda-filtros" class="row">

	<input type="hidden" name="filtro" value="{{ Request::get('filtro') }}">

	<div class="col-lg-6">

		<div class="row">
			<div class="mb-1 col-md-6">
				<input name="search" class="form-control form-control-sm"
					placeholder="Filtrar por nombre, dni, cuil, legajo o tel..." value="{{ Request::get('search') }}">
			</div>
			<div class="mb-1 col-md-6">
				<select name="ausentes" class="form-control form-control-sm">
					<option value="">--Seleccionar Filtro--</option>
					<option value="hoy" {{Request::get('ausentes')=='hoy' ? 'selected' : '' }}>Ausentes Hoy</option>
					<option value="covid" {{Request::get('ausentes')=='covid' ? 'selected' : '' }}>Ausentes Hoy COVID
					</option>
					<option value="accidente" {{Request::get('ausentes')=='accidente' ? 'selected' : '' }}>Ausentes Hoy
						Accidente</option>
					<option value="incidente" {{Request::get('ausentes')=='incidente' ? 'selected' : '' }}>Ausentes Hoy
						Incidente</option>
				</select>
			</div>
			<div class="mb-1 col-md-6">
				<select name="estado" class="form-control form-control-sm">
					<option value="">--Seleccionar Estado--</option>
					<option value="1" {{Request::get('estado')=='1' ? 'selected' : '' }}>Sólo Activos</option>
					<option value="0" {{Request::get('estado')=='0' ? 'selected' : '' }}>Sólo Inactivos</option>
				</select>
			</div>
		</div>




	</div>

	<div class="col-lg-4 border-left align-items-center d-flex flex-wrap">

		<div class="mr-1 mb-1">
			<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro mb-2"><i class="fas fa-search"></i>
				Buscar</button>
		</div>
		<div class="mr-1 mb-1">
			<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro mb-2" href="#!"><i
					class="fas fa-list"></i> Mostrar Todo</button>
		</div>
		<div class="mr-1 mb-1">
			<button data-toggle="export" data-href="{{url(Route::currentRouteName().'/exportar')}}" target="_blank"
				class="btn-ejornal btn-info mb-2"><i class="fas fa-file-excel"></i> Exportar</button>
		</div>
	</div>

</div>

<hr>


<table class="table table-striped table-hover table-sm tabla_nominas dt-responsive">

	<!--Table head-->
	<thead>
		<tr>
			<th>Foto</th>
			<th>Nombre</th>
			<th>CUIL</th>
			<th>Tel</th>
			<th>Legajo</th>
			<th>DNI</th>
			<th>Fecha Nac.</th>
			<th>Estado</th>
			<th>Sector</th>
			<th>Hoy</th>
			<th>Fecha de Alta</th>
			<th>Acciones</th>
		</tr>
	</thead>
	<!--Table head-->

	<!--Table body-->
	<tbody>

	</tbody>
	<!--Table body-->
</table>