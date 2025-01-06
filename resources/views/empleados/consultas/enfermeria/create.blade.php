@extends('partials.layout')

@section('title', 'Empleado')

@section('content')


<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Creación de consultas de enfermería</h2>
			<p>Aquí puedes cargar los consultas de enfermería pertinentes a la empresa en la que estas trabajando</p>
		</div>

		@include('../../../mensajes_validacion')
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
			<p class="alert alert-info">Seleccione un trabajador de la nomina</p>
			{{-- Se completa por JS --}}
		</div>

		<div class="tarjeta">
			<form id="form_guardar_consulta_enfermeria"
				action="{{action('EmpleadoConsultaEnfermeriaController@store')}}" accept-charset="UTF-8" method="post">
				@csrf
				<input type="hidden" name="id_cliente_actual" id="id_cliente_actual"
					value="{{auth()->user()->id_cliente_actual}}">
				<div class="form-row">
					<div class="form-group col-md-3">
						<label>Trabajador: <span style="color: red;">*</span></label>
						<select id="id_nomina" name="nomina" class="form-control select_2" required>
							<option value="">--Seleccionar--</option>
							@foreach ($nominas as $nomina)
							<option value="{{$nomina->id}}" {{ old('nomina')==$nomina->id ? 'selected' : ''
								}}>{{$nomina->nombre}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-md-3">
						<label>
							Diagnóstico: <span style="color: red;">*</span>
							@if (auth()->user()->permiso_desplegables == 1)
							<a style="color: #6f9eab; margin-right: 10px;" data-toggle="modal"
								data-target="#crear_diagnostico" href="#">
								<i class="fas fa-plus-circle"></i>
							</a>
							<a style="color: #6f9eab;" data-toggle="modal" data-target="#ver_tipo_diagnostico" href="#">
								<i class="fas fa-eye"></i>
							</a>
							@endif
						</label>
						<select name="tipo" class="form-control" required>
							<option value="">--Seleccionar--</option>
							@foreach ($diagnostico_consultas as $tipo)
							<option value="{{$tipo->id}}" {{ old('tipo')==$tipo->id ? 'selected' : ''
								}}>{{$tipo->nombre}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-md-3">
						<label>Fecha <span style="color: red;">*</span> </label>
						<input required id="data_picker_gral" name="fecha" type="text" class="form-control"
							value="{{ old(" fecha") }}">
					</div>
					<div class="form-group col-md-3">
						<label>Amerita salida <span style="color: red;">*</span></label>
						<select name="amerita_salida" class="form-control" required>
							<option value="">--Seleccionar--</option>
							<option value="1" {{ old('amerita_salida')=='1' ? 'selected' : '' }}>Si</option>
							<option value="0" {{ old('amerita_salida')=='0' ? 'selected' : '' }}>No</option>
						</select>
					</div>
				</div>

				<hr>

				<div class="form-row">
					<ul class="list-group col-md-3">
						<label>
							Medicación:
							<a id="cargar_medicacion" style="color: #6f9eab; margin-right: 10px;" href="#">
								<i class="fas fa-plus-circle"></i>
							</a>
						</label>
						<div class="listado_medicaciones">

						</div>
						<div class="listado_medicaciones_inputs_ocultos">

						</div>
					</ul>
				</div>

				<hr>

				<div class="form-row">
					<div class="form-group col-md-3">
						<label>Temperatura axiliar</label>
						<input name="temperatura_auxiliar" type="number" class="form-control" value="{{ old("
							temperatura_auxiliar") }}" step="0.01">
					</div>
					<div class="form-group col-md-3">
						<label>Peso</label>
						<input name="peso" type="number" class="form-control" value="{{ old(" peso") }}">
					</div>
					<div class="form-group col-md-3">
						<label>Altura</label>
						<div class="input-group">
							<input name="altura" type="number" class="form-control" value="{{ old(" altura") }}">
							<div class="input-group-append">
								<span class="input-group-text">cm.</span>
							</div>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label>IMC</label>
						<input disabled name="imc_disabled" type="text" class="form-control" value="{{ old(" imc") }}">
						<input name="imc" type="hidden" class="form-control" value="{{ old(" imc") }}">
					</div>
					<div class="form-group col-md-3">
						<label>Glucemia</label>
						<input name="glucemia" type="number" class="form-control" value="{{ old(" glucemia") }}">
					</div>
					<div class="form-group col-md-3">
						<label>Saturación oxígeno</label>
						<input name="saturacion_oxigeno" type="number" class="form-control" value="{{ old("
							saturacion_oxigeno") }}">
					</div>
					<div class="form-group col-md-3">
						<label>Tensión arterial</label>
						<input name="tension_arterial" type="text" class="form-control" value="{{ old("
							tension_arterial") }}">
					</div>
					<div class="form-group col-md-3">
						<label>Frecuencia Cardíaca</label>
						<input name="frec_cardiaca" type="number" class="form-control" value="{{ old(" frec_cardiaca")
							}}">
					</div>
					<div class="form-group col-md-3">
						<label>Derivación consulta <span style="color: red;">*</span></label>
						<select name="derivacion_consulta" class="form-control" required>
							<option value="">--Seleccionar--</option>
							<option value="Sanatorio" {{ old('derivacion_consulta')=='Sanatorio' ? 'selected' : '' }}>
								Sanatorio</option>
							<option value="ART" {{ old('derivacion_consulta')=='ART' ? 'selected' : '' }}>ART</option>
							<option value="Vuelve a trabajar" {{ old('derivacion_consulta')=='Vuelve a trabajar'
								? 'selected' : '' }}>Vuelve a trabajar</option>
						</select>
					</div>
					<div class="form-group col-md-4">
						<label>Observaciones <span style="color: red;">*</span></label>
						<textarea name="observaciones" required class="form-control"
							rows="3">{{ old("observaciones") }}</textarea>
					</div>
				</div>
				<button id="guarda_consulta" class="btn-ejornal btn-ejornal-base" type="submit" name="button">Guardar
					consulta de enfermería</button>
			</form>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>





