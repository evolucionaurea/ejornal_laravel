<div data-toggle="busqueda-fecha" class="d-flex align-items-center flex-wrap">

	<input type="hidden" name="filtro" value="{{ Request::get('filtro') }}">

	<div class="form-group mr-3" style="max-width:220px">
		<select name="tipo" class="form-control form-control-sm select_2">
			<option value="">--Todos los tipos--</option>
			@foreach ($tipos as $tipo)
			<option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
			@endforeach
		</select>
	</div>

	<div class="form-group mr-3" style="max-width:220px">
		<select name="ausentes" class="form-control form-control-sm">
			<option value="">--Seleccionar Filtro--</option>
			<option value="hoy" {{ Request::get('ausentes')  == 'hoy' ? 'selected' : '' }} >Ausentes Hoy</option>
			<option value="mes" {{ Request::get('ausentes')  == 'mes' ? 'selected' : '' }} >Ausentes Este Mes</option>
			<option value="mes-pasado" {{ Request::get('ausentes')  == 'mes-pasado' ? 'selected' : '' }} >Ausentes Mes Pasado</option>
		</select>
	</div>

	<div class="form-group mr-3" style="max-width:220px">
		<select name="estado" class="form-control form-control-sm">
			<option value="">--Seleccionar Filtro--</option>
			<option value="activo" {{ Request::get('estado')  == 'activo' ? 'selected' : '' }} >Trabajadores Activos</option>
			<option value="inactivo" {{ Request::get('estado')  == 'inactivo' ? 'selected' : '' }} >Trabajadores Inactivos</option>
		</select>
	</div>


	<div class="form-group mr-3" style="max-width:220px">
		<input placeholder="Desde" name="from" type="datetime" class="form-control form-control-sm"
			value="{{$fecha_inicio ?? ''}}">
	</div>

	<div class="form-group mr-3" style="max-width:220px">
		<input placeholder="Hasta" name="to" type="datetime" class="form-control form-control-sm"
			value="{{$fecha_final ?? ''}}">
	</div>


	<div class="form-group mr-3">
		<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro"><i class="fas fa-search"></i> Buscar</button>
	</div>
	<div class="form-group mr-3">
		<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-list"></i> Mostrar todo</button>
	</div>
	<div class="form-group mr-3">
		<a href="{{url(Route::currentRouteName().'/exportar')}}" target="_blank" class="btn-ejornal btn-info" ><i class="fas fa-file-excel"></i> Exportar Todo</a>
	</div>



</div>
<hr>


<table class="table table-striped table-hover table-sm tabla_ausentismos">

	<!--Table head-->
	<thead>
		<tr>
			<th>Trabajador</th>
			<th>DNI</th>
			<th>Sector</th>
			<th>Tipo</th>
			<th>Fecha inicio</th>
			<th>Fecha final</th>
			<th>Fecha en que regresó</th>
			<th>Hoy</th>

			<th></th>


		</tr>
	</thead>
	<!--Table head-->

	<!--Table body-->
	<tbody></tbody>
	<!--Table body-->
</table>