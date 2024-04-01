$(()=>{

	$('[name="fecha_inicio"]').datepicker({
		onSelect:(date,obj)=>{
			const minDate = new Date(obj.selectedYear,obj.selectedMonth,obj.selectedDay)
			$('[name="fecha_final"],[name="fecha_regreso_trabajar"]').datepicker('destroy')
			$('[name="fecha_final"],[name="fecha_regreso_trabajar"]').datepicker({
				minDate:minDate
			})
		}
	})
	$('[name="fecha_final"]').on('change',event=>{
		const value = $(event.currentTarget).val()
		$('[name="fecha_regreso_trabajar"]').val(value)
	})


	$('.select_2').select2()
	$('.select_2').trigger('change')

	const inst = $.datepicker._getInst($('[name="fecha_inicio"]')[0]);
	$.datepicker._get(inst, 'onSelect').apply(inst.input[0], [$('[name="fecha_inicio"]').datepicker('getDate'), inst]);


	$('.btn_editar_tipo_ausentismo').on('click', function(event) {
		let id_tipo = $(this).data("id")
		let tipo_actual = $(this).data("text")
		let color = $(this).data("color")
		let incluir_indice = $(this).data("indice")
		$('#editar_tipo_ausentismo [name="tipo_editado"]').val(tipo_actual)
		$('#editar_tipo_ausentismo [name="id_tipo"]').val(id_tipo)
		$('#editar_tipo_ausentismo [name="color"]').val(color)
		$('#editar_tipo_ausentismo [name="editar_incluir_indice"]').val(incluir_indice || 0)

	})


	//// Certificado
	$('[name="incluir_certificado"]').on('change',input=>{
		const checked = $(input.currentTarget).is(':checked')
		const required_fields = ['cert_institucion','cert_medico','cert_fecha_documento','cert_diagnostico']
		let required
		if(checked){
			required = true
			$('#certificado_content').slideDown()
		}else{
			required = false
			$('#certificado_content').slideUp()
		}

		required_fields.map(field=>{
			$(`[name="${field}"]`).prop({required:required})
		})
	})
	$('[data-toggle="incluir-certificado"]').click(btn=>{
		const wrapper = $(btn.currentTarget).closest('.input-group')
		const checkbox = wrapper.find('input')
		checkbox.prop({checked:!checkbox.prop('checked')}).trigger('change')
	})
	$('[data-toggle="validar-matricula"]').click(btn=>{
		if($('[name="cert_matricula_nacional"]').val()==''){
			Swal.fire({
				icon:'warning',
				title:'Debes agregar algún número de matrícula'
			})
			return false
		}
		$('[data-toggle="certificado-validar-icon"][data-value="ok"]').removeClass('d-none')
		$('[name="matricula_validada"]').val(1)
	})

	$('[data-toggle="agregar-archivo-cert"]').click(btn=>{
		const table = $('[data-table="certificaciones_archivos"]')
		const tr = `
		<tr>
			<td data-content="file">
				<div class="custom-file">
					<input name="cert_archivo[]" type="file" class="custom-file-input" required>
					<label class="custom-file-label">...</label>
				</div>
			</td>
			<td class="text-right">
				<button data-toggle="quitar-archivo-cert" class="btn btn-danger btn-tiny text-light" type="button">
					<i class="fa fa-times"></i>
				</button>
			</td>
		</tr>`
		table.find('tbody').append($(tr))
	})
	$('[data-table="certificaciones_archivos"]').on('click','tbody tr button[data-toggle="quitar-archivo-cert"]',btn=>{
		const tbody = $(btn.currentTarget).closest('tbody')
		const tr = $(btn.currentTarget).closest('tr')
		const indx = tr.index()
		if(indx == 0){
			Swal.fire({
				icon:'warning',
				title:'Debes subir al menos 1 archivo'
			})
			return false
		}

		tr.remove()
	})

	$('[name="cert_fecha_documento"]').datepicker()

})