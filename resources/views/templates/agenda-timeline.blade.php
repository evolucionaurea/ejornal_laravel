@if($turnos)
	<div class="timeline">
		@foreach($turnos as $turno)
		<div data-id="{{ $turno->id }}" class="timeline-card">
			<div class="content">

				<div class="datetime">{{ $turno->fecha_inicio_formatted }}</div>
				<div class="remaining">{{ $turno->tiempo_faltante }}</div>

				<div class="title">{{ $turno->trabajador->nombre }}</div>

				<div class="small">Legajo: {!! $turno->trabajador->legajo ?: '<span class="text-muted font-italic">[no cargado]</span>' !!} | Motivo: {!! $turno->motivo ? $turno->motivo->nombre : '<span class="text-muted font-italic">[sin definir]</span>' !!}</div>
				<div class="author">Asignado a {{ $turno->user ? $turno->user->nombre : '[desconocido]' }} | Cargado por: {{ $turno->user_registra ? $turno->user_registra->nombre : '[desconocido]' }}</div>

				<div class="comments">{!! $turno->comentarios ?? '<span class="text-muted font-italic">[sin comentarios]</span>' !!}</div>
			</div>
			
			<div class="actions">
				<div>
					<span class="badge" style="background-color:{{$turno->estado->color}}">{{ $turno->estado->nombre }}</span>
				</div>

				@if( (auth()->user()->fichada == 1 || !auth()->user()->fichar) )
				@if($turno->estado->referencia!='cancelled')
				<button data-toggle="editar-turno" title="Editar Turno" class="btn btn-primary">
					<i class="fa fa-pencil fa-fw"></i>
				</button>
				<button data-toggle="cancelar-turno" title="Cancelar Turno" class="btn btn-danger">
					<i class="fa fa-times fa-fw"></i>
				</button>
				@endif
				@else 
				<div class="small-comment">[necesitás fichar para editar un turno]</div>
				@endif
			</div>
		</div>
		@endforeach
	</div>
	@else 
	<div class="small-comment">No hay turnos agendados</div>
	@endif