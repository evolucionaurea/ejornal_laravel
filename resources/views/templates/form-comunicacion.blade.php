<form action="{{action('EmpleadosComunicacionesController@store')}}" enctype="multipart/form-data" accept-charset="UTF-8" method="post">
	
	{{ csrf_field() }}

	<h3>Cargar Comunicación</h3>

	<hr class="hr-line-dashed">	
	
	<input type="hidden" name="id_ausentismo" value="">

	<div class="row">
		<div class="col-lg-5">
			<div class="font-weight-bold">Datos del Ausentismo</div>
			<ul class="list-grouop">
				<li class="list-group-item p-2 small">
					<span class="small-comment">Tipo:</span> 
					<span data-content="ausentismo_tipo"></span>
				</li>
				<li class="list-group-item p-2 small">
					<span class="small-comment">Fecha Inicio:</span> 
					<span data-content="fecha_inicio"></span>
				</li>
				<li class="list-group-item p-2 small">
					<span class="small-comment">Fecha Final:</span> 
					<span data-content="fecha_final"></span>
				</li>
				<li class="list-group-item p-2 small">
					<span class="small-comment">Total Días:</span> 
					<span data-content="total_dias"></span>
				</li>
				<li class="list-group-item p-2 small">
					<span class="small-comment">Usuario que Registró:</span> 
					<span data-content="user"></span>
				</li>
			</ul>
		</div>
		<div class="col-lg-7 border-left">
			<div class="form-row">
				<div class="form-group col-md-12">
					<label>Tipo de comunicación</label>
					<select class="form-control" name="id_tipo" required>
						<option value="">--Seleccionar--</option>
					</select>
				</div>
				<div class="form-group col-md-12">
					<label>Descripción</label>
					<textarea required name="descripcion" class="form-control" rows="5"></textarea>
				</div>
				<div class="form-group col-md-12">
					<label>Archivos</label>
					<input type="file" multiple name="archivos[]" id="">
				</div>
			</div>
			<button class="btn-ejornal btn-ejornal-success" type="submit" name="button">Crear comunicación</button>
		</div>
	</div>


</form>