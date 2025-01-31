@extends('partials.layout')

@section('title', 'Empleado')

@section('content')


<div class="d-flex" id="wrapper">

	@include('partials.sidebar_empleados')

	<div id="page-content-wrapper">

		@include('partials.nav_sup')


		<div class="cabecera">
			<h2>Agenda</h2>
			<p>Tu agenda de turnos</p>
		</div>


		<div class="row">
			<div class="col-lg-4">
				<div class="tarjeta">
					<div class="tarjeta-header">
						<h4>Próximos Turnos</h4>
					</div>
					<div class="tarjeta-body">
						xx
					</div>
					<div class="tarjeta-footer">

						@if( (auth()->user()->fichada == 1 || !auth()->user()->fichar) && auth()->user()->id_especialidad == 1 )
						<button data-toggle="add-event" class="btn-ejornal btn-ejornal-base" >
							<i class="fas fa-plus-circle"></i> <span>Nuevo Turno</span>
						</button>
						@else
						<div class="text-muted font-italic small">Debes fichar para registrar un nuevo turno</div>
						@endif

					</div>
				</div>
			</div>

			<div class="col-lg-8">
				<div class="tarjeta">
					<div class="tarjeta-header">
						<h4>Calendario</h4>
					</div>
					<div class="tarjeta-body">
						<div id="calendar"></div>
					</div>
				</div>
			</div>

		</div>

	</div>


</div>

@endsection