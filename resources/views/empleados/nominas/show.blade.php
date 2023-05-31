@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Historial de un trabajador</h2>
			<p>Aquí podrá ver las consultas y ausentismos del trabajador</p>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-gris-claro" href="{{ url('empleados/nominas') }}"><i
						class="fas fa-arrow-circle-left"></i>Volver</a>
			</div>
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
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-lg-4 col-md-3 col-sm-12 text-center">
							@if ($trabajador->foto)
							<img style="width: 300px;"
								src="{{ $trabajador->photo_url }}">
							@else
							<i class="fas fa-user fa-10x"></i>
							@endif
							<br>
							<br>
							<h5>{{$trabajador->nombre}}</h5>
						</div>
						<div class="col-lg-4 col-md-5 col-sm-12">
							<ul class="list-group list-group-flush">
								<li class="list-group-item">
									<b>Email: </b>
									@if ($trabajador->email !== null && $trabajador->email !== '')
									<a
										href="mailto:{{$trabajador->email}}?subject=contacto ejornal">{{$trabajador->email}}</a>
									@else
									{{'No fue cargado'}}
									@endif
								</li>
								<li class="list-group-item">
									<b>Teléfono: </b>
									@if ($trabajador->telefono !== null && $trabajador->telefono !== '')
									<a href="tel:{{$trabajador->telefono}}">{{$trabajador->telefono}}</a>
									@else
									{{'No fue cargado'}}
									@endif
								</li>
								<li class="list-group-item">
									<b>Calle: </b> {{$trabajador->calle}}
								</li>
								<li class="list-group-item">
									<b>Nro: </b> {{$trabajador->nro}}
								</li>
								<li class="list-group-item">
									<b>Entre calles: </b> {{$trabajador->entre_calles}}
								</li>
							</ul>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12">
							<ul class="list-group list-group-flush">
								<li class="list-group-item">
									<b>Localidad: </b> {{$trabajador->localidad}}
								</li>
								<li class="list-group-item">
									<b>Partido: </b> {{$trabajador->partido}}
								</li>
								<li class="list-group-item">
									<b>Codigo postal: </b> {{$trabajador->cod_postal}}
								</li>
								<li class="list-group-item">
									<b>DNI: </b> {{$trabajador->dni}}
								</li>
								<li class="list-group-item">
									<b>Activo: </b>
									@if ($trabajador->estado == 1)
									Si
									@else
									No
									@endif
								</li>
							</ul>
						</div>
						<div class="col-sm-12">
							<ul class="list-group list-group-flush">
								<li class="list-group-item">
									<b>Observaciones: </b> {{$trabajador->observaciones}}
								</li>
							</ul>
						</div>
					</div>
				</div>

			</div>

			<div class="row">
				<div class="col-md-6 col-lg-6 mb-4 col-sm-12">
					<div class="card mdb-color">
						<div class="card-body">
							<p class="d-flex align-items-center">
								<i class="fas fa-notes-medical fa-3x text-info"></i>
								<span style="font-size: 20px; color: white;"
									class="ml-4 font-weight-bold cyan-lighter-hover">Testeos</span>
							</p>
							<p class="text-muted font-weight-light mb-0">
							<ul class="testeos_vacunas_historial_trabajador">
								@if (isset($testeos) && !empty($testeos))
								@foreach ($testeos as $testeo)
								<li>{{$testeo->nombre}}</li>
								<li>{{$testeo->resultado}}</li>
								<li>{{$testeo->laboratorio}}</li>
								<li>{{ (!empty($testeo->fecha)) ? date('d/m/Y',strtotime($testeo->fecha)) : "" }}
								</li>
								<hr style="background: grey;">
								@endforeach
								@else
								<li>No hay testeos realizados</li>
								@endif
							</ul>
							</p>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-6 mb-4 col-sm-12">
					<div class="card mdb-color">
						<div class="card-body">
							<p class="d-flex align-items-center">
								<i class="fas fa-vial fa-3x text-info"></i>
								<span style="font-size: 20px; color: white;"
									class="ml-4 font-weight-bold cyan-lighter-hover">Vacunas</span>
							</p>
							<p class="text-muted font-weight-light mb-0">
							<ul class="testeos_vacunas_historial_trabajador">
								@if (isset($vacunas) && !empty($vacunas))
								@foreach ($vacunas as $vacuna)
								<li>{{$vacuna->nombre}}</li>
								<li>{{$vacuna->institucion}}</li>
								<li>{{ (!empty($vacuna->fecha)) ? date('d/m/Y',strtotime($vacuna->fecha)) : "" }}
								</li>
								<hr style="background: grey;">
								@endforeach
								@else
								<li>No hay vacunas aplicadas</li>
								@endif
							</ul>
							</p>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-12">
					<div class="accordion" id="accordionExample">
						<div class="card">
							<div class="card-header bg-dark text-white cabecera_consultas_historial" id="headingOne">
								<h2 class="mb-0 d-flex">
									<button class="btn btn-link btn-block text-left" type="button"
										data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
										aria-controls="collapseOne">
										<h6>Consultas medicas</h6>
									</button>
									<i class="fal fa-chevron-circle-down text-white"></i>
								</h2>
							</div>

							<div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
								data-parent="#accordionExample">
								<div class="card-body">
									@if (count($consultas_medicas) > 0)
									<div class="card_consultas">
										@foreach ($consultas_medicas as $consulta_medica)
										<div class="card_consulta">
											<div class="card_consultas_titulo">
												<h6>
													Fecha
													{{ (!empty($consulta_medica->fecha)) ?
													date('d/m/Y',strtotime($consulta_medica->fecha))
													: "" }}
												</h6>
											</div>
											<ul class="list-group list-group-flush">
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Temp. auxiliar:
													</span>
													{{ (!empty($consulta_medica->temperatura_auxiliar)) ?
													$consulta_medica->temperatura_auxiliar : "" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Peso: </span>
													{{ (!empty($consulta_medica->peso)) ? $consulta_medica->peso : "" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Altura: </span>
													{{ (!empty($consulta_medica->altura)) ? $consulta_medica->altura :
													"" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">IMC: </span>
													{{ (!empty($consulta_medica->imc)) ? $consulta_medica->imc : "" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Glucemia: </span>
													{{ (!empty($consulta_medica->glucemia)) ? $consulta_medica->glucemia
													:
													"" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Saturación de
														oxígeno: </span>
													{{ (!empty($consulta_medica->saturacion_oxigeno)) ?
													$consulta_medica->saturacion_oxigeno
													: "" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Tensión arterial:
													</span>
													{{ (!empty($consulta_medica->tension_arterial)) ?
													$consulta_medica->tension_arterial :
													"" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Frecuencia
														cardíaca:
													</span>
													{{ (!empty($consulta_medica->frec_cardiaca)) ?
													$consulta_medica->frec_cardiaca : "" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Derivación:
													</span>
													{{ (!empty($consulta_medica->derivacion_consulta)) ?
													$consulta_medica->derivacion_consulta : "" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Anamnesis:
													</span>
													{{ (!empty($consulta_medica->anamnesis)) ?
													$consulta_medica->anamnesis :
													"" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Tratamiento:
													</span>
													{{ (!empty($consulta_medica->tratamiento)) ?
													$consulta_medica->tratamiento : "" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Observaciones:
													</span>
													{{ (!empty($consulta_medica->observaciones)) ?
													$consulta_medica->observaciones : "" }}
												</li>
											</ul>
											<br>
										</div>
										@endforeach
									</div>
									@else
									<div class="alert alert-light" role="alert">
										No hay ninguno
									</div>
									@endif
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-header bg-dark text-white cabecera_consultas_historial" id="headingTwo">
								<h2 class="mb-0 d-flex">
									<button class="btn btn-link btn-block text-left collapsed" type="button"
										data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
										aria-controls="collapseTwo">
										<h6>Consultas Enfermeria</h6>
									</button>
									<i class="fal fa-chevron-circle-down text-white"></i>
								</h2>
							</div>
							<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
								data-parent="#accordionExample">
								<div class="card-body">
									@if (count($consultas_enfermeria) > 0)
									<div class="card_consultas">
										@foreach ($consultas_enfermeria as $consulta_enfermeria)
										<div class="card_consulta">
											<div class="card_consultas_titulo">
												<h6>
													Fecha:
													{{ (!empty($consulta_enfermeria->fecha)) ?
													date('d/m/Y',strtotime($consulta_enfermeria->fecha)) : "" }}
												</h6>
											</div>
											<ul class="list-group list-group-flush">
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Temp. auxiliar:
													</span>
													{{ (!empty($consulta_enfermeria->temperatura_auxiliar)) ?
													$consulta_enfermeria->temperatura_auxiliar : "" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Peso: </span>
													{{ (!empty($consulta_enfermeria->peso)) ? $consulta_enfermeria->peso
													:
													"" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Altura: </span>
													{{ (!empty($consulta_enfermeria->altura)) ?
													$consulta_enfermeria->altura
													:
													"" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">IMC: </span>
													{{ (!empty($consulta_enfermeria->imc)) ? $consulta_enfermeria->imc :
													""
													}}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Glucemia: </span>
													{{ (!empty($consulta_enfermeria->glucemia)) ?
													$consulta_enfermeria->glucemia
													: "" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Saturación de
														oxígeno:
													</span>
													{{ (!empty($consulta_enfermeria->saturacion_oxigeno)) ?
													$consulta_enfermeria->saturacion_oxigeno : "" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Tensión arterial:
													</span>
													{{ (!empty($consulta_enfermeria->tension_arterial)) ?
													$consulta_enfermeria->tension_arterial : "" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Frecuencia
														cardíaca:
													</span>
													{{ (!empty($consulta_enfermeria->frec_cardiaca)) ?
													$consulta_enfermeria->frec_cardiaca :
													"" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Derivación:
													</span>
													{{ (!empty($consulta_enfermeria->derivacion_consulta)) ?
													$consulta_enfermeria->derivacion_consulta : "" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Observaciones:
													</span>
													{{ (!empty($consulta_enfermeria->observaciones)) ?
													$consulta_enfermeria->observaciones :
													"" }}
												</li>
											</ul>
											<br>
										</div>
										@endforeach
									</div>
									@else
									<div class="alert alert-light" role="alert">
										No hay ninguno
									</div>
									@endif
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-header bg-dark text-white cabecera_consultas_historial" id="headingThree">
								<h2 class="mb-0 d-flex">
									<button class="btn btn-link btn-block text-left collapsed" type="button"
										data-toggle="collapse" data-target="#collapseThree" aria-expanded="false"
										aria-controls="collapseThree">
										<h6>Examemes medicos complementarios</h6>
									</button>
									<i class="fal fa-chevron-circle-down text-white"></i>
								</h2>
							</div>
							<div id="collapseThree" class="collapse" aria-labelledby="headingThree"
								data-parent="#accordionExample">
								<div class="card-body">
									@if (count($preocupacionales) > 0)
									<div class="card_consultas">
										@foreach ($preocupacionales as $preocupacional)
										<div class="card_consulta">
											<div class="card_consultas_titulo">
												<h6>
													Fecha inicio:
													{{ (!empty($preocupacional->fecha)) ?
													date('d/m/Y',strtotime($preocupacional->fecha)) :
													"" }}
												</h6>
											</div>
											<ul class="list-group list-group-flush">
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Fecha final:
													</span>
													{{ (!empty($preocupacional->observaciones)) ?
													date('d/m/Y',strtotime($preocupacional->observaciones)) : "" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Trabajador:
													</span>
													{{ (!empty($preocupacional->nombre)) ? $preocupacional->nombre : ""
													}}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Archivo: </span>
													<br>
													<br>
													@if ($preocupacional->archivo == null)
													<span style="color: grey;">No se adjuntó documentación</span>
													@else
													<a class="btn-ejornal btn-ejornal-gris-claro"
														href="{{route('preocupacionales.archivo', $preocupacional->id)}}">
														<i class="fa fa-file"></i>{{$preocupacional->archivo}}
													</a>
													@endif
												</li>
											</ul>
											<br>
										</div>
										@endforeach
									</div>
									@else
									<div class="alert alert-light" role="alert">
										No hay ninguno
									</div>
									@endif
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-header bg-dark text-white cabecera_consultas_historial" id="headingFour">
								<h2 class="mb-0 d-flex">
									<button class="btn btn-link btn-block text-left collapsed" type="button"
										data-toggle="collapse" data-target="#collapseFour" aria-expanded="false"
										aria-controls="collapseFour">
										<h6>Ausentismos</h6>
									</button>
									<i class="fal fa-chevron-circle-down text-white"></i>
								</h2>
							</div>
							<div id="collapseFour" class="collapse" aria-labelledby="headingFour"
								data-parent="#accordionExample">
								<div class="card-body">
									@if (count($ausentismos) > 0)
									<div class="card_consultas">
										@foreach ($ausentismos as $ausentismo)
										<div class="card_consulta">
											<div class="card_consultas_titulo">
												<h6>
													Fecha inicio:
													{{ (!empty($ausentismo->fecha_inicio)) ?
													date('d/m/Y',strtotime($ausentismo->fecha_inicio)) : "" }}
												</h6>
											</div>
											<ul class="list-group list-group-flush">
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Fecha final:
													</span>
													{{ (!empty($ausentismo->fecha_final)) ?
													date('d/m/Y',strtotime($ausentismo->fecha_final)) : "" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Fecha regreso
														trabajar:
													</span>
													{{ (!empty($ausentismo->fecha_regreso_trabajar)) ?
													date('d/m/Y',strtotime($ausentismo->fecha_regreso_trabajar)) : "" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Tipo: </span>
													{{ (!empty($ausentismo->tipo)) ? $ausentismo->tipo : "" }}
												</li>
												<li class="list-group-item">
													<span style="font-weight: 600;" class="text_black">Archivo: </span>
													<br>
													<br>
													@if ($ausentismo->archivo == null)
													<span style="color: grey;">No se adjuntó documentación</span>
													@else
													<a class="btn-ejornal btn-ejornal-gris-claro"
														href="{{route('ausentismos.archivo', $ausentismo->id)}}">
														<i class="fa fa-file"></i>{{$ausentismo->archivo}}
													</a>
													@endif
												</li>
											</ul>
											<br>
										</div>
										@endforeach
									</div>
									@else
									<div class="alert alert-light" role="alert">
										No hay ninguno
									</div>
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>



		{{-- Contenido de la pagina --}}
	</div>
</div>

@endsection