@extends('partials.layout')

@section('title', 'Empleado - Crear Consulta')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')

		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Crear consulta</h2>
			<p>Aquí puedes crear consultas de otros profesionales para un trabajador de la nómina.</p>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-gris-claro" href="{{ url('empleados/consultas/otras') }}">
					<i class="fas fa-arrow-circle-left"></i> Volver
				</a>
			</div>
		</div>

		@include('../../mensajes_validacion')

		@if ($errors->any())
		@foreach ($errors->all() as $error)
		<div class="alert alert-danger alert-dismissible fade show mr-4 ml-4" role="alert">
			{{ $error }}
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		@endforeach
		@endif

		<div class="tarjeta" id="caratula">
			<p class="alert alert-info">Selecciona un trabajador de la nómina para ver su carátula.</p>
			{{-- Se completa dinámicamente por JS --}}
		</div>

		<div class="tarjeta">
			<form id="form_guardar_consulta_otra" action="{{ Route::has('empleados.consultas.otras.store') ? route('empleados.consultas.otras.store') : url('empleados/consultas/otras') }}" accept-charset="UTF-8" method="post">
				@csrf

				<input type="hidden" value="{{ isset($cliente) ? $cliente->id : auth()->user()->id_cliente_actual }}" name="cliente_id" id="id_cliente_actual">
				<input type="hidden" value="{{ isset($cliente) ? $cliente->id : auth()->user()->id_cliente_actual }}" name="id_cliente">

				<div class="form-row">
					<div class="form-group col-md-4">
						<label>Trabajador <span style="color: red;">*</span></label>
						<select required id="id_nomina" name="nomina_id" class="form-control select_2">
							<option value="">--Seleccionar--</option>
							@if(isset($nominas))
							@foreach ($nominas as $nomina)
							<option value="{{ $nomina->id }}" {{ (old('nomina_id') == $nomina->id || old('id_nomina') == $nomina->id || Request::get('id_nomina') == $nomina->id) ? 'selected' : '' }}>
								{{ $nomina->nombre }}
							</option>
							@endforeach
							@endif
						</select>
					</div>

					<div class="form-group col-md-4">
						<label>Tipo de profesional <span style="color: red;">*</span></label>
						<select required id="tipo_profesional_id" name="tipo_profesional_id" class="form-control select_2">
							<option value="">--Seleccionar--</option>
							@php
								$tiposProfesionales = $profesionales_tipos ?? $tipos_profesionales ?? $tipos ?? [];
							@endphp
							@foreach ($tiposProfesionales as $tipo)
							<option value="{{ $tipo->id }}" {{ old('tipo_profesional_id') == $tipo->id ? 'selected' : '' }}>
								{{ $tipo->nombre }}
							</option>
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-4">
						<label>Fecha <span style="color: red;">*</span></label>
						<input required id="data_picker_gral" name="fecha" type="text" class="form-control" value="{{ old('fecha', date('d/m/Y')) }}" placeholder="Click para desplegar el calendario" autocomplete="off" readonly >
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-12">
						<label>Motivo <span style="color: red;">*</span></label>
						<textarea required name="motivo" class="form-control" rows="3" placeholder="Ingrese el motivo de la consulta...">{{ old('motivo') }}</textarea>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-12">
						<label>Desarrollo <span style="color: red;">*</span></label>
						<textarea required name="desarrollo" class="form-control" rows="10" placeholder="Detalle el desarrollo o evolución de la consulta...">{{ old('desarrollo') }}</textarea>
					</div>
				</div>

				<div class="form-group mb-0">
					<button class="btn-ejornal btn-ejornal-base" type="submit" name="button">
						<i class="fas fa-save mr-1"></i> Crear consulta
					</button>
				</div>
			</form>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>

@endsection