<!-- Modal Crear tipo diagnostico consulta -->
<div class="modal fade" id="crear_diagnostico" tabindex="-1" aria-labelledby="diagnostico_titulo" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="diagnostico_titulo">Crear tipo de diagnóstico</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="row">
					<div class="col-md-12">
						<form action="{{action('EmpleadoConsultaEnfermeriaController@tipo')}}" accept-charset="UTF-8"
							method="post">
							{{ csrf_field() }}
							<div class="form-group">
								<label>Nombre</label>
								<input name="nombre" type="text" class="form-control form-control-sm" placeholder="">
							</div>
							<button type="submit" class="btn-ejornal btn-ejornal-success">Crear tipo</button>
							<button type="button" class="btn-ejornal btn-ejornal-gris-claro"
								data-dismiss="modal">Cerrar</button>
						</form>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>



<!-- Modal Ver tipo Diagnostico consulta -->
<div class="modal fade" id="ver_tipo_diagnostico" tabindex="-1" aria-labelledby="ver_tipo_diagnostico_titulo"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="ver_tipo_diagnostico_titulo">Tipos de Diagnóstico cargados</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">


				<div class="row">
					<div class="col-md-12">
						<table class="table table-striped table-hover table-sm ">
							<thead>
								<tr>
									<th>Tipo</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($diagnostico_consultas as $tipo)
								<tr>
									<td>{{$tipo->nombre}}</td>
									<td class="acciones_tabla" scope="row">
										<form class="" action="{{route('consultas.enfermeria.tipo_delete', $tipo->id)}}"
											method="post">
											{{ csrf_field() }}
											<input type="hidden" name="_method" value="DELETE">
											<button title="Eliminar" type="submit">
												<i class="fas fa-trash"></i>
											</button>
										</form>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>



<!-- Modal edicion Caratula -->
<div class="modal fade" id="modalEdicionCaratula" tabindex="-1" role="dialog"
	aria-labelledby="modalEdicionCaratulaLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalEdicionCaratulaLabel">Editar Carátula</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="formEdicionCaratula">
				<div class="modal-body">
					<!-- Primera Fila -->
					<div class="row">
						<div class="col-md-6 mb-3">
							<label for="cliente">Cliente</label>
							<input type="text" name="cliente" class="form-control" id="cliente" disabled>
							<input type="hidden" name="cliente_id" id="cliente_id">
						</div>
						<div class="col-md-6 mb-3">
							<label for="trabajador">Trabajador</label>
							<input type="text" name="trabajador" class="form-control" id="trabajador" disabled>
							<input type="hidden" name="trabajador_id" id="trabajador_id">
						</div>
						<div class="col-md-6 mb-3">
							<label for="patologia">Patología</label>
							<input type="text" name="patologia" class="form-control" id="patologia">
						</div>
						<div class="col-md-6 mb-3">
							<label for="peso">Peso (kg)</label>
							<input type="number" name="peso" class="form-control" id="peso">
						</div>
						<div class="col-md-6 mb-3">
							<label for="altura">Altura (cm)</label>
							<input type="number" name="altura" class="form-control" id="altura">
						</div>
						<div class="col-md-6 mb-3">
							<label for="imc">IMC</label>
							<input type="text" name="imc" class="form-control" id="imc">
						</div>
						<div class="col-md-6 mb-3">
							<label for="alergias">Alergias</label>
							<textarea name="alergias" class="form-control" id="alergias"></textarea>
						</div>
						<div class="col-md-6 mb-3">
							<label for="medicacion_habitual">Medicación Habitual</label>
							<textarea name="medicacion_habitual" class="form-control"
								id="medicacion_habitual"></textarea>
						</div>
					</div>
					<div class="row">
						<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
						<button type="submit" class="btn btn-primary btn-sm">Guardar Cambios</button>
					</div>
			</form>
		</div>
	</div>
</div>


@include('../../modulos/modales_crud_consultas')


@endsection