<div class="modal fade" id="editarCaratulaModal" tabindex="-1" role="dialog" aria-labelledby="editarCaratulaLabel" aria-hidden="true">

	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		
			<div class="modal-header">
				<h5 class="modal-title" id="editarCaratulaLabel">Actualizar Carátula</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<form data-form="editar-caratula">
					<input type="hidden" name="cliente_id_edit_caratula" value="{{$caratula->cliente->id}}">
					
					<div class="row">						
						<div class="col-md-6">
							<label for="trabajador">Trabajador</label>
							<input type="text" name="trabajador_edit_caratula" class="form-control" value="{{$caratula->nomina->nombre}}" disabled>
							<input type="hidden" name="trabajador_id_edit_caratula" value="{{$caratula->nomina->id}}">
						</div>
						<div class="col-md-6">
							<label for="patologia">
							Patología
							</label>
							<select name="patologia_edit_caratula[]" class="form-control" multiple required>
								<!-- Itera sobre las patologías y crea las opciones -->
								@foreach($patologias as $patologia)
								<option value="{{ $patologia->id }}" {{ in_array($patologia->id,$caratula->patologias->pluck('id')->toArray()) ? 'selected' : '' }} >{{ $patologia->nombre }}</option>
								@endforeach
							
							</select>
							<input type="hidden" name="patologia_id_edit_caratula" value="{{$caratula->patologias->pluck('id')->join(',')}}">
						</div>
					</div>
					
					<div class="row">

						<div class="col-md-4">
							<label for="peso">Peso (kg)</label>
							<input type="number" name="peso_edit_caratula" class="form-control" value="{{$caratula->peso}}" step="0.1" min="1" required>
						</div>
						<div class="col-md-4">
							<label for="altura">Altura (cm)</label>
							<input type="number" name="altura_edit_caratula" class="form-control" value="{{$caratula->altura}}" required>
						</div>
						<div class="col-md-4">
							<label for="imc">IMC</label>
							<input type="text" name="imc_edit_caratula" class="form-control" disabled value="{{$caratula->imc}}">
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<label for="antecedentes">Antecedentes</label>
							<textarea name="antecedentes_edit_caratula" class="form-control">{{$caratula->antecedentes}}</textarea>
						</div>
						<div class="col-md-12">
							<label for="alergias">Alergias</label>
							<textarea name="alergias_edit_caratula" class="form-control">{{$caratula->alergias}}</textarea>
						</div>
						<div class="col-md-12">
							<label for="medicacion_habitual">Medicación Habitual</label>
							<textarea name="medicacion_habitual_edit_caratula" class="form-control">{{$caratula->medicacion_habitual}}</textarea>
						</div>
					</div>

					<hr>


					<div class="row">
						<div class="col-12">
							<button type="button" class="btn-ejornal btn-ejornal-gris-claro ml-4" data-dismiss="modal">Cancelar</button>
							<button class="btn-ejornal btn-ejornal-base">Guardar cambios</button>
						</div>
					</div>

				</form>
			</div>

			
			
		</div>
	</div>
</div>