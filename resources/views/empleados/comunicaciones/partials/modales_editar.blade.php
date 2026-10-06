{{-- Modales para editar las comunicaciones de un ausentismo --}}
@foreach($comunicaciones as $comunicacion)
<div class="modal fade" id="editar_comunicacion_{{ $comunicacion->id }}" tabindex="-1"
	aria-labelledby="editar_comunicacion_{{ $comunicacion->id }}_titulo" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editar_comunicacion_{{ $comunicacion->id }}_titulo">Editar Comunicación</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="row p-4">
					<form style="width: 100%;" action="{{ action('EmpleadosComunicacionesController@update', $comunicacion->id) }}"
						enctype="multipart/form-data" accept-charset="UTF-8" method="post">
						{{ csrf_field() }}
						<input type="hidden" name="_method" value="PUT">
						<div class="form-row">
							<div class="form-group col-md-8">
								<label>Tipo de comunicación</label>
								<select class="form-control" name="id_tipo" required>
									@foreach ($tipo_comunicaciones as $tipo)
									<option value="{{$tipo->id}}" {{ $comunicacion->id_tipo==$tipo->id ? 'selected' : '' }}>{{$tipo->nombre}}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group col-md-4">
								<label>Fecha</label>
								<input data-toggle="fecha-comunicacion" name="fecha" type="text" class="form-control" required
									autocomplete="off" value="{{ $comunicacion->fecha ? $comunicacion->fecha->format('d/m/Y') : '' }}">
							</div>
							<div class="form-group col-md-12">
								<label>Descripción</label>
								<textarea required name="descripcion" class="form-control" rows="3">{{ $comunicacion->descripcion }}</textarea>
							</div>

							@if($comunicacion->archivos->isNotEmpty())
							<div class="form-group col-md-12">
								<label>Archivos adjuntos</label>
								<ul class="list-group">
									@foreach($comunicacion->archivos as $archivo)
									<li class="list-group-item p-2 small d-flex justify-content-between align-items-center">
										<a href="{{ route('comunicaciones.verArchivo', ['id' => $archivo->id_comunicacion, 'hash' => $archivo->hash_archivo]) }}" target="_blank" class="text-info">{{ $archivo->archivo }}</a>
										<label class="mb-0 text-danger">
											<input type="checkbox" name="archivos_eliminar[]" value="{{ $archivo->id }}"> Quitar
										</label>
									</li>
									@endforeach
								</ul>
							</div>
							@endif

							<div class="form-group col-md-12">
								<label>Agregar archivos</label>
								<input type="file" multiple name="archivos[]" accept=".jpeg,.png,.jpg,.gif,.pdf,.doc,.docx,.xls,.xlsx">
							</div>
						</div>
						<button class="btn-ejornal btn-ejornal-success" type="submit" name="button">Guardar cambios</button>
					</form>
				</div>

			</div>

		</div>
	</div>
</div>
@endforeach

<script>
	$(() => {
		$('[data-toggle="fecha-comunicacion"]').datepicker()
	})
</script>
