@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Listado de comunicaciones</h2>
			<p>Aquí puede ver el listado de comunicaciones de la empresa</p>
			{{-- @if (auth()->user()->fichada == 1)
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-base" href="{{route('comunicaciones.create')}}"><i
						class="fas fa-plus-circle"></i> Nueva comunicación</a>
			</div>
			@endif --}}
		</div>

		@include('../mensajes_validacion')

		<div class="tarjeta">

			@php
			$export=true;
			@endphp

			{{-- BUSQUEDA --}}

			<div data-toggle="busqueda-fecha" class="row">

				<div class="col-md-6 d-flex flex-wrap align-items-center">

					<div class="row">

						<div class="col-lg-6">
							<input name="search" type="text" class="form-control form-control-sm" placeholder="Filtrar por nombre, dni, cuil o tel, user o descripción...">
						</div>
						<div class="col-lg-6">
							<select id="estado" name="estado" class="form-control form-control-sm">
								<option value="todos" selected>-Activos/Inactivos-</option>
								<option value="activos">Activos</option>
								<option value="inactivos">Inactivos</option>
							</select>
						</div>

						<div class="col-lg-6">
							<input placeholder="Desde" name="from" type="text" class="form-control form-control-sm" value="{{$fecha_inicio ?? ''}}">
						</div>

						<div class="col-lg-6">
							<input placeholder="Hasta" name="to" type="text" class="form-control form-control-sm" value="{{$fecha_final ?? ''}}">
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

				</div>

			</div>
			<hr>



			<table class="table table-striped table-hover table-sm tabla_comunicaciones_listado">

				<!--Table head-->
				<thead>
					<tr>
						<th>Trabajador</th>
						<th>Tipo</th>
						<th>Usuario que registró</th>
						<th>Cargado</th>
						<th>Archivos</th>
						<th>Estado</th>
						<th>Descripción</th>
						<th></th>
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