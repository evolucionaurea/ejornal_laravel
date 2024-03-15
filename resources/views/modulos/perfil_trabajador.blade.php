<div class="tarjeta">

	{{-- PRINCIPAL --}}
	<div class="row">

		<div class="col-lg-4 col-md-3 col-sm-12 text-center">
			@if ($trabajador->foto)
			{{-- <img style="width: 300px;" src="" class="rounded"> --}}
			<div class="foto-perfil" style="background-image: url({{ $trabajador->photo_url }})"></div>
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
					<b>Fecha Nacimiento:</b> {{$trabajador->fecha_nacimiento ?
					$trabajador->fecha_nacimiento->format('d/m/Y') : '[no cargado]' }}
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



	{{-- ACCORDIONES --}}
	<div class="accordion" id="accordionExample">

		{{-- Resumen de Historial --}}
		<div class="card">
			<div class="card-header bg-dark text-white cabecera_consultas_historial" id="headingFive">
				<h2 class="mb-0 d-flex">
					<button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
						data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
						<h6>Resumen Historial ({{count($resumen_historial)}})</h6>
					</button>
					<i style="cursor: pointer;" data-toggle="collapse" data-target="#collapseFive"
						class="fal fa-chevron-circle-down text-white"></i>
				</h2>
			</div>
			<div id="collapseFive" class="collapse show" aria-labelledby="headingFive" data-parent="#accordionExample">
				<div class="card-body">
					@include('modulos.perfil_trabajador.resumen_historial')
				</div>

			</div>
		</div>

		{{-- AUSENTISMOS --}}
		<div class="card">
			<div class="card-header bg-dark text-white cabecera_consultas_historial" id="headingFour">
				<h2 class="mb-0 d-flex">
					<button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
						data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
						<h6>Ausentismos ({{count($ausentismos)}})</h6>
					</button>
					<i style="cursor: pointer;" data-toggle="collapse" data-target="#collapseFour"
						class="fal fa-chevron-circle-down text-white"></i>
				</h2>
			</div>
			<div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
				<div class="card-body">
					@include('modulos.perfil_trabajador.ausentismos_historial_tabla')
				</div>

			</div>
		</div>

		{{-- MEDICAS --}}
		<div class="card">
			<div class="card-header bg-dark text-white cabecera_consultas_historial" id="headingOne">
				<h2 class="mb-0 d-flex">
					<button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
						data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
						<h6>Consultas médicas ({{count($consultas_medicas)}})</h6>
					</button>
					<i style="cursor: pointer;" data-toggle="collapse" data-target="#collapseOne"
						class="fal fa-chevron-circle-down text-white"></i>
				</h2>
			</div>

			<div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">

				<div class="card-body">

					@include('modulos.perfil_trabajador.consultas_medicas_tabla')

				</div>

			</div>
		</div>

		{{-- ENFERMERIA --}}
		<div class="card">
			<div class="card-header bg-dark text-white cabecera_consultas_historial" id="headingTwo">
				<h2 class="mb-0 d-flex">
					<button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
						data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
						<h6>Consultas Enfermería ({{ count($consultas_enfermeria) }})</h6>
					</button>
					<i style="cursor: pointer;" data-toggle="collapse" data-target="#collapseTwo"
						class="fal fa-chevron-circle-down text-white"></i>
				</h2>
			</div>
			<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">

				<div class="card-body">
					@include('modulos.perfil_trabajador.consultas_enfermeria_tabla')
				</div>

			</div>
		</div>

		{{-- EXAMENES --}}
		<div class="card">
			<div class="card-header bg-dark text-white cabecera_consultas_historial" id="headingThree">
				<h2 class="mb-0 d-flex">
					<button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
						data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						<h6>Exámemes médicos complementarios ({{ count($preocupacionales) }})</h6>
					</button>
					<i style="cursor: pointer;" data-toggle="collapse" data-target="#collapseThree"
						class="fal fa-chevron-circle-down text-white"></i>
				</h2>
			</div>
			<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">

				<div class="card-body">

					@include('modulos.perfil_trabajador.preocupacionales_tabla')

				</div>

			</div>
		</div>


	</div>

</div>