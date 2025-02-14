@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Edición del estudio médico complementario</h2>
			<p>Aquí podrá editar el estudio médico complementario.</p>
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



		<form action="{{action('EmpleadosPreocupacionalesController@store')}}" accept-charset="UTF-8" method="post"
			enctype="multipart/form-data">

			{{ csrf_field() }}

			<input type="hidden" class="form-control" name="id" value="{{ $preocupacional->id }}">
			<input type="hidden" class="form-control" name="trabajador" value="{{ $preocupacional->id_nomina }}">


			{{-- MAIN --}}
			<div class="tarjeta">

				<div class="form-row">

					<div class="form-group col-lg-3">
						<label for="">
							<span>Tipo de Estudio</span>

							<a data-toggle="modal" data-target="#tipoPreocupacional" href="">
								<i class="fas fa-eye"></i>
							</a>
							<a data-toggle="modal" data-target="#tipoPreocupacionalAdd" href="">
								<i class="fas fa-plus-circle"></i>
							</a>
							<small style="color: red;">*</small>
						</label>
						<select name="tipo_estudio_id" class="form-control" required>
							<option value="">--Seleccionar--</option>
							@foreach($tipos as $tipo)
							<option value="{{ $tipo->id }}" {{ $tipo->id==$preocupacional->tipo_estudio_id ? 'selected'
								: '' }} >{{ $tipo->name }}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group col-lg-3 col-md-4 col-12">
						<label>Trabajador<small style="color: red;">*</small></label>
						<input disabled type="text" class="form-control" value="{{$preocupacional->trabajador->nombre}}"
							placeholder="">
					</div>

					<div class="form-group col-lg-3 col-md-4 col-12">
						<label>Fecha <small style="color: red;">*</small></label>
						<input id="ausentismo_fecha_inicio" name="fecha" type="datetime" class="form-control"
							value="{{ !empty($preocupacional->fecha) ? date('d/m/Y',strtotime($preocupacional->fecha)) : "" }}">
					</div>

					<div class="form-group col-lg-3 col-md-4 col-12">
						<label>Resultado</label>
						<input name="resultado" type="datetime" class="form-control"
							value="{{ $preocupacional->resultado }}">
					</div>

				</div>
			</div>


			{{-- VENCIMIENTO --}}
			<div class="tarjeta">

				<h4>Vencimiento</h4>

				<div class="row">

					<div class="form-group col-lg-3">
						<label for="">¿Tiene Vencimiento? <small style="color: red;">*</small></label>
						<select name="tiene_vencimiento" class="form-control" required>
							<option value="0" {{ !$preocupacional->fecha_vencimiento ? 'selected' : '' }} >No</option>
							<option value="1" {{ $preocupacional->fecha_vencimiento ? 'selected' : '' }} >Si</option>
						</select>
					</div>
					<div class="form-group col-lg-3" data-toggle="vencimiento" {{ !is_null($preocupacional->fecha_vencimiento) ? '' : 'd-none' }}>
						<label for="">Fecha de Vencimiento</label>
						<input name="fecha_vencimiento" type="text" class="form-control"
							value="{{ $preocupacional->fecha_vencimiento ? $preocupacional->fecha_vencimiento->format('d/m/Y') : '' }}">
					</div>

					<div
						class="col-lg-6 p-4 {{ !is_null($preocupacional->fecha_vencimiento) ? '' : 'd-none' }}">
						<div class="row">
							{{-- <div class="form-group col-lg-6">
								<label for="">Completado</label>
								<select name="completado" class="form-control">
									<option value="0" {{ $preocupacional->completado===0 ? 'selected' : '' }}>No
									</option>
									<option value="1" {{ $preocupacional->completado===1 ? 'selected' : '' }}>Si
									</option>
								</select>
							</div> --}}
						</div>
						{{-- <div class="form-group">
							<label for="">Comentarios</label>
							<textarea name="completado_comentarios" rows="5" class="form-control" {{ !$preocupacional->completado ? '' : 'disabled' }}>{{ $preocupacional->completado_comentarios }}</textarea>
						</div> --}}
					</div>

				</div>

			</div>


			{{-- ARCHIVOS --}}
			<div class="tarjeta">
				<label>Documentación / Observación</label>

				<div class="row">

					<div class="form-group col-lg-6">
						<div class="table-responsive">
							<table data-table="archivos" class="table table-sm small w-100 table-bordered border">
								<thead>
									<tr class="bg-light">
										<th colspan="2">
											<label for="" class="mb-0">Adjuntar archivos</label>
											<span class="small text-muted font-italic">Puedes adjuntar más de 1
												archivo</span>
										</th>
									</tr>
								</thead>
								<tbody>
									@if($preocupacional->archivos) @foreach($preocupacional->archivos as $archivo)
									<tr>
										<td colspan="2">
											<a href="{{ $archivo->file_path }}" target="_blank" class="text-info">{{
												$archivo->archivo }}</a>
										</td>
									</tr>
									@endforeach @endif
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
							required>{{ $preocupacional->observaciones }}</textarea>
					</div>

				</div>
			</div>

			<div class="text-center m-5">
				<button class="btn-ejornal btn-ejornal-base btn-ejornal-lg" type="submit" name="button">Guardar
					cambios</button>
			</div>

		</form>
	</div>

	{{-- Contenido de la pagina --}}
</div>
</div>


@include('../modulos/modales_crud_tipos_preocupacional')

@endsection