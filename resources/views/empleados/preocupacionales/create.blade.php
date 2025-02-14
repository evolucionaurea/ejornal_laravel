@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Creación de estudios médicos complementarios</h2>
			<p>Aquí puedes cargar a los estudios médicos complementarios de la empresa.</p>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-gris-claro" href="{{url('empleados/preocupacionales')}}"><i
						class="fas fa-arrow-alt-circle-left"></i>Volver</a>
			</div>
		</div>

		@include('../../mensajes_validacion')
		@if ($errors->any())
		@foreach ($errors->all() as $error)
		<div class="alert alert-danger alert-dismissible fade show mr-4 ml-4" role="alert">
			{{$error}}
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		@endforeach
		@endif


		<form action="{{action('EmpleadosPreocupacionalesController@store')}}" accept-charset="UTF-8" method="post" enctype="multipart/form-data">

			@csrf

			<div class="tarjeta">

				<h4>Estudio Complementario</h4>

				<div class="row">

					<div class="form-group col-lg-3">
						<label for="">
							Tipo de Estudio
							<a data-toggle="modal" data-target="#tipoPreocupacional" href="">
								<i class="fas fa-eye"></i>
							</a>
							<a data-toggle="modal" data-target="#tipoPreocupacionalAdd" href="">
								<i class="fas fa-plus-circle"></i>
							</a>
							<small style="color: red;">*</small>
						</label>
						<select name="tipo_estudio_id" class="form-control" required>
							@foreach($tipos as $tipo)
							<option value="{{ $tipo->id }}" {{ $tipo->id==old('tipo_estudio_id') ? 'selected' : '' }}
								>{{ $tipo->name }}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group col-lg-3">
						<label>Trabajador <small style="color: red;">*</small></label>
						<select data-toggle="select2" name="trabajador" class="form-control form-control-sm select_2"
							required>
							<option value="">--Seleccionar--</option>
							@foreach ($trabajadores as $trabajador)
							<option value="{{$trabajador->id}}" {{ $trabajador->id==old('trabajador') ? 'selected' : ''
								}} >{{$trabajador->nombre}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group col-lg-3">
						<label>Fecha <small style="color: red;">*</small></label>
						<input name="fecha" type="text" class="form-control" value="{{ old(" fecha") }}" required>
					</div>

					<div class="form-group col-lg-3">
						<label for="">Resultado</label>
						<input name="resultado" type="text" class="form-control" value="{{ old('resultado') }}">
					</div>

				</div>


			</div>

			<div class="tarjeta">

				<h4>Vencimiento</h4>

				<div class="row">

					<div class="form-group col-lg-3">
						<label for="">¿Tiene Vencimiento? <small style="color: red;">*</small></label>
						<select name="tiene_vencimiento" class="form-control" required>
							<option value="0" {{ old('tiene_vencimiento')==='0' ? 'selected' : '' }}>No</option>
							<option value="1" {{ old('tiene_vencimiento')==='1' ? 'selected' : '' }}>Si</option>
						</select>
					</div>
					<div  data-toggle="vencimiento" class="form-group col-lg-3 {{ old('tiene_vencimiento')==='1' ? '' : 'd-none' }}">
						<label for="">Fecha de Vencimiento</label>
						<input name="fecha_vencimiento" type="text" class="form-control" value="{{ old('fecha_vencimiento') }}">
					</div>

					<div data-toggle="vencimiento" class="col-lg-3 p-4 {{ old('tiene_vencimiento')==='1' ? '' : 'd-none' }}">
						<div class="row">
							{{-- <div class="form-group col-lg-6">
								<label for="">Completado</label>
								<select name="completado" class="form-control">
									<option value="0" {{ old('completado')==='0' ? 'selected' : '' }}>No</option>
									<option value="1" {{ old('completado')==='1' ? 'selected' : '' }}>Si</option>
								</select>
							</div> --}}
						</div>
						{{-- <div class="form-group">
							<label for="">Comentarios</label>
							<textarea name="completado_comentarios" rows="5" class="form-control" >{{ old("completado_comentarios") }}</textarea>
						</div> --}}
					</div>

				</div>

			</div>

			<div class="tarjeta">
				<h4>Documentación / Observaciones</h4>

				<div class="row">

					<div class="form-group col-lg-6">
						<div class="table-responsive">
							<table data-table="archivos" class="table table-sm small w-100 table-bordered border">
								<thead>
									<tr class="bg-light">
										<th colspan="2">
											<label for="" class="mb-0">Adjuntar archivos</label>
											<span class="small text-muted font-italic">Puedes adjuntar más de 1 archivo</span>
										</th>
									</tr>
								</thead>
								<tbody>
									{{-- @include('templates.tr-certificado-ausentismo') --}}
								</tbody>
								<tfoot>
									<tr class="bg-light">
										<th colspan="2">
											<button data-toggle="agregar-archivo"
												class="btn btn-tiny btn-dark text-light" type="button">
												<i class="fal fa-plus fa-fw"></i> <span>Agregar archivo</span>
											</button>
										</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>


					<div class="form-group col-lg-6">
						<label>Observaciones <small style="color: red;">*</small></label>
						<textarea name="observaciones" class="form-control" rows="6"
							required>{{ old("observaciones") }}</textarea>
					</div>

				</div>
			</div>

			<div class="text-center m-5">
				<button class="btn-ejornal btn-ejornal-success btn-ejornal-lg" type="submit" name="button">Crear preocupacional</button>
			</div>
		</form>



		{{-- Contenido de la pagina --}}
	</div>
</div>


@include('../modulos/modales_crud_tipos_preocupacional')

@endsection