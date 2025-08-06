$(()=>{

	console.log('caratula.trabajador')
	

	$('#id_nomina').on('change',async select=>{
		let idNomina = $(select.currentTarget).val()
		if(idNomina=='' || idNomina==undefined) {
			$('#caratula').html('<p class="alert alert-info">Seleccione un trabajador de la nomina</p>')
			return false
		}
		const template = await window.get_template(`/api/get_caratula_nomina/${idNomina}`)
		$('#caratula').html(template)
	})

	$('body').on('click','[data-toggle="editar-caratula"]',async btn=>{
		btn.preventDefault()
		let idNomina = $('#id_nomina').val(); 
		let dominio = window.location.origin; 
		const template = await window.get_template(`${dominio}/api/get_caratula_modal/${idNomina}`)
		const $template = $(template)		
		$template.find('[name="patologia_edit_caratula[]"]').select2()
		$('body').append($template)
		$('#editarCaratulaModal').modal('show');
	})
	// Eliminar el modal al cerrarlo
	$('body').on('hidden.bs.modal', '#editarCaratulaModal', ()=>{
		$('#editarCaratulaModal').remove();
	});
	$('body').on('submit','[data-form="editar-caratula"]',async form=>{
		form.preventDefault() 
		const post = window.get_form(form.currentTarget)

		const response = await axios.post(`/api/actualizar_caratula`,post)
		console.log(response)
		if(!response.data.estado) return toastr.error(response.data.message)

		toastr.success(response.data.message)
		$('#editarCaratulaModal').modal('hide')

		const template = await get_template(`/api/get_caratula_nomina/${post.trabajador_id_edit_caratula}`)
		$('#caratula').html(template)

	})

	$('body').on('change keyup','input[name="peso_edit_caratula"], input[name="altura_edit_caratula"]', input=>{
		const peso = $("input[name='peso_edit_caratula']").val();
		const altura = $("input[name='altura_edit_caratula']").val();

		$("input[name='imc_edit_caratula']").val(window.calculate_imc(peso,altura));

	})

	$('body').on('click', '[data-toggle="usar-datos-caratula"]', btn=>{

		const peso = $('[data-content="peso"]').text()
		const altura = $('[data-content="altura"]').text()
		console.log(peso,altura)
			
		// Asignar peso y altura
		$('[name="peso"]').val(peso);
		$('[name="altura"]').val(altura);
		$("input[name='imc']").val(window.calculate_imc(peso,altura));
		$("input[name='imc_disabled']").val(window.calculate_imc(peso,altura))

	})

	

})