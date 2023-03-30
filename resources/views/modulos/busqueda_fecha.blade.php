<div data-toggle="busqueda-fecha" class="d-flex row">
	<div class="col-md-10 col-lg-7 d-flex">
		<input placeholder="Desde" name="from" type="datetime" class="form-control form-control-sm mr-2 w-25 p-3" value="{{$fecha_inicio ?? ''}}">
		<input placeholder="Hasta" name="to" type="datetime" class="form-control form-control-sm mr-2 w-25 p-3" value="{{$fecha_final ?? ''}}">

		<input type="hidden" name="filtro" value="{{ Request::get('filtro') }}">

		<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro" ><i class="fas fa-search"></i> Buscar</button>
		<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-list"></i> Mostrar todo</button>


		@if(isset($export))

		<button data-toggle="export" data-href="{{url(Route::currentRouteName().'/exportar')}}" target="_blank" class="btn-ejornal btn-info" ><i class="fas fa-file-excel"></i> Exportar</button>

		@endif


	</div>
</div>
<hr>