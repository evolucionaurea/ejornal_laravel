<div class="timeline">

	<div data-id="{{ $turno->id }}" class="timeline-card">
		<div class="content">
			<div class="datetime">{{ $turno->fecha_inicio_formatted }}</div>
			<div class="remaining">{{ $turno->tiempo_faltante }}</div>

			<div class="title">{{ $turno->trabajador->nombre }}</div>
			<div class="small">Motivo: {!! $turno->motivo ? $turno->motivo->nombre : '<span class="text-muted font-italic">[sin definir]</span>' !!}</div>
			<div class="author">Asignado a {{ $turno->user ? $turno->user->nombre : '[desconocido]' }} | Cargado por: {{ $turno->user_registra ? $turno->user_registra->nombre : '[desconocido]' }}</div>

			<div class="comments">{!! $turno->comentarios ?? '<span class="text-muted font-italic">[sin comentarios]</span>' !!}</div>
		</div>
		@if( (auth()->user()->fichada == 1 || !auth()->user()->fichar) &&
		auth()->user()->id_especialidad == 1 )
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
	
</div>