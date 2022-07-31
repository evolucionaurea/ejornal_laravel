@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_admin')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Listado de usuarios</h2>
			<p>Aquí puede ver el listado de los usuarios del sistema</p>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-base" href="{{route('users.create')}}"><i class="fas fa-plus-circle"></i> Nuevo usuario</a>
			</div>
		</div>

		@include('../mensajes_validacion')

		@if (!empty($users_sin_empresas))

		<div id="accordion" class="p-4">
			<div class="card">
				<div class="card-header text-danger" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
					<h5 class="mb-0 d-flex justify-content-between align-items-center">
						<p>Hay {{count($users_sin_empresas)}} empleados sin empresas asignadas</p>
						<i class="fas fa-angle-down"></i>
					</h5>
				</div>

				<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
					<div class="card-body">
						@foreach ($users_sin_empresas as $value)
						<ul class="list-group list-group-flush">
							<li class="list-group-item">
								{{$value->nombre}} {{$value->email}}
								<a title="Editar" href="{{route('users.edit', $value->id)}}">
									<i class="fas fa-pen"></i>
								</a>
							</li>
						</ul>
						@endforeach
					</div>
				</div>
			</div>
		</div>

		@endif

		<div class="tarjeta">


			<div data-toggle="busqueda-filtros" class="d-flex row">

				<div class="col-lg-12 d-flex align-items-center">

					<input type="hidden" name="filtro" value="{{ Request::get('filtro') }}">

					<select name="especialidad" class="form-control form-control-sm w-25 mr-2">
						<option value="">--Seleccionar Especialidad--</option>
						<option value="2" {{Request::get('especialidad')==2 ? 'selected' : '' }}>Enfermero</option>
						<option value="1" {{Request::get('especialidad')==1 ? 'selected' : '' }}>Médico</option>
					</select>

					<select name="estado" class="form-control form-control-sm w-25 mr-2">
						<option value="">--Seleccionar Estado--</option>
						<option value="1" {{Request::get('estado')=='1' ? 'selected' : '' }} >Sólo Activos</option>
						<option value="0" {{Request::get('estado')=='0' ? 'selected' : '' }} >Sólo Inactivos</option>
					</select>

					<select name="fichada" class="form-control form-control-sm w-25 mr-2">
						<option value="">--Seleccionar Fichaje--</option>
						<option value="1" {{Request::get('fichada')=='1' ? 'selected' : '' }} >Fichada Activa</option>
						<option value="0" {{Request::get('fichada')=='0' ? 'selected' : '' }} >Sin Fichar</option>
					</select>

					<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro" ><i class="fas fa-search"></i> Buscar</button>
					<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-list"></i> Mostrar todo</button>

				</div>
			</div>

			<hr>



			<table class="table table-striped table-hover table-sm tabla">

				<!--Table head-->
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Email</th>
						<th>Especialidad</th>
						<th>Estado</th>
						<th>Rol</th>
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
