@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Certificados de un ausentismo</h2>
			<p>Aquí puede ver el listado de las documentaciones que carga en cada ausentismo</p>
		</div>

		 @include('../mensajes_validacion')

		<div class="tarjeta">

			{{-- BUSCADOR --}}
			<div data-toggle="busqueda-fecha" class="d-flex flex-wrap">

				<input type="hidden" name="filtro" value="{{ Request::get('filtro') }}" >


				<div class="d-flex flex-wrap col-md-8 align-items-center">

					<div class="mb-1 col-md-6">
						<input name="search" class="form-control form-control-sm" placeholder="Buscar por trabajador. dni, cuil, médico o institución..." value="{{ Request::get('search') }}"></input>
					</div>

					<div class="mb-1 col-md-3" >
						<input placeholder="Desde" name="from" type="text" class="form-control form-control-sm"
							value="{{Request::get('from')}}">
					</div>

					<div class="mb-1 col-md-3" >
						<input placeholder="Hasta" name="to" type="text" class="form-control form-control-sm"
							value="{{Request::get('to')}}">
					</div>

				</div>

				<div class="d-flex col-md-4 border-left align-items-center">

					<div class="mr-2 mb-2">
						<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro"><i class="fas fa-search"></i> Buscar</button>
					</div>
					<div class="mr-2 mb-2">
						<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-list"></i> Mostrar todo</button>
					</div>

					<div class="mr-2 mb-2" >
						<button data-toggle="export" data-href="{{url(Route::currentRouteName().'/exportar')}}" class="btn-ejornal btn-info" ><i class="fas fa-file-excel"></i> Exportar</button>
					</div>

				</div>


			</div>
			<hr>



			<table data-table="certificados" class="table table-striped table-hover table-sm w-100">

				<!--Table head-->
				<thead>
					<tr>
						<th>Trabajador</th>
						<th>Médico</th>
						<th>Institución</th>
						<th>Fecha inicio</th>
						<th>Fecha final</th>
						<th>Fecha en que regresó</th>
						<th>Matricula nacional</th>
						<th>Archivos</th>
						<th>Acciones</th>
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
