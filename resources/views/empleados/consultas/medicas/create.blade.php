@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Creación de consultas medicas</h2>
			<p>Aquí puedes cargar los consultas medicas pertinentes a la empresa en la que estas trabajando</p>
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

		<div class="tarjeta">
			<form id="form_guardar_consulta_medica" action="{{action('EmpleadoConsultaMedicaController@store')}}"
				accept-charset="UTF-8" method="post">
				@csrf
				<div class="form-row">
					<div class="form-group col-md-3">
						<label>Trabajador: <span style="color: red;">*</span></label>
						<select name="nomina" class="form-control select_2" required>
							<option value="">--Seleccionar--</option>
							@foreach ($nominas as $nomina)
							<option value="{{$nomina->id}}" {{ old('nomina')==$nomina->id ? 'selected' : '' }}
								>{{$nomina->nombre}}</option>
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
						<input disabled readonly required id="data_picker_gral" name="fecha_fija" type="text"
							class="form-control" value="{{ old(" fecha_fija") }}">
						<input required id="fecha_actual_oculta" name="fecha" type="hidden" class="form-control"
							value="{{ old(" fecha") }}">
					</div>
					<div class="form-group col-md-3">
						<label>Amerita salida <span style="color: red;">*</span></label>
						<select name="amerita_salida" class="form-control">
							<option value="">--Seleccionar--</option>
							<option value="1" {{ old('amerita_salida')==1 ? 'selected' : '' }}>Si</option>
							<option value="0" {{ old('amerita_salida')==0 ? 'selected' : '' }}>No</option>
						</select>
					</div>
				</div>
				<div class="form-group col-md-12">
					<ul class="list-group">
						<label>
							Medicación:
							<a id="cargar_medicacion" style="color: #6f9eab; margin-right: 10px;" href="#">
								<i class="fas fa-plus-circle"></i>
							</a>
						</label>

						<div class="listado_medicaciones"></div>
						<div class="listado_medicaciones_inputs_ocultos"></div>

					</ul>
				</div>
				<div class="form-row">
					<div class="form-group col-md-3">
						<label>Temperatura axilar</label>
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
						<label>Saturacion oxígeno</label>
						<input name="saturacion_oxigeno" type="number" class="form-control" value="{{ old("
							saturacion_oxigeno") }}">
					</div>
					<div class="form-group col-md-3">
						<label>Tension arterial</label>
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
						<label>Anamnesis</label>
						<textarea name="anamnesis" required class="form-control"
							rows="3">{{ old("anamnesis") }}</textarea>
					</div>
					<div class="form-group col-md-4">
						<label>Tratamiento <span style="color: red;">*</span></label>
						<textarea name="tratamiento" required class="form-control"
							rows="3">{{ old("tratamiento") }}</textarea>
					</div>
					<div class="form-group col-md-4">
						<label>Observaciones <span style="color: red;">*</span></label>
						<textarea name="observaciones" required class="form-control"
							rows="3">{{ old("observaciones") }}</textarea>
					</div>
				</div>
				<button id="guarda_consulta" class="btn-ejornal btn-ejornal-base" type="submit" name="button">Guardar
					consulta médica</button>
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
				<h5 class="modal-title" id="diagnostico_titulo">Crear tipo de diagnostico</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="row">
					<div class="col-md-12">
						<form action="{{action('EmpleadoConsultaMedicaController@tipo')}}" accept-charset="UTF-8"
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
				<h5 class="modal-title" id="ver_tipo_diagnostico_titulo">Tipos de Diagnostico cargados</h5>
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
									<th class="th-lg">
										<a>
											Tipo
											<i class="fas fa-sort ml-1"></i>
										</a>
									</th>
									<th class="th-lg">
										<a href="">
											Acciones
											<i class="fas fa-sort ml-1"></i>
										</a>
									</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($diagnostico_consultas as $tipo)
								<tr>
									<td>{{$tipo->nombre}}</td>
									<td class="acciones_tabla" scope="row">
										<form class="" action="{{route('consultas.medicas.tipo_delete', $tipo->id)}}"
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





<!-- Modal Cargar tipo de medicacion -->
<div class="modal fade" id="cargar_medicacion_abrir" tabindex="-1" aria-labelledby="cargar_medicacion_titulo"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="cargar_medicacion_titulo">Suministrar medicación</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<input type="text" class="form-control" id="medicamentoSearch"
						placeholder="Busca tu medicamento...">
				</div>
				<div class="row">
					<div class="col-md-12">
						<form class="modal_medicacion_a_suministrar" action="" accept-charset="UTF-8" method="">
							@foreach ($stock_medicamentos as $medicamento)
							<div class="btn-toolbar mb-3" role="toolbar" aria-label="Toolbar with button groups">
								<h6>{{$medicamento->nombre}}</h6>
								<div style="width: 100%;" class="input-group">
									<div class="input-group-prepend">
										<div class="input-group-text">En stock: {{$medicamento->stock}}</div>
									</div>
									<input name="{{$medicamento->id}}" type="text" class="form-control" placeholder=""
										value="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)">
								</div>
							</div>
							@endforeach
							<button id="aceptar_suministrar_medicamentos" type="button"
								class="btn-ejornal btn-ejornal-success">Aceptar</button>
							<button type="button" class="btn-ejornal btn-ejornal-gris-claro"
								data-dismiss="modal">Cerrar</button>
						</form>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>




<!-- Modal confirmación Final -->
<div class="modal fade" id="consulta_confirmacion_final" tabindex="-1"
	aria-labelledby="consulta_confirmacion_final_Titulo" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="consulta_confirmacion_final_Titulo">Advertencia</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-light" role="alert">
							<p>Una vez creada la consulta no podrá editarse. Presione Aceptar para continuar o cancelar
								para revisar la consulta.</p>
							<hr>
							<a id="consulta_medica_crear_ok" type="button"
								class="btn-ejornal btn-ejornal-success">Aceptar</a>
							<a type="button" class="btn-ejornal btn-ejornal-gris-claro" data-dismiss="modal">Cerrar</a>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>



@endsection