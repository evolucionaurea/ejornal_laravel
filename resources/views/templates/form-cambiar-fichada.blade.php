<form data-form="cambiar-fichada">
	<p>Empleado: <strong data-content="empleado"></strong></p>
	<p>Fecha registro actual: <strong data-content="current_date"></strong></p>
	{{-- <input type="hidden" name="old_date" > --}}
	<input type="hidden" name="id">
	<input type="hidden" name="action">

	<div class="container">
		<div class="row">
			<div class="col-lg-4 form-group">
				<label for="">Fecha</label>
				<input type="text" name="new_date" class="form-control" placeholder="fecha nueva..." required>
			</div>
			<div class="col-lg-4 form-group">
				<label for="">Hora</label>
				<select name="new_hour" class="form-control" required></select>
			</div>
			<div class="col-lg-4 form-group">
				<label for="">Minutos</label>
				<select name="new_minutes" class="form-control" required></select>
			</div>
		</div>

	</div>


	<hr>

	<button class="btn btn-info btn-sm">Guardar</button>
</form>