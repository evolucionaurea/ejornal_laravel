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
			@if (auth()->user()->fichada == 1 || !auth()->user()->fichar)
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-base" href="{{route('nominas.create')}}"><i
						class="fas fa-plus-circle fa-fw"></i> Nuevo trabajador</a>
				<a data-toggle="modal" data-target="#cargar_nominas_excel" class="btn-ejornal btn-ejornal-success"
					href="#"><i class="fas fa-file-excel fa-fw"></i>Carga masiva</a>

				<a href="{{route('/empleados/nominas/historial')}}" class="btn-ejornal btn-dark"><i
						class="fas fa-file-excel fa-fw"></i> Historial</a>
			</div>
			@else
			<div class="small text-muted"><i>Debe fichar para poder agregar un nuevo trabajador</i></div>
			@endif
		</div>


		@include('mensajes_validacion')

		@if (\Session::has('errores'))
		<div class="alert alert-danger alert-dismissible fade show mt-2 mr-4 ml-4" role="alert">
			<p>Se encontraron los siguientes errores al importar el archivo:</p>
			<ul class="list-group list-group-flush small">
				@foreach(\Session::get('errores') as $error)
				<li class="list-group-item py-1 px-0">Fila: {{ $error->fila }} | Columna: {{ $error->columna }} | Valor: {{ $error->valor }} | Error:
					{{ $error->error }}</li>
				@endforeach
			</ul>
		</div>
		@endif


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

						<div class="">
							<a class="btn-ejornal btn-ejornal-dark"
								href="{{asset('archivos/nominas_carga_masiva.csv')}}" download target="_blank"><i
									class="fa fa-fw fa-download"></i> Descargar excel</a>
						</div>
						<div class="small text-muted mt-3">Archivo actualizado al 30/01/2024</div>

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

							<h6 class="mb-0">Si durante la carga encontramos trabajadores que ya fueron cargados, ¿Qué deseas hacer?</h6>
							<div class="small text-muted font-italic mb-2">Se considerará una coincidencia cuando el <b>dni</b> y el <b>cuil</b> que figura en el archivo excel sea el mismo que el registro guardado en la base de datos.</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="coincidencia"
									id="no_modificar_trabajador_radio" value="2" checked>
								<label class="form-check-label" for="no_modificar_trabajador_radio">No modificarlo. Dejar el que está actualmente cargado.</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="coincidencia"
									id="actualizar_trabajador_radio" value="1">
								<label class="form-check-label" for="actualizar_trabajador_radio">Actualizar con los datos del presente excel</label>
							</div>

							<hr>

							<h6>¿Que hacer con los trabajadores previamente guardados que no estén en el archivo excel?</h6>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="borrar" id="no_borrar_trabajador"
									value="0" checked>
								<label class="form-check-label" for="no_borrar_trabajador">Dejar el que está actualmente cargado.</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="borrar" id="borrar_trabajador"
									value="1">
								<label class="form-check-label" for="borrar_trabajador">Marcarlos como inactivos</label>
							</div>


							<hr>
							<h6>Si el trabajador existe en la nómina de otra sucursal o empresa, ¿Qué hacer?</h6>
							<div class="form-check">
								<input type="radio" class="form-check-input" name="mover" id="no_mover_trabajador" value="0" checked>
								<label for="no_mover_trabajador" class="form-check-label">No transferir, dejarlo en la nómina de la empresa a la que pertenece.</label>
							</div>
							<div class="form-check">
								<input type="radio" class="form-check-input" name="mover" id="mover_trabajador" value="1">
								<label for="mover_trabajador" class="form-check-label">Transferirlo en la nómina de la empresa actual ({{ auth()->user()->cliente_actual ? auth()->user()->cliente_actual->nombre : '[Debes seleccionar un cliente]' }}).</label>
							</div>
							<hr>


							<button type="button" class="btn-ejornal btn-ejornal-gris-claro" data-dismiss="modal">
								<i class="fa fa-fw fa-times"></i> Cerrar
							</button>

							<button type="submit" class="btn-ejornal btn-ejornal-success">
								<i class="fa fa-fw fa-save"></i> Comenzar Importación
							</button>

						</form>
					</div>
				</div>

			</div>

		</div>
	</div>
</div>


@endsection