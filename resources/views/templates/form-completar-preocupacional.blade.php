<form data-form="completar-preocupacional">

	<input type="hidden" name="id">

	<h4>Marcar completado y dejar un comentario</h4>

	<div class="form-group">
		<textarea name="comentarios" class="form-control" placeholder="Ingresa un comentario" required rows="8"></textarea>
	</div>

	<div class="custom-control custom-checkbox">
		<input id="renovar_estudio" type="checkbox" class="custom-control-input" checked>
		<label for="renovar_estudio" class="custom-control-label">¿Renovar Estudio?</label>
	</div>
	<div class="small text-muted font-italic">Al marcar esta opción se abrirá la ventana para cargar el mismo estudio.</div>

	<hr>

	<button class="btn btn-ejornal-success">Guardar</button>

</form>