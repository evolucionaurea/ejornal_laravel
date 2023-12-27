@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">

	@include('partials.sidebar_empleados')

	<div id="page-content-wrapper">

		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Edición de un ausentismo</h2>
			<p>Aquí podrá editar la información referente al ausentismo de un trabajador</p>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-gris-claro"
					href="{{url('empleados/ausentismos')}}?{{$_SERVER['QUERY_STRING']}}"><i
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

		<div class="tarjeta">
			<form
				action="{{ action('EmpleadosAusentismosController@update', $ausentismo->id) }}?{{ $_SERVER['QUERY_STRING'] }}"
				accept-charset="UTF-8" method="post">
				{{ csrf_field() }}
				<input name="_method" type="hidden" value="PUT">
				<div class="row">
					<div class="form-group col-md-3">
						<label>Trabajador</label>
						<input disabled name="nombre" type="text" class="form-control"
							value="{{$ausentismo->trabajador->nombre}}" placeholder="">
					</div>
					<div class="form-group col-md-3">
						<label>Tipo</label>
						@if($ausentismo_tipos)
						<select name="tipo" class="form-control" required>
							@foreach($ausentismo_tipos as $tipo)
							<option value="{{$tipo->id}}" {{ $ausentismo->tipo->id==$tipo->id ? 'selected' : '' }}
								>{{$tipo->nombre}}</option>
							@endforeach
						</select>
						@endif
					</div>
					<div class="form-group col-md-2">
						<label>Fecha inicio</label>
						<input name="fecha_inicio" type="text" class="form-control" required
							value="{{ (!empty($ausentismo->fecha_inicio)) ? date('d/m/Y',strtotime($ausentismo->fecha_inicio)) : "" }}">
					</div>
					<div class="form-group col-md-2">
						<label>Fecha final</label>
						<input name="fecha_final" type="text" class="form-control" required
							value="{{ (!empty($ausentismo->fecha_final)) ? date('d/m/Y',strtotime($ausentismo->fecha_final)) : "" }}">
					</div>
					<div class="form-group col-md-2">
						<label>Fecha en que regresó</label>
						<input style="background-color: #f0f0f0; pointer-events: none;" name="fecha_regreso_trabajar"
							type="text" class="form-control"
							value="{{ (!empty($ausentismo->fecha_regreso_trabajar)) ? date('d/m/Y',strtotime($ausentismo->fecha_regreso_trabajar)) : "" }}">
					</div>

					<div class="form-group col-md-12">
						<label>Comentario</label>
						<textarea name="comentario" class="form-control"
							rows="3">{{ $ausentismo->comentario }}</textarea>
					</div>
				</div>


				<hr>
				<h4>Comunicación</h4>
				<div class="form-row">
					<div class="form-group col-md-3">
						<label>
							Tipo
							{{-- <a style="color: #6f9eab; margin-right: 10px;" data-toggle="modal"
								data-target="#crear_tipo_comunicacion" href="#">
								<i class="fas fa-plus-circle"></i>
							</a>
							<a style="color: #6f9eab;" data-toggle="modal" data-target="#ver_tipo_comunicacion"
								href="#">
								<i class="fas fa-eye"></i>
							</a> --}}
						</label>
						<select required name="tipo_comunicacion" class="form-control">
							<option value="">--Seleccionar--</option>
							@foreach ($tipo_comunicaciones as $tipo_com)
							<option value="{{$tipo_com->id}}" {{ $ausentismo->comunicacion->id_tipo==$tipo_com->id ?
								'selected' :
								'' }} >{{$tipo_com->nombre}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-md-9">
						<label>Descripción</label>
						<textarea required name="descripcion" class="form-control"
							rows="3">{{ $ausentismo->comunicacion->descripcion }}</textarea>
					</div>
				</div>

				<div class="form-group">
					<button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Guardar cambios</button>
				</div>
			</form>
		</div>

		{{-- Contenido de la pagina --}}

	</div>
</div>

@endsection