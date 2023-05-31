<div data-toggle="busqueda-filtros" class="d-flex row">

	<div class="col-12 d-flex align-items-center">


		<input type="hidden" name="filtro" value="{{ Request::get('filtro') }}">

		<select name="ausentes" class="form-control form-control-sm mr-2" style="max-width:220px">
			<option value="">--Seleccionar Filtro--</option>
			<option value="hoy" {{Request::get('ausentes')=='hoy' ? 'selected' : '' }}>Ausentes Hoy</option>
			<option value="covid" {{Request::get('ausentes')=='covid' ? 'selected' : '' }}>Ausentes Hoy COVID</option>
			<option value="accidente" {{Request::get('ausentes')=='accidente' ? 'selected' : '' }}>Ausentes Hoy Accidente</option>
			<option value="incidente" {{Request::get('ausentes')=='incidente' ? 'selected' : '' }}>Ausentes Hoy Incidente</option>
		</select>

		<select name="estado" class="form-control form-control-sm mr-2" style="max-width:220px">
			<option value="">--Seleccionar Filtro--</option>
			<option value="1" {{Request::get('estado')=='1' ? 'selected' : '' }} >Sólo Activos</option>
			<option value="0" {{Request::get('estado')=='0' ? 'selected' : '' }} >Sólo Inactivos</option>
		</select>

		<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro" ><i class="fas fa-search"></i> Buscar</button>
		<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-list"></i> Mostrar Todo</button>

		<button data-toggle="export" data-href="{{url(Route::currentRouteName().'/exportar')}}" target="_blank" class="btn-ejornal btn-info" ><i class="fas fa-file-excel"></i> Exportar</button>


	</div>
</div>

<hr>


<table class="table table-striped table-hover table-sm tabla_nominas dt-responsive">

	<!--Table head-->
	<thead>
		<tr>
			<th>Foto</th>
			<th>Nombre</th>
			<th>Email</th>
			<th>Tel</th>
			<th>DNI</th>
			<th>Estado</th>
			<th>Sector</th>
			<th>Hoy</th>
			<th></th>
		</tr>
	</thead>
	<!--Table head-->

	<!--Table body-->
	<tbody>

	</tbody>
	<!--Table body-->
</table>