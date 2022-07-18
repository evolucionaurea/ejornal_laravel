@extends('partials.layout')

@section('title', 'Clientes')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_clientes')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Listado de ausentismos</h2>
			<p>Aquí puede ver el listado de ausentismos de la empresa</p>
		</div>

		 @include('../mensajes_validacion')

		<div class="tarjeta">


			<div data-toggle="busqueda-fecha" class="row align-items-center">

				<input type="hidden" name="filtro" value="{{ Request::get('filtro') }}">

				<div class="col-lg-3 form-group">
					<select name="tipo" class="form-control form-control-sm select_2">
						<option value="">--Todos los tipos--</option>
						@foreach ($tipos as $tipo)
						<option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
						@endforeach
					</select>
				</div>


				<div class="col-lg-2 form-group">
					<input placeholder="Desde" name="from" type="datetime" class="form-control form-control-sm" value="{{$fecha_inicio ?? ''}}">
				</div>

				<div class="col-lg-2 form-group">
					<input placeholder="Hasta" name="to" type="datetime" class="form-control form-control-sm" value="{{$fecha_final ?? ''}}">
				</div>


				<div class="col-lg-3 form-group">
					<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro" ><i class="fas fa-search"></i> Buscar</button>
					<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-list"></i> Mostrar todo</button>
				</div>

			</div>
			<hr>


			<table class="table table-striped table-hover table-sm tabla_ausentismos">

				<!--Table head-->
				<thead>
					<tr>
						<th class="th-lg">Trabajador</th>
						<th class="th-lg">Tipo</th>
						<th class="th-lg">Fecha inicio</th>
						<th class="th-lg">Fecha final</th>
						<th class="th-lg">Fecha en que regresó</th>
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


@endsection
