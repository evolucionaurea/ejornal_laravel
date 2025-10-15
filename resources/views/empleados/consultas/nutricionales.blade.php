@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Nutricionales</h2>
			<p>Aquí puede ver el listado de consultas nutricionales de la empresa.</p>
			@if ((auth()->user()->fichada == 1 || !auth()->user()->fichar))

			@if(auth()->user()->id_especialidad == 1)
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-base" href="{{route('empleados.consultas.nutricionales.create')}}">
					<i class="fas fa-plus-circle"></i>
					Nueva Consulta Nutricional
				</a>
			</div>
			@else 
			<div class="small text-muted"><i>Tu usuario no tiene permiso para crear consultas nutricionales</i></div> 
			@endif 

			@else
			<div class="small text-muted"><i>[Debe fichar para crear una nueva consulta nutricional]</i></div>
			@endif
		</div>



		@include('../mensajes_validacion')

		<div class="tarjeta">
			
			@include('../../modulos/busqueda_consultas')

			<table data-table="consultas-nutricionales" class="table table-striped table-sm tabla" >
				<thead>
					<tr>
						<th>ID</th>
						<th>Trabajador</th>
						<th>Legajo</th>
						<th>Tipo</th>
						<th>Fecha atención</th>
						<th>Cargado por</th>
						<th class="text-right">Acciones</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>

			
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>



@endsection