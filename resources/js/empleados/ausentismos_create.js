class Ausentismo {

	constructor(){

		get_template('/templates/tr_certificado_ausentismo')
			.then(template=>{
				this.table_archivos_cert = $('[data-table="certificaciones_archivos"]')
				this.tr_certificado_ausentismo = template
				this.init()
			})
	}


	init(){

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
		console.log('class: Ausentismo')


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
			const tr = $(this.tr_certificado_ausentismo)
			this.table_archivos_cert.find('tbody').append(tr)
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
		this.table_archivos_cert.on('change','input[type="file"]',event=>{
			event.preventDefault()
			const wrapper = $(event.currentTarget).closest('.custom-file')
			wrapper.find('label').text(event.target.files[0].name)
			console.log(event.target.files[0].name)
		})

		$('[name="cert_fecha_documento"]').datepicker()
	}

}

new Ausentismo