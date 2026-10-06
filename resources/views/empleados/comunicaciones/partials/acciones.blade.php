{{-- Botones Editar / Eliminar de una comunicación de ausentismo --}}
<div class="d-flex">
	<button type="button" data-toggle="modal" data-target="#editar_comunicacion_{{ $comunicacion->id }}"
		class="btn-ejornal btn-ejornal-dark">
		<i class="fas fa-pen fa-fw"></i> <span>Editar</span>
	</button>

	@if($puede_eliminar)
	<form action="{{ action('EmpleadosComunicacionesController@destroy', $comunicacion->id) }}" method="post"
		onsubmit="return confirm('¿Seguro deseas eliminar esta comunicación? También se eliminarán sus archivos.')">
		{{ csrf_field() }}
		<input type="hidden" name="_method" value="DELETE">
		<button type="submit" class="btn-ejornal btn-danger">
			<i class="fas fa-trash fa-fw"></i> <span>Eliminar</span>
		</button>
	</form>
	@endif
</div>
