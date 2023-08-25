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
				<a class="btn-ejornal btn-ejornal-base" href="{{route('nominas.create')}}"><i
						class="fas fa-plus-circle"></i> Nuevo trabajador</a>
				<a data-toggle="modal" data-target="#cargar_nominas_excel" class="btn-ejornal btn-ejornal-success"
					href="#"><i class="fas fa-file-excel"></i>Carga masiva</a>
			</div>
			@else
			<div class="small text-muted"><i>Debe fichar para poder agregar un nuevo trabajador</i></div>
			@endif
		</div>


		@include('mensajes_validacion')


		<div class="tarjeta">

			@include('modulos.nominas_tabla')
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>



<!-- Modal -->
<div class="modal fade" id="cargar_nominas_excel" tabindex="-1" aria-labelledby="nominas_excel_titulo"
	aria-hidden="true">
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
						<p class="card-text">Descarge este excel modelo. Completelo con los campos solicitados y subalo
							en el paso 2</p>
						<a class="btn-ejornal btn-ejornal-dark" href="{{asset('archivos/nominas_carga_masiva.csv')}}"
							download target="_blank"><i class="fa fa-fw fa-download"></i> Descargar excel</a>
					</div>
				</div>
				<div class="card mb-4">
					<h5 class="card-header">Paso 2 | Cargar excel de clientes</h5>
					<div class="card-body">

						<form action="{{action('EmpleadosNominasController@cargar_excel')}}"
							enctype="multipart/form-data" accept-charset="UTF-8" method="post">
							{{ csrf_field() }}

							<div class="input-group">
								<div class="custom-file">
									<input name="archivo" id="input_file" type="file" class="custom-file-input"
										accept="*.csv,*.xls,*.xlsx">
									<label for="input_file" class="custom-file-label">Subir Archivo</label>
								</div>
							</div>

							<hr>

							<h6>¿Si durante la carga encontramos trabajadores que ya fueron cargados que desea hacer?
							</h6>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="coincidencia"
									id="no_modificar_trabajador_radio" value="2" checked>
								<label class="form-check-label" for="no_modificar_trabajador_radio">No modificarlo.
									Dejar el que está actualmente cargado.</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="coincidencia"
									id="actualizar_trabajador_radio" value="1">
								<label class="form-check-label" for="actualizar_trabajador_radio">Actualizar con los
									datos del presente excel</label>
							</div>

							<hr>

							<h6>¿Borrar los trabajadores previamente guardados que no estén en el archivo excel?</h6>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="borrar" id="no_borrar_trabajador"
									value="0" checked>
								<label class="form-check-label" for="no_borrar_trabajador">No borrar. Dejar el que está
									actualmente cargado.</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="borrar" id="borrar_trabajador"
									value="1">
								<label class="form-check-label" for="borrar_trabajador">Si, borrarlo de la base de
									datos.</label>
							</div>


							<hr>


							<button type="button" class="btn-ejornal btn-ejornal-gris-claro" data-dismiss="modal"><i
									class="fa fa-fw fa-times"></i> Cerrar</button>

							<button type="submit" class="btn-ejornal btn-ejornal-success"><i
									class="fa fa-fw fa-save"></i> Comenzar Importación</button>


						</form>
					</div>
				</div>

			</div>

		</div>
	</div>
</div>


@endsection