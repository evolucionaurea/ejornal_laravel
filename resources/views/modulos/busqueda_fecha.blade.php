<div data-toggle="busqueda-fecha" class="row">

	<input type="hidden" name="filtro" value="{{ Request::get('filtro') }}">

	<div class="col-md-6 d-flex flex-wrap align-items-center">

		<div class="row">

			<div class="col-lg-4">
				<input placeholder="Desde" name="from" type="text" class="form-control form-control-sm"
					value="{{$fecha_inicio ?? ''}}">
			</div>
			<div class="col-lg-4">
				<input placeholder="Hasta" name="to" type="text" class="form-control form-control-sm"
					value="{{$fecha_final ?? ''}}">
			</div>

			<div class="col-lg-4">
				<select id="estado" name="estado" class="form-control form-control-sm">
					<option value="todos" selected>-Activos/Inactivos-</option>
					<option value="activos">Activos</option>
					<option value="inactivos">Inactivos</option>
				</select>


			</div>

		</div>
	</div>


	<div class="col-lg-4 border-left align-items-center d-flex flex-wrap">

		<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro mb-2"><i class="fas fa-search"></i>
			Buscar</button>
		<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro mb-2" href="#!"><i
				class="fas fa-list"></i> Mostrar todo</button>

		@if(isset($export))
		<button data-toggle="export" data-href="{{ url('admin/reportes_fichadas_nuevas/exportar') }}" type="button"
			class="btn-ejornal btn-info"><i class="fas fa-file-excel"></i> Exportar</button>
		@endif

	</div>

</div>
<hr>