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


			<div data-toggle="busqueda-filtros" class="row align-items-center">

				<input type="hidden" name="filtro" value="{{ Request::get('filtro') }}">


				<div class="col-lg-9 border-right">

					<div class="row">

						<div class="col-lg-4 form-group">
							<select name="especialidad" class="form-control form-control-sm">
								<option value="">--Seleccionar Especialidad--</option>
								<option value="2" {{Request::get('especialidad')==2 ? 'selected' : '' }}>Enfermero</option>
								<option value="1" {{Request::get('especialidad')==1 ? 'selected' : '' }}>Médico</option>
							</select>
						</div>

						<div class="col-lg-4 form-group">
							<select name="estado" class="form-control form-control-sm">
								<option value="">--Seleccionar Estado--</option>
								<option value="1" {{Request::get('estado')=='1' ? 'selected' : '' }} >Sólo Activos</option>
								<option value="0" {{Request::get('estado')=='0' ? 'selected' : '' }} >Sólo Inactivos</option>
							</select>
						</div>

						<div class="col-lg-4 form-group">
							<select name="fichada" class="form-control form-control-sm">
								<option value="">--Seleccionar Fichaje--</option>
								<option value="1" {{Request::get('fichada')=='1' ? 'selected' : '' }} >Fichada Activa</option>
								<option value="0" {{Request::get('fichada')=='0' ? 'selected' : '' }} >Sin Fichar</option>
							</select>
						</div>

						<div class="col-lg-4 form-group">
							<select name="rol" class="form-control form-control-sm">
								<option value="">--Seleccionar Rol--</option>
								@if($roles) @foreach($roles as $rol)
								<option value="{{$rol->id}}" {{Request::get('rol')==$rol->id ? 'selected' : '' }} >{{$rol->nombre}}</option>
								@endforeach @endif
							</select>
						</div>

						<div class="col-lg-4 form-group">
							<select name="grupo" class="form-control form-control-sm">
								<option value="">--Seleccionar Grupo--</option>
								@if($grupos) @foreach($grupos as $grupo)
								<option value="{{$grupo->id}}" {{Request::get('grupo')==$grupo->id ? 'selected' : '' }} >{{$grupo->nombre}}</option>
								@endforeach @endif
							</select>
						</div>

						<div class="col-lg-4 form-group">
							<select name="cliente" class="form-control form-control-sm">
								<option value="">--Seleccionar Cliente--</option>
								@if($clientes) @foreach($clientes as $cliente)
								<option value="{{$cliente->id}}" {{Request::get('cliente')==$cliente->id ? 'selected' : '' }} >{{$cliente->nombre}}</option>
								@endforeach @endif
							</select>
						</div>

					</div>
				</div>

				<div class="col-lg-3">
					<div class="form-group">
						<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro" ><i class="fas fa-search"></i> Buscar</button>
						<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-list"></i> Mostrar todo</button>
					</div>
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
						<th style="width:120px">Rol</th>
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
