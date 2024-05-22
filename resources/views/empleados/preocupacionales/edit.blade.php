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
						<a class="btn-ejornal btn-ejornal-gris-claro" href="{{url('empleados/preocupacionales')}}"><i class="fas fa-arrow-alt-circle-left"></i>Volver</a>
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
			<form action="{{action('EmpleadosPreocupacionalesController@update', $preocupacional->id)}}" accept-charset="UTF-8" method="post">
				{{ csrf_field() }}
				<input name="_method" type="hidden" value="PUT">

				<div class="form-row">

					<div class="form-group col-lg-3 col-md-4 col-12">
						<label>Trabajador</label>
						<input disabled name="trabajador" type="text" class="form-control" value="{{$preocupacional->trabajador->nombre}}" placeholder="">
					</div>

					<div class="form-group col-lg-2 col-md-4 col-12">
						<label>Fecha</label>
						<input id="ausentismo_fecha_inicio" name="fecha" type="datetime" class="form-control"
						value="{{ !empty($preocupacional->fecha) ? date('d/m/Y',strtotime($preocupacional->fecha)) : "" }}">
					</div>

					<div class="form-group col-lg-2 col-md-4 col-12">
						<label for="">¿Tiene Vencimiento?</label>
						<select name="tiene_vencimiento" class="form-control" required>
							<option value="0" {{ !$preocupacional->fecha_vencimiento ? 'selected' : '' }} >No</option>
							<option value="1" {{ $preocupacional->fecha_vencimiento ? 'selected' : '' }} >Si</option>
						</select>
					</div>

					<div data-toggle="vencimiento" class="form-group col-lg-2 col-md-4 col-12 {{ !$preocupacional->fecha_vencimiento ? 'd-none' : ''}}">
						<label for="">Vencimiento</label>
						<input name="fecha_vencimiento" type="text" class="form-control" value="{{ $preocupacional->fecha_vencimiento ? $preocupacional->fecha_vencimiento->format('d/m/Y') : '' }}" >
					</div>
					<div data-toggle="vencimiento" class="form-group col-lg-2 col-md-4 col-12 {{ !$preocupacional->fecha_vencimiento ? 'd-none' : ''}}">
						<label for="">Completado</label>
						<select name="completado" class="form-control">
							<option value="0" {{ !$preocupacional->completado ? 'selected' : '' }} >No</option>
							<option value="1" {{ $preocupacional->completado ? 'selected'  : ''}} >Si</option>
						</select>
					</div>

					<div class="form-group col-lg-12">
						<label>Observaciones</label>
						<textarea name="observaciones" class="form-control" rows="6">{{$preocupacional->observaciones}}</textarea>
					</div>

				</div>
				<hr>


				<label>Documentación</label>
				<div class="form-group">
					<a class="btn btn-info btn-tiny" href="{{route('preocupacionales.archivo', $preocupacional->id)}}" target="_blank">
						<i class="fa fa-download fa-fw"></i> <span>{{$preocupacional->archivo}}</span>
					</a>
				</div>

				<hr>
				<button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Guardar cambios</button>

			</form>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>

@endsection
