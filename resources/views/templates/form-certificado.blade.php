<form data-form="certificado" action="{{ action('EmpleadosAusentismoDocumentacionController@store') }}" enctype="multipart/form-data" accept-charset="UTF-8" method="post">

	{{ csrf_field() }}
	<input type="hidden" class="form-control" name="id_ausentismo" value="">
	<input type="hidden" class="form-control" name="id" value="0">
	<input type="hidden" class="form-control" name="matricula_validada" value="0">

	<h5 class="modal-title" >Certificado</h5>
	<hr>

	<div class="row p-4">

		<div class="form-row">
			<div class="form-group col-md-6">
				<label>Institución <span style="color: red;">*</span></label>
				<input required name="institucion" type="text" class="form-control" placeholder="">
			</div>
			<div class="form-group col-md-6">
				<label>Médico <span style="color: red;">*</span></label>
				<input required name="medico" type="text" class="form-control" placeholder="">
			</div>
			<div class="form-group col-md-6">
				<label>Matrícula Provincial</label>
				<input name="matricula_provincial" type="text" class="form-control" placeholder="">
			</div>
			<div class="form-group col-md-6">
				<label class="d-flex align-items-center">
					Matrícula Nacional
					<i data-toggle="certificado-validar-icon" data-value="ok" style="color: green; margin-left: 5px;" class="fas fa-check-circle d-none"></i>
					<i data-toggle="certificado-validar-icon" data-value="fail" style="color: red; margin-left: 5px;" class="fas fa-times-circle d-none"></i>
				</label>
				<div class="d-flex">
					<input style="max-width: 200px; margin-right: 5px;" name="matricula_nacional" type="text" class="form-control" placeholder="">
					<button data-toggle="validar-matricula" type="button" class="btn-ejornal btn-ejornal-gris-claro" href="#">
						<i class="fas fa-user-check fa-fw"></i> <span>Validar</span>
					</button>
				</div>
			</div>
			<div class="form-group col-md-6">
				<label>Fecha documento <span style="color: red;">*</span></label>
				<input name="fecha_documento" required id="data_picker_gral" type="text" class="form-control" placeholder="[click para desplegar calendario]" readonly>
			</div>
			<div class="form-group col-md-6"></div>

			<div class="form-group col-md-6">
				<label>Diagnóstico <span style="color: red;">*</span></label>
				<textarea required name="diagnostico" class="form-control" rows="5"></textarea>
			</div>
			<div class="form-group col-md-6">
				<label>Observaciones</label>
				<textarea name="observaciones" class="form-control" rows="5"></textarea>
			</div>
		</div>

		<hr class="hr-line-dashed">

		<div class="table-responsive">
			<table data-table="certificaciones_archivos" class="table table-sm small w-100 table-bordered border">
				<thead>
					<tr class="bg-light">
						<th colspan="2">
							<label for="" class="mb-0">Adjuntar archivos <span style="color: red;">*</span></label>
							<span class="small text-muted font-italic">Puedes adjuntar más de 1 archivo</span>
						</th>
					</tr>
				</thead>
				<tbody></tbody>
				<tfoot>
					<tr class="bg-light">
						<th colspan="2">
							<button data-toggle="agregar-archivo-cert" class="btn btn-tiny btn-dark text-light" type="button">
								<i class="fal fa-plus fa-fw"></i> <span>Agregar archivo</span>
							</button>
						</th>
					</tr>
				</tfoot>
			</table>
		</div>


		<hr class="hr-line-dashed">

		<button class="btn-ejornal btn-ejornal-success">Guardar Certificado</button>

	</div>

</form>