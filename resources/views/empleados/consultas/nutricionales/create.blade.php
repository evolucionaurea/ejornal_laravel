@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Crear consulta nutricional</h2>
			<p>Aquí puedes crear consultas nutricionales para un trabajador de la nómina</p>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-gris-claro" href="{{ url('empleados/consultas/nutricionales') }}"><i
						class="fas fa-arrow-circle-left"></i>Volver</a>
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

		<div class="tarjeta" id="caratula">
			<p class="alert alert-info">Selecciona un trabajador de la nómina para ver su carátula.</p>
			{{-- Se completa por JS --}}
		</div>

		<div class="tarjeta">
			<form id="form_guardar_consulta_nutricional" action="{{action('EmpleadosConsultaNutricionalController@store')}}" accept-charset="UTF-8" method="post"  enctype="multipart/form-data">
				@csrf
				
				<input type="hidden" value="{{ $cliente->id }}" name="id_cliente" id="id_cliente_actual">

				<div class="form-row">
					<div class="form-group col-md-3">
						<label>Trabajador <span style="color: red;">*</span></label>
						<select required id="id_nomina" name="id_nomina" class="form-control select_2">
							<option value="">--Seleccionar--</option>
							@foreach ($nominas as $nomina)
							<option value="{{ $nomina->id }}" {{ old('id_nomina')==$nomina->id || Request::get('id_nomina')==$nomina->id ? 'selected' : '' }} >{{ $nomina->nombre }}</option>
							@endforeach
						</select>
					</div>
					{{-- <div class="form-group col-md-3">
						<label>Cliente <span style="color: red;">*</span></label>
						<input name="cliente" type="text" class="form-control" placeholder="" value="{{ $cliente->nombre }}" disabled>
					</div> --}}
					<div class="form-group col-md-3">
						<label>Tipo <span style="color: red;">*</span></label>
						<select required id="tipo-consulta" name="tipo" class="form-control">
							<option value="inicial" {{ old('tipo')=='inicial' ? 'selected' : '' }}>Inicial</option>
							<option value="seguimiento" {{ old('tipo')=='seguimiento' ? 'selected' : '' }}>Seguimiento
							</option>
						</select>
					</div>
					<div class="form-group col-md-3">
						<label>Fecha atención <span style="color: red;">*</span></label>
						<input required data-toggle="has-datepicker" name="fecha_atencion" type="text" class="form-control" value="{{ old('fecha_atencion') }}" readonly placeholder="click para desplegar el calendario" >
					</div>
				</div>

				<div class="row">

					<div class="form-group col-md-3">
						<label for="">Peso</label>
						<input required name="peso" type="number" class="form-control" value="{{ old('peso') }}" step="0.01" min="1">
					</div>
					<div class="form-group col-md-3">
						<label for="">Altura</label>
						<input required name="altura" type="number" class="form-control" value="{{ old('altura') }}" step="0.01" min="1">
					</div>
					<div class="form-group col-md-3">
						<label for="">IMC</label>
						<input required readonly name="imc" type="number" class="form-control" value="{{ old('imc') }}" >
					</div>
				</div>

				<div class="row">

					{{-- Campos de tipo Inicial --}}
					<div class="form-group col-md-4 campos-inicial">
						<label>Objetivos</label>
						<textarea class="form-control" name="objetivos" cols="30" rows="5">{{ old('objetivos') }}</textarea>
					</div>
					<div class="form-group col-md-4 campos-inicial">
						<label>Gustos alimentarios</label>
						<textarea class="form-control" name="gustos_alimentarios" cols="30" rows="5">{{ old('gustos_alimentarios') }}</textarea>
					</div>
					<div class="form-group col-md-4 campos-inicial">
						<label>Comidas diarias</label>
						<textarea class="form-control" name="comidas_diarias" cols="30" rows="5">{{ old('comidas_diarias') }}</textarea>
					</div>
					<div class="form-group col-md-4 campos-inicial">
						<label>Descanso</label>
						<textarea class="form-control" name="descanso" cols="30" rows="5">{{ old('descanso') }}</textarea>
					</div>
					<div class="form-group col-md-4 campos-inicial">
						<label>Intolerancias digestivas</label>
						<textarea class="form-control" name="intolerancias_digestivas" cols="30" rows="5">{{ old('intolerancias_digestivas') }}</textarea>
					</div>
					<div class="form-group col-md-4 campos-inicial">
						<label>Alergias alimentarias</label>
						<textarea class="form-control" name="alergias_alimentarias" cols="30" rows="5">{{ old('alergias_alimentarias') }}</textarea>
					</div>

					{{-- Campos de tipo Seguimiento --}}
					<div class="form-group col-md-3 campos-seguimiento">
						<label>Circunferencia cintura</label>
						<input name="circunferencia_cintura" type="number" step="0.01" min="0" max="999.99" class="form-control" value="{{ old('circunferencia_cintura') }}" >
					</div>
					<div class="form-group col-md-3 campos-seguimiento">
						<label>Porcentaje de masa grasa</label>
						<input name="porcent_masa_grasa" type="number" step="0.01" min="0" max="999.99" class="form-control" value="{{ old('porcent_masa_grasa') }}" >
					</div>
					<div class="form-group col-md-3 campos-seguimiento">
						<label>Porcentaje masa muscular</label>
						<input name="porcent_masa_muscular" type="number" step="0.01" min="0" max="999.99" class="form-control" value="{{ old('porcent_masa_muscular') }}">
					</div>
					<div class="form-group col-md-3 campos-seguimiento">
						<label>Próxima cita</label>
						<input data-toggle="has-datepicker" name="prox_cita" type="text" class="form-control" value="{{ old('prox_cita') }}" readonly placeholder="click para desplegar el calendario">
					</div>
					<div class="form-group col-md-3 campos-seguimiento">
						<label>Act física</label>
						<textarea class="form-control" name="act_fisica" cols="30" rows="5">{{ old('act_fisica') }}</textarea>
					</div>
					<div class="form-group col-md-3 campos-seguimiento">
						<label>Tránsito intestinal</label>
						<textarea class="form-control" name="transito_intestinal" cols="30" rows="5">{{ old('transito_intestinal') }}</textarea>
					</div>
					<div class="form-group col-md-3 campos-seguimiento">
						<label>Evolución</label>
						<textarea class="form-control" name="evolucion" cols="30" rows="5">{{ old('evolucion') }}</textarea>
					</div>
					<div class="form-group col-md-3 campos-seguimiento">
						<label>Medicaciones</label>
						<textarea class="form-control" name="medicaciones" cols="30" rows="5">{{ old('medicaciones') }}</textarea>
					</div>

				</div>
				<button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Crear consulta nutricional</button>
			</form>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>

@endsection