<form action="" >

	@csrf

	<div class="mb-3">
		<label for="">Fecha</label>
		<input type="text" name="fecha_inicio" class="form-control"  readonly>
	</div>

	<div class="mb-3">
		<label for="">Horario</label>
		<div class="row">
			<div class="col-lg-6">
				<select name="hora" class="form-control">
					@for($i=0; $i<=23; $i++)
					<option value="{{ $i<10?'0'.$i }}">{{ $i<10?'0'.$i }}</option>
					@endfor
				</select>
			</div>
			<div class="col-lg-6">
				<select name="minuto" class="form-control">
					<option value="00">00 min.</option>
					<option value="15">15 min.</option>
					<option value="30">30 min.</option>
					<option value="45">45 min.</option>
				</select>
			</div>
		</div>

	</div>

	<div class="mb-3">
		<label for="">Duración</label>
		<select name="duracion" class="form-control">
			<option value="">10 min.</option>
			<option value="">15 min.</option>
			<option value="">30 min.</option>
			<option value="">45 min.</option>
			<option value="">1 hora</option>
		</select>
	</div>

	<div class="mb-3">
		<label for="">Comentarios</label>
		<textarea name="comentarios" class="form-control"></textarea>
	</div>


	<button class="btn btn-success">
		<i class="fa fa-save fa-fw"></i>
		<span>Guardar Turno</span>
	</button>


</form>