@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">

		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Listado de trabajadores</h2>
			<p>Aquí puede ver el listado de trabajadores de la empresa</p>
			@if (auth()->user()->fichada == 1)
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-base" href="{{route('nominas.create')}}"><i class="fas fa-plus-circle"></i> Nuevo trabajador</a>
				<a data-toggle="modal" data-target="#cargar_nominas_excel" class="btn-ejornal btn-ejornal-success" href="#"><i class="fas fa-file-excel"></i>Carga masiva</a>
			</div>
			@endif
		</div>


		@include('../mensajes_validacion')


		<div class="tarjeta">

			<div data-toggle="busqueda-filtros" class="d-flex row">

				<div class="col-md-10 col-lg-7 d-flex">


					<input type="hidden" name="filtro" value="{{ Request::get('filtro') }}">

					<select name="ausentes" class="form-control form-control-sm w-25 mr-2">
						<option value="">--Seleccionar Filtro--</option>
						<option value="hoy" {{Request::get('ausentes')=='hoy' ? 'selected' : '' }}>Ausentes Hoy</option>
						<option value="covid" {{Request::get('ausentes')=='covid' ? 'selected' : '' }}>Ausentes Hoy COVID</option>
						<option value="accidente" {{Request::get('ausentes')=='accidente' ? 'selected' : '' }}>Ausentes Hoy Accidente</option>
						<option value="incidente" {{Request::get('ausentes')=='incidente' ? 'selected' : '' }}>Ausentes Hoy Incidente</option>
					</select>

					<select name="estado" class="form-control form-control-sm w-25 mr-2">
						<option value="">--Seleccionar Filtro--</option>
						<option value="1" {{Request::get('estado')=='1' ? 'selected' : '' }} >Sólo Activos</option>
						<option value="0" {{Request::get('estado')=='0' ? 'selected' : '' }} >Sólo Inactivos</option>
					</select>

					<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro" ><i class="fas fa-search"></i> Buscar</button>
					<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-list"></i> Mostrar todo</button>


				</div>
			</div>

			<hr>


			<table class="table table-striped table-hover table-sm tabla_user dt-responsive">

				<!--Table head-->
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Email</th>
						<th>Tel</th>
						<th>DNI</th>
						<th>Estado</th>
						<th>Sector</th>
						<th>Hoy</th>

						<th>
							<div>Acciones</div>
							@if (auth()->user()->fichada != 1)
							<div class="small text-muted font-italic">debes fichar para ver las acciones</div>
							@endif
						</th>

					</tr>
				</thead>
				<!--Table head-->

				<!--Table body-->
				<tbody>

				</tbody>
				<!--Table body-->
			</table>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>





<!-- Modal -->
<div class="modal fade" id="cargar_nominas_excel" tabindex="-1" aria-labelledby="nominas_excel_titulo" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="nominas_excel_titulo">Carga masiva</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="card mb-4">
					<h5 class="card-header">Paso 1 | Descargar modelo de excel</h5>
					<div class="card-body">
						<p class="card-text">Descarge este excel modelo. Completelo con los campos solicitados y subalo en el paso 2</p>
						<a class="btn-ejornal btn-ejornal-dark" href="{{asset('archivos/nominas_carga_masiva.csv')}}" download target="_blank">Descargar excel</a>
					</div>
				</div>
				<div class="card mb-4">
					<h5 class="card-header">Paso 2 | Cargar excel de clientes</h5>
					<div class="card-body">

						<form action="{{action('EmpleadosNominasController@cargar_excel')}}" enctype="multipart/form-data" accept-charset="UTF-8" method="post">
						{{ csrf_field() }}
							<div class="form-group mb-4">
								<input name="archivo" type="file" class="form-control-file form-control-sm">
							</div>

							<hr>

							<h6>¿Si durante la carga encontramos trabajadores que ya fueron cargados que desea hacer?</h6>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="coincidencia" id="no_modificar_trabajador_radio" value="2" checked>
								<label class="form-check-label" for="no_modificar_trabajador_radio">No modificarlo. Dejar el que está actualmente cargado.</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="coincidencia" id="actualizar_trabajador_radio" value="1">
								<label class="form-check-label" for="actualizar_trabajador_radio">Actualizar con los datos del presente excel</label>
							</div>

							<hr>

							<h6>¿Borrar los trabajadores previamente guardados que no estén en el archivo excel?</h6>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="borrar" id="no_borrar_trabajador" value="0" checked>
								<label class="form-check-label" for="no_borrar_trabajador">No borrar. Dejar el que está actualmente cargado.</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="borrar" id="borrar_trabajador" value="1">
								<label class="form-check-label" for="borrar_trabajador">Si, borrarlo de la base de datos.</label>
							</div>


							<hr>


							<button type="submit" class="btn-ejornal btn-ejornal-success">Subir excel</button>
							<button type="button" class="btn-ejornal btn-ejornal-gris-claro" data-dismiss="modal">Cerrar</button>
						</form>
					</div>
				</div>

			</div>

		</div>
	</div>
</div>



@endsection


{{-- @foreach ($trabajadores as $trabajador)
					<tr>
						<td class="align-middle d-flex align-items-center">
							<div class="foto-user-tabla {{ isset($trabajador->foto) && !empty($trabajador->foto) ? 'has-image' : '' }}" style="background-image:url({{ isset($trabajador->foto) && !empty($trabajador->foto) ? asset('storage/nominas/fotos/'.$trabajador->id.'/'.$trabajador->hash_foto) : ''  }})"></div>
							{{$trabajador->nombre}}
						</td>
						<td class="align-middle">{{$trabajador->email}}</td>
						<td class="align-middle">{{$trabajador->telefono}}</td>
						<td class="align-middle">{{$trabajador->dni}}</td>
						<td class="align-middle">
							@if ($trabajador->estado == 1)
							<span class="tag_ejornal tag_ejornal_success">Activo</span>
							@else
							<span class="tag_ejornal tag_ejornal_danger">Inactivo</span>
							@endif
						</td>
						<td class="align-middle">{{$trabajador->sector}}</td>
						<td class="align-middle">
							<h3>

								@php
									if(isset($trabajador->hoy['estado']) && !empty($trabajador->hoy['estado']) && $trabajador->hoy['estado'] == 'Ausente'):
								@endphp
								<a href="{{route('ausentismos.edit',$trabajador->hoy['id'])}}" title="Click para editar ausentismo">
									<span class="tag_ejornal tag_ejornal_danger">Ausente</span>
								</a>


								<div class="small mt-3 text-danger">{{$trabajador->hoy['tipo']}}</div>


								@php endif; @endphp
							</h3>
						</td>

						@if (auth()->user()->fichada == 1)
						<td class="align-middle" scope="row">

							<div class="acciones_tabla">

								<a title="Historial" href="{{route('nominas.show', $trabajador->id)}}">
									<i class="fas fa-book"></i>
								</a>

								<a title="Editar" href="{{route('nominas.edit', $trabajador->id)}}">
									<i class="fas fa-pen"></i>
								</a>

								<form class="" action="{{route('nominas.destroy', $trabajador->id)}}" method="post">
									{{ csrf_field() }}
									<input type="hidden" name="_method" value="DELETE">
									<button title="Eliminar" type="submit">
										<i class="fas fa-trash"></i>
									</button>
								</form>

							</div>

						</td>
						@endif

					</tr>
					@endforeach --}}