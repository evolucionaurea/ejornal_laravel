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
						<input name="search" class="form-control form-control-sm" placeholder="Buscar por trabajador, médico o institución..." value="{{ Request::get('search') }}"></input>
					</div>


					{{-- <div class="mb-1 col-md-6">
						<select name="tipo" class="form-control form-control-sm select_2">
							<option value="">--Todos los tipos--</option>
							@foreach ($tipos as $tipo)
							<option value="{{$tipo->id}}" {{ Request::get('tipo') == $tipo->id ? 'selected' : '' }} >{{$tipo->nombre}}</option>
							@endforeach
						</select>
					</div> --}}

					{{-- <div class="mb-1 col-md-6">
						<select name="ausentes" class="form-control form-control-sm">
							<option value="">--Seleccionar Filtro--</option>
							<option value="hoy" {{ Request::get('ausentes')  == 'hoy' ? 'selected' : '' }} >Ausentes Hoy</option>

							<option value="mes-actual" {{ Request::get('ausentes')  == 'mes-actual' ? 'selected' : '' }} >Ausentismos mes actual</option>
							<option value="mes-anterior" {{ Request::get('ausentes')  == 'mes-anterior' ? 'selected' : '' }} >Ausentismos mes anterior</option>
							<option value="mes-anio-anterior" {{ Request::get('ausentes')  == 'mes-anio-anterior' ? 'selected' : '' }} >Ausentismos mismo mes año anterior</option>
							<option value="anio-actual" {{ Request::get('ausentes')  == 'anio-actual' ? 'selected' : '' }} >Ausentismos año actual</option>
						</select>
					</div> --}}

					{{-- <div class="mb-1 col-md-6" >
						<select name="estado" class="form-control form-control-sm">
							<option value="">--Seleccionar Filtro--</option>
							<option value="activo" {{ Request::get('estado')  == 'activo' || Request::get('ausentes')  == 'hoy' ? 'selected' : '' }} >Trabajadores Activos</option>
							<option value="inactivo" {{ Request::get('estado')  == 'inactivo' ? 'selected' : '' }} >Trabajadores Inactivos</option>
						</select>
					</div> --}}

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



			<table class="table table-striped table-hover table-sm tabla_certificados_ausentismo_listado w-100">

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
