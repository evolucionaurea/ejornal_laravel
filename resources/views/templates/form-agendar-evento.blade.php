<form data-form="agregar-turno" class="text-left">


	<input type="hidden" name="id" >

	@csrf

	<h4 data-content="form-title">Title</h4>
	<div data-content="form-caption" class="text-muted font-italic small mb-3"></div>

	<div class="mb-3">

		<div class="row">

			<div class="col-lg-4">
				<label for="">Fecha</label>
				<input type="text" name="fecha_inicio" class="form-control" required readonly placeholder="Click para desplegar el calendario" required value="{{ date('d/m/Y') }}">
			</div>

			<div class="col-lg-4">
				<label for="">Horario</label>	
				<input type="time" name="horario" class="form-control" required value="{{ \Carbon\CarbonImmutable::now()->format('H').':'.\Carbon\CarbonImmutable::now()->format('i') }}">
			</div>			

			<div class="col-lg-4">
				<label for="">Duración</label>
				<select name="duracion" class="form-control" required>
					<option value="10">10 min.</option>
					<option value="15">15 min.</option>
					<option value="30">30 min.</option>
					<option value="45">45 min.</option>
					<option value="60">1 hora</option>
				</select>
			</div>

			<div class="col-lg-6">
				<label for="">Usuario</label>
				<select name="user_id" class="form-control" required>
					<option value="">--Seleccionar--</option>
					<option value="{{ auth()->user()->id }}" selected>{{ auth()->user()->nombre }} ({{auth()->user()->especialidad->nombre}})</option>
				</select>
			</div>

			<div class="col-lg-6">
				<label for="">Trabajador</label>
				<select name="nomina_id" class="form-control" required>
					<option value="">--Seleccionar--</option>
				</select>
			</div>

		</div>

	</div>


	<div class="mb-3">
		<label for="">Comentarios</label>
		<textarea name="comentarios" class="form-control" rows="6"></textarea>
	</div>


	<button class="btn btn-success">
		<i class="fa fa-save fa-fw"></i>
		<span>Guardar Turno</span>
	</button>


</form>