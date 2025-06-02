<div data-toggle="busqueda-fecha" class="row">

	<input type="hidden" name="filtro" value="{{ Request::get('filtro') }}">


	<div class="col-lg-6">

		<div class="row">

			<div class="mb-1 col-md-6">
				<input name="search" class="form-control form-control-sm"
					placeholder="Buscar por nombre, dni, cuil o tel..." value="{{ Request::get('search') }}"></input>
			</div>


			<div class="mb-1 col-md-6">
				<select name="tipo" class="form-control form-control-sm select_2">
					<option value="">--Todos los tipos--</option>
					@foreach ($tipos as $tipo)
					<option value="{{$tipo->id}}" {{ Request::get('tipo')==$tipo->id ? 'selected' : '' }}
						>{{$tipo->nombre}}
					</option>
					@endforeach
				</select>
			</div>

			<div class="mb-1 col-md-6">
				<select name="ausentes" class="form-control form-control-sm">
					<option value="">--Seleccionar Filtro--</option>
					<option value="hoy" {{ Request::get('ausentes')=='hoy' ? 'selected' : '' }}>Ausentes Hoy</option>

					<option value="mes-actual" {{ Request::get('ausentes')=='mes-actual' ? 'selected' : '' }}>Ausentismos mes actual</option>
					<option value="mes-anterior" {{ Request::get('ausentes')=='mes-anterior' ? 'selected' : '' }}>Ausentismos mes anterior</option>
					<option value="mes-anio-anterior" {{ Request::get('ausentes')=='mes-anio-anterior' ? 'selected' : ''
						}}>Ausentismos mismo mes año anterior</option>
					<option value="anio-actual" {{ Request::get('ausentes')=='anio-actual' ? 'selected' : '' }}>Ausentismos año actual</option>

					{{-- <option value="mes-actual-carga" {{ Request::get('ausentes')=='mes-actual-carga' ? 'selected'
						: '' }}>Ausentismos cargados en mes actual</option>
					<option value="mes-anterior-carga" {{ Request::get('ausentes')=='mes-anterior-carga' ? 'selected'
						: '' }}>Ausentismos cargados el mes pasado</option> --}}
				</select>
			</div>

			<div class="mb-1 col-md-6">
				<select name="estado" class="form-control form-control-sm">
					<option value="">--Seleccionar Filtro--</option>
					<option value="activo" {{ Request::get('estado')=='activo' || Request::get('ausentes')=='hoy'
						? 'selected' : '' }}>Trabajadores Activos</option>
					<option value="inactivo" {{ Request::get('estado')=='inactivo' ? 'selected' : '' }}>Trabajadores
						Inactivos</option>
				</select>
			</div>
		</div>

	</div>

	<div class="col-lg-4 border-left align-items-center d-flex">

		<div class="mr-1 mb-1">
			<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro"><i class="fas fa-search"></i>
				Buscar</button>
		</div>
		<div class="mr-1 mb-1">
			<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-list"></i>
				Mostrar todo</button>
		</div>

		<div class="mr-1 mb-1">
			<button data-toggle="export" data-href="{{url(Route::currentRouteName().'/exportar')}}"
				class="btn-ejornal btn-info"><i class="fas fa-file-excel"></i> Exportar</button>
		</div>

	</div>


</div>
<hr>


<div class="table-responsivexx">

	<table data-table="ausentismos" class="table table-striped table-hover table-sm tabla_ausentismos">
		<!--Table head-->
		<thead>
			<tr>
				<th>ID</th>
				<th>Trabajador</th>
				<th>Sector</th>
				<th>Tipo</th>
				<th>Fecha inicio</th>
				<th>Fecha final</th>
				<th>Comentario</th>
				{{-- <th>Fecha en que regresó</th> --}}

				<th>Días mes actual <i class="fa fa-question-circle fa-fw" data-swal="Se calcula en base a los días ausentes en el mes actual. También toma en cuenta si la fecha de inicio sea antes o la fecha final sea posterior al mes actual, contabilizando solamente los días desde el inicio del mes hasta el día de la fecha."></i>
				</th>
				<th>Total días <i class="fa fa-question-circle fa-fw" data-swal="Se calcula desde la fecha de inicio hasta la fecha final"></i></th>

				<th>Hoy</th>

				<th></th>


			</tr>
		</thead>
		<!--Table head-->

		<!--Table body-->
		<tbody></tbody>
		<!--Table body-->
	</table>
</div>