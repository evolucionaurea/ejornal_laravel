<div class="tarjeta">

	{{-- PRINCIPAL --}}
	<div class="row align-items-star">
		<!-- Primer <ul>: borde blanco suave -->
		<div class="p-2 col-lg-6" style="background-color: #ebebeb; color: #535353;">
			<h4 class="text-black p-2 mb-0">Información principal</h4>
			<ul style="list-style: none; border-radius: 10px; padding: 10px; width: 100%; gap: 10px; display: flex; flex-direction: column;">
				<li style="background: transparent; color: rgb(58, 58, 58);">
					<b>Activo:</b><br>
					@if ($trabajador->estado == 1)
					Sí
					@else
					No
					@endif
				</li>
				<li style="background: transparent; color: rgb(58, 58, 58);">
					<b>DNI / CUIL:</b><br>
					@if ($trabajador->dni !== null && $trabajador->dni !== '')
					<span>{{ $trabajador->dni }}</span>
					@else
					{{ 'No fue cargado' }}
					@endif
				</li>
				<li style="background: transparent; color: rgb(58, 58, 58);">
					<b>Email:</b><br>
					@if ($trabajador->email !== null && $trabajador->email !== '')
					<span>{{ $trabajador->email }}</span>
					@else
					{{ 'No fue cargado' }}
					@endif
				</li>
				<li style="background: transparent; color: rgb(58, 58, 58);">
					<b>Fecha Nacimiento:</b><br>
					{{ $trabajador->fecha_nacimiento ? $trabajador->fecha_nacimiento->format('d/m/Y') : '[no cargado]'
					}}
				</li>
				@if ($trabajador->fecha_nacimiento != null)
				<li style="background: transparent; color: rgb(58, 58, 58);">
					<b>Edad:</b><br>
					{{ \Carbon\Carbon::parse($trabajador->fecha_nacimiento)->age }} años
				</li>
				@endif
				<li style="background: transparent; color: rgb(58, 58, 58);">
					<b>Teléfono:</b><br>
					@if ($trabajador->telefono !== null && $trabajador->telefono !== '')
					<a href="tel:{{$trabajador->telefono}}" style="color: #45526e;">{{$trabajador->telefono}}</a>
					@else
					{{ 'No fue cargado' }}
					@endif
				</li>
			</ul>
		</div>

		<!-- Segundo <ul>: fondo azul oscuro y letras azul claro -->
		@if ($caratula)
		<div class="p-2 col-lg-6" style="background-color: #1a3b63; color: #8cb3ff;">
			<h4 class="text-white p-2 mb-0">Carátula</h4>
			<ul
				style="list-style: none; border-radius: 10px; padding: 10px; width: 100%; gap: 10px; display: flex; flex-direction: column;">
				<li style="background: transparent;">
					<b>Última Patología:</b><br>
					@if ($caratula->patologias && count($caratula->patologias) > 0)
					<ul class="list-group d-flex flex-wrap flex-row">
						@foreach ($caratula->patologias as $patologia)
						<li class="list-group-item" style="margin-right: 5px;">
							{{ $patologia->nombre }}
						</li>
						@endforeach
					</ul>
					@else
					{{ 'No fue cargado' }}
					@endif
				</li>
				<li style="background: transparent;">
					<b>Medicación habitual:</b><br>
					{{ $caratula->medicacion_habitual }}
				</li>
				<li style="background: transparent;">
					<b>Antecedentes:</b><br>
					{{ $caratula->antecedentes }}
				</li>
				<li style="background: transparent;">
					<b>Alergias:</b><br>
					{{ $caratula->alergias }}
				</li>
				<li style="background: transparent;">
					<b>Peso:</b><br>
					{{ $caratula->peso }}
				</li>
				<li style="background: transparent;">
					<b>Altura:</b><br>
					{{ $caratula->altura }}
				</li>
				<li style="background: transparent;">
					<b>IMC:</b><br>
					{{ $caratula->imc }}
				</li>
			</ul>
		</div>
		@else
		<div class="col-lg-6 p-4 alert-info">
			<h4>Carátula</h4>
			<p>Sin contenido cargado aún</p>
		</div>
		@endif
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
			<div id="collapseFive" class="collapse {{count($resumen_historial) ? 'show' : ''}}"
				aria-labelledby="headingFive" data-parent="#accordionExample">
				<div class="card-body table-responsive">
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
			<div id="collapseFour" class="collapse {{count($ausentismos) ? 'show' : ''}}" aria-labelledby="headingFour"
				data-parent="#accordionExample">
				<div class="card-body table-responsive">
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

			<div id="collapseOne" class="collapse {{count($consultas_medicas) ? 'show' : ''}}"
				aria-labelledby="headingOne" data-parent="#accordionExample">

				<div class="card-body table-responsive">

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
			<div id="collapseTwo" class="collapse {{count($consultas_enfermeria) ? 'show' : ''}}"
				aria-labelledby="headingTwo" data-parent="#accordionExample">

				<div class="card-body table-responsive">
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
			<div id="collapseThree" class="collapse {{count($preocupacionales) ? 'show' : ''}}"
				aria-labelledby="headingThree" data-parent="#accordionExample">

				<div class="card-body table-responsive">

					@include('modulos.perfil_trabajador.preocupacionales_tabla')

				</div>

			</div>
		</div>


	</div>

	<div class="accordion" id="accordionInfoInicial">
		{{-- Acorddeon Info Principal --}}
		<div class="card">
			<div class="card-header bg-dark text-white" id="headingInfoInicial">
				<h2 class="mb-0 d-flex">
					<button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
						data-target="#collapseInfoInicial" aria-expanded="false" aria-controls="collapseInfoInicial">
						<h6>Informacion adicional</h6>
					</button>
					<i style="cursor: pointer;" data-toggle="collapse" data-target="#collapseInfoInicial"
						class="fal fa-chevron-circle-down text-white"></i>
				</h2>
			</div>
			<div id="collapseInfoInicial" class="collapse ? 'show' : ''}}" aria-labelledby="headingInfoInicial"
				data-parent="#accordionInfoInicial">
				<div class="card-body table-responsive">
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

			</div>
		</div>

	</div>



</div>