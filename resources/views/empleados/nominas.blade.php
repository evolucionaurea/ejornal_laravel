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


			<form action="{{action('EmpleadosNominasController@listado')}}" class="row" method="POST">

				@csrf

				<div  class="col-lg-2 form-group" >
					<label for="">Ausentes</label>
					<select name="ausentes" class="form-control form-control-sm" onchange="this.form.submit()">
						<option value="">--Seleccionar Filtro--</option>
						<option value="hoy" {{isset($filtros) && isset($filtros['ausentes']) && $filtros['ausentes']=='hoy' ? 'selected' : '' }}>Ausentes Hoy</option>
						<option value="covid" {{isset($filtros) && isset($filtros['ausentes']) && $filtros['ausentes']=='covid' ? 'selected' : '' }}>Ausentes COVID</option>
					</select>
				</div>


				<div class="col-lg-2 form-group">
					<label for="">Activos/Inactivos</label>
					<select name="estado" class="form-control form-control-sm" onchange="this.form.submit()">
						<option value="">--Seleccionar Filtro--</option>
						<option value="1" {{isset($filtros) && isset($filtros['estado']) && $filtros['estado']=='1' ? 'selected' : '' }} >Sólo Activos</option>
						<option value="0" {{isset($filtros) && isset($filtros['estado']) && $filtros['estado']=='0' ? 'selected' : '' }} >Sólo Inactivos</option>
					</select>
				</div>

			</form>
			<hr>



			<table class="table table-striped table-hover table-sm tabla_user">

				<!--Table head-->
				<thead>
					<tr>
						<th>
							Nombre
							<i class="fas fa-sort ml-1"></i>
						</th>
						<th>
							Email
							<i class="fas fa-sort ml-1"></i>
						</th>
						<th>
							Tel
							<i class="fas fa-sort ml-1"></i>
						</th>
						<th>
							DNI
							<i class="fas fa-sort ml-1"></i>
						</th>
						<th>
							Estado
							<i class="fas fa-sort ml-1"></i>
						</th>
						<th>
							Hoy
							<i class="fas fa-sort ml-1"></i>
						</th>
						@if (auth()->user()->fichada == 1)
						<th>
							Acciones
							<i class="fas fa-sort ml-1"></i>
						</th>
						@endif
					</tr>
				</thead>
				<!--Table head-->

				<!--Table body-->
				<tbody>
					@foreach ($trabajadores as $trabajador)
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
					@endforeach
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
					<h5 class="card-header">Paso 1</h5>
					<div class="card-body">
						<h5 class="card-title">Descargar modelo de excel</h5>
						<p class="card-text">Descarge este excel modelo. Completelo con los campos solicitados y subalo en el paso 2</p>
						<a class="btn-ejornal btn-ejornal-dark" href="{{asset('archivos/nominas_carga_masiva.csv')}}" download target="_blank">Descargar excel</a>
					</div>
				</div>
				<div class="card mb-4">
					<h5 class="card-header">Paso 2</h5>
					<div class="card-body">
						<h5 class="card-title">Cargar excel de clientes</h5>
						<form action="{{action('EmpleadosNominasController@cargar_excel')}}" enctype="multipart/form-data" accept-charset="UTF-8" method="post">
						{{ csrf_field() }}
						<div class="form-group mb-4">
							<input name="archivo" type="file" class="form-control-file form-control-sm">
						</div>
						<div class="row">
						  <div class="col-md-12">
							<h6>¿Si durante la carga encontramos trabajadores que ya fueron cargados que desea hacer?</h6>
							<div class="form-check">
							  <input class="form-check-input" type="radio" name="coincidencia" id="no_modificar_trabajador_radio" value="2" checked>
							  <label class="form-check-label" for="no_modificar_trabajador_radio">
								No modificarlo. Dejar el que está actualmente cargado.
							  </label>
							</div>
							<div class="form-check">
							  <input class="form-check-input" type="radio" name="coincidencia" id="actualizar_trabajador_radio" value="1">
							  <label class="form-check-label" for="actualizar_trabajador_radio">
								Actualizar con los datos del presente excel
							  </label>
							</div>
						  </div>
						</div>
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
