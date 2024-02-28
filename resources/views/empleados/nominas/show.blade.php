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
				<a class="btn-ejornal btn-ejornal-gris-claro" href="{{ url('empleados/nominas') }}?{{$_SERVER['QUERY_STRING']}}"><i
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

			{{-- PRINCIPAL --}}
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
							<b>CUIL: </b>
							@if ($trabajador->email !== null && $trabajador->email !== '')
							<span>{{$trabajador->email}}</span>
							@else
							{{'No fue cargado'}}
							@endif
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
						<li class="list-group-item">
							<b>Teléfono: </b>
							@if ($trabajador->telefono !== null && $trabajador->telefono !== '')
							<a href="tel:{{$trabajador->telefono}}">{{$trabajador->telefono}}</a>
							@else
							{{'No fue cargado'}}
							@endif
						</li>
						<li class="list-group-item">
							<b>Fecha Nacimiento:</b> {{$trabajador->fecha_nacimiento ? $trabajador->fecha_nacimiento->format('d/m/Y') : '[no cargado]' }}
						</li>
						@if($trabajador->fecha_nacimiento)
						<li class="list-group-item">
							<b>Edad: </b> {{$trabajador->edad}}
						</li>
						@endif

					</ul>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12">
					<ul class="list-group list-group-flush">
						<li class="list-group-item">
							<b>Calle: </b> {{$trabajador->calle}}
						</li>
						<li class="list-group-item">
							<b>Nro: </b> {{$trabajador->nro}}
						</li>
						<li class="list-group-item">
							<b>Entre calles: </b> {{$trabajador->entre_calles}}
						</li>
						<li class="list-group-item">
							<b>Localidad: </b> {{$trabajador->localidad}}
						</li>
						<li class="list-group-item">
							<b>Partido: </b> {{$trabajador->partido}}
						</li>
						<li class="list-group-item">
							<b>Codigo postal: </b> {{$trabajador->cod_postal}}
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


			{{-- TESTEOS Y VACUNAS --}}
			<div class="row">
				<div class="col-md-6 col-lg-6 mb-4 col-sm-12">
					<div class="card mdb-color" style="max-height:420px;overflow-y:auto;">
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
					<div class="card mdb-color" style="max-height:420px;overflow-y:auto;">
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

			{{-- CONSULTAS --}}
			<div class="accordion" id="accordionExample">

				{{-- MEDICAS --}}
				<div class="card">
					<div class="card-header bg-dark text-white cabecera_consultas_historial" id="headingOne">
						<h2 class="mb-0 d-flex">
							<button class="btn btn-link btn-block text-left" type="button"
								data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
								aria-controls="collapseOne">
								<h6>Consultas médicas ({{count($consultas_medicas)}})</h6>
							</button>
							<i class="fal fa-chevron-circle-down text-white"></i>
						</h2>
					</div>

					<div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
						data-parent="#accordionExample">

						<div class="card-body">
							<table table data-table="medicas" class="table small table-bordered table-sm w-100">
								<thead>
									<tr>
										<th>Fecha</th>
										<th>Tipo</th>
										<th>Temp. Aux.</th>
										<th>Peso</th>
										<th>Altura</th>
										<th>IMC</th>
										<th>Glucemia</th>
										<th>Sat. Oxígeno</th>
										<th>Tensión Art.</th>
										<th>Frec. Cardíaca</th>
										<th style="width:220px">Anamnesis</th>
										<th style="width:220px">Tratamiento</th>
										<th style="width:220px">Observaciones</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($consultas_medicas as $consulta_medica)
									<tr>
										<td>{{ $consulta_medica->fecha->format('d/m/Y') }}</td>
										<td>{{ $consulta_medica->diagnostico ?? '-' }}</td>
										<td>{{ $consulta_medica->temperatura_auxiliar ?? '-' }}</td>
										<td>{{ $consulta_medica->peso ?? '-' }}</td>
										<td>{{ $consulta_medica->altura ?? '-' }}</td>
										<td>{{ $consulta_medica->imc ?? '-' }}</td>
										<td>{{ $consulta_medica->glucemia ?? '-' }}</td>
										<td>{{ $consulta_medica->saturacion_oxigeno ?? '-' }}</td>
										<td>{{ $consulta_medica->tension_arterial ?? '-' }}</td>
										<td>{{ $consulta_medica->frec_cardiaca ?? '-' }}</td>
										<td>{{ $consulta_medica->anamnesis }}</td>
										<td>{{ $consulta_medica->tratamiento }}</td>
										<td>{{ $consulta_medica->observaciones }}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>

					</div>
				</div>

				{{-- ENFERMERIA --}}
				<div class="card">
					<div class="card-header bg-dark text-white cabecera_consultas_historial" id="headingTwo">
						<h2 class="mb-0 d-flex">
							<button class="btn btn-link btn-block text-left collapsed" type="button"
								data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
								aria-controls="collapseTwo">
								<h6>Consultas Enfermería ({{ count($consultas_enfermeria) }})</h6>
							</button>
							<i class="fal fa-chevron-circle-down text-white"></i>
						</h2>
					</div>
					<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
						data-parent="#accordionExample">

						<div class="card-body">
							<table table data-table="enfermeria" class="table small table-bordered table-sm w-100">
								<thead>
									<tr>
										<th>Fecha</th>
										<th>Tipo</th>
										<th>Temp. Aux.</th>
										<th>Peso</th>
										<th>Altura</th>
										<th>IMC</th>
										<th>Glucemia</th>
										<th>Sat. Oxígeno</th>
										<th>Tensión Art.</th>
										<th>Frec. Cardíaca</th>
										<th>Derivación</th>
										<th>Observaciones</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($consultas_enfermeria as $consulta_enfermeria)
									<tr>
										<td>{{ $consulta_enfermeria->fecha->format('d/m/Y') }}</td>
										<td>{{ $consulta_enfermeria->diagnostico ?? '-' }}</td>
										<td>{{ $consulta_enfermeria->temperatura_auxiliar ?? '-' }}</td>
										<td>{{ $consulta_enfermeria->peso ?? '-' }}</td>
										<td>{{ $consulta_enfermeria->altura ?? '-' }}</td>
										<td>{{ $consulta_enfermeria->imc ?? '-' }}</td>
										<td>{{ $consulta_enfermeria->glucemia ?? '-' }}</td>
										<td>{{ $consulta_enfermeria->saturacion_oxigeno ?? '-' }}</td>
										<td>{{ $consulta_enfermeria->tension_arterial ?? '-' }}</td>
										<td>{{ $consulta_enfermeria->frec_cardiaca ?? '-' }}</td>
										<td>{{ $consulta_enfermeria->derivacion_consulta }}</td>
										<td>{{ $consulta_enfermeria->observaciones }}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>

					</div>
				</div>

				{{-- EXAMENES --}}
				<div class="card">
					<div class="card-header bg-dark text-white cabecera_consultas_historial" id="headingThree">
						<h2 class="mb-0 d-flex">
							<button class="btn btn-link btn-block text-left collapsed" type="button"
								data-toggle="collapse" data-target="#collapseThree" aria-expanded="false"
								aria-controls="collapseThree">
								<h6>Exámemes médicos complementarios ({{ count($preocupacionales) }})</h6>
							</button>
							<i class="fal fa-chevron-circle-down text-white"></i>
						</h2>
					</div>
					<div id="collapseThree" class="collapse" aria-labelledby="headingThree"
						data-parent="#accordionExample">

						<div class="card-body">

							<table data-table="preocupacionales" class="table small table-bordered table-sm w-100">
								<thead>
									<tr>
										<th>Fecha</th>
										<th>Observaciones</th>
										<th>Archivo</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($preocupacionales as $preocupacional)
									<tr>
										<td>{{ (!empty($preocupacional->fecha)) ? date('d/m/Y',strtotime($preocupacional->fecha)) : "" }}</td>
										<td>{{$preocupacional->observaciones}}</td>
										<td>
											@if ($preocupacional->archivo)
											<a class="btn-ejornal btn-ejornal-gris-claro" href="{{route('preocupacionales.archivo', $preocupacional->id)}}">
												<i class="fa fa-file"></i>{{$preocupacional->archivo}}
											</a>
											@else
											<span class="text-muted font-italic">No se adjuntó documentación</span>
											@endif
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>

						</div>

					</div>
				</div>

				{{-- AUSENTISMOS --}}
				<div class="card">
					<div class="card-header bg-dark text-white cabecera_consultas_historial" id="headingFour">
						<h2 class="mb-0 d-flex">
							<button class="btn btn-link btn-block text-left collapsed" type="button"
								data-toggle="collapse" data-target="#collapseFour" aria-expanded="false"
								aria-controls="collapseFour">
								<h6>Ausentismos ({{count($ausentismos)}})</h6>
							</button>
							<i class="fal fa-chevron-circle-down text-white"></i>
						</h2>
					</div>
					<div id="collapseFour" class="collapse" aria-labelledby="headingFour"
						data-parent="#accordionExample">
						<div class="card-body">
							@include('modulos.ausentismos_historial_tabla')
						</div>

					</div>
				</div>
			</div>

		</div>



		{{-- Contenido de la pagina --}}
	</div>
</div>

@endsection