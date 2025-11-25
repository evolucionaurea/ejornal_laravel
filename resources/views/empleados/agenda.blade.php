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

			<hr class="hr-line-dashed">
			<div class="row">
				<div class="col-lg-3 form-group">
					<label for="">Filtrar turnos por usuario</label>
					<select name="usuarios" class="form-control form-control-sm">
						<option value="">--Todos los usuarios--</option>
						<option value="<?= auth()->user()->id ?>">Mis Turnos</option>
						@if($usuarios) 
							@foreach($usuarios as $usuario) 
								@if($usuario->id == auth()->user()->id)
									@continue
								@endif
								<option value="<?= $usuario->id ?>">{{ $usuario->nombre }}</option>
							@endforeach 
						@endif
					</select>
				</div>
				<div class="col-lg-3 form-group">
					<label for="">Filtrar turnos por trabajador</label>
					<select name="trabajadores" class="form-control form-control-sm">
						<option value="">--Todos los trabajadores--</option>
						@if($trabajadores) 
							@foreach($trabajadores as $trabajador)								
								<option value="<?= $trabajador->id ?>">{{ $trabajador->nombre }}</option>
							@endforeach 
						@endif
					</select>
				</div>
			</div>
		</div>

		<div class="d-flex flex-wrap">

			{{-- Próximos Turnos --}}
			<div class="col-md-12 col-lg-4">
				<div class="tarjeta m-0">

					<div class="tarjeta-header">						
						@if( (auth()->user()->fichada == 1 || !auth()->user()->fichar) )
						<button data-toggle="add-event" class="btn-ejornal btn-ejornal-base mb-2">
							<i class="fas fa-plus-circle"></i> <span>Nuevo Turno</span>
						</button>
						@else
						<div class="text-muted font-italic small mb-2">Debes fichar para registrar un nuevo turno</div>
						@endif
					</div>					
						
					<div class="tarjeta-header">
					</div>					

					<div class="tarjeta-body">
						<h4 class="my-2">Próximos Turnos</h4>
						<hr>

						<div data-content="next-events"></div>

					</div>

					<div class="tarjeta-footer">
						<i class="small text-muted">Se mostrarán los próximos 5 turnos a partir de la hora actual.</i>
					</div>
					
				</div>
			</div>

			{{-- Calendario --}}
			<div class="col-md-12 col-lg-8">
				<div class="tarjeta m-0">
					<div class="tarjeta-header">
						<h4 class="mb-1">Calendario</h4>
					</div>
					<div class="tarjeta-body">
						<div id="calendar"></div>

						<div class="text-muted font-italic small">Click en el turno para verlo. Puedes arrastrar el turno hacia otro casillero para cambiar el día.</div>

						<hr class="hr-line-dashed">

						<div class="mt-1">
							<div class="small"><b>Referencias Colores:</b></div>
							@if($estados) 
							<ul class="d-flex">
								@foreach($estados as $estado)
								<li class="d-block mr-1">
									<span class="badge" style="background-color:{{ $estado->color }};color:white">{{ $estado->nombre }}</span>
								</li>
								@endforeach
							</ul>
							@endif
						</div>
					</div>
				</div>
			</div>

		</div>

	</div>


</div>


@include('modulos.modal')

@endsection