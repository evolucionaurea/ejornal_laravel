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

		<div class="d-flex flex-wrap">

			{{-- Próximos Turnos --}}
			<div class="col-md-12 col-lg-4">
				<div class="tarjeta m-0">

					<div class="tarjeta-header">						
						@if( (auth()->user()->fichada == 1 || !auth()->user()->fichar))
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

						@if($turnos)
						<div class="timeline">
							@foreach($turnos as $turno)
							<div data-id="{{ $turno->id }}" class="timeline-card">
								<div class="content">
									<div class="datetime">{{ $turno->fecha_inicio_formatted }}</div>
									<div class="remaining">{{ $turno->tiempo_faltante }}</div>

									<div class="title">{{ $turno->trabajador->nombre }}</div>
									<div class="small">Motivo: {!! $turno->motivo ? $turno->motivo->nombre : '<span class="text-muted font-italic">[sin definir]</span>' !!}</div>
									<div class="author">Asignado a {{ $turno->user ? $turno->user->nombre : '[desconocido]' }} | Cargado por: {{ $turno->user_registra ? $turno->user_registra->nombre : '[desconocido]' }}</div>

									<div class="comments">{!! $turno->comentarios ?? '<span class="text-muted font-italic">[sin comentarios]</span>' !!}</div>
								</div>
								@if( (auth()->user()->fichada == 1 || !auth()->user()->fichar) )
								<div class="actions">
									@if($turno->estado->referencia!='cancelled')
									<button data-toggle="editar-turno" title="Editar Turno" class="btn btn-primary">
										<i class="fa fa-pencil fa-fw"></i>
									</button>
									<button data-toggle="cancelar-turno" title="Cancelar Turno" class="btn btn-danger">
										<i class="fa fa-times fa-fw"></i>
									</button>
									@else 
									<span class="badge" style="background-color:{{$turno->estado->color}}">{{ $turno->estado->nombre }}</span>
									@endif
								</div>
								@endif
							</div>
							@endforeach
						</div>
						@endif
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
					</div>
				</div>
			</div>

		</div>

	</div>


</div>


@include('modulos.modal')

@endsection