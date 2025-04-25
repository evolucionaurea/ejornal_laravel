class Certificado {

	constructor(){

		Promise.all([
			get_template('/templates/tr-certificado-ausentismo'),
			get_template('/templates/tr-certificado-ausentismo-readonly'),
			get_template('/templates/form-certificado')
		])
			.then(promise=>{
				this.popup = $('#popups')
				this.table = $('[data-table="certificados"]')
				this.table_archivos_cert = '[data-table="certificaciones_archivos"]'

				this.tr_certificado_ausentismo = promise[0]
				this.tr_certificado_ausentismo_readonly = promise[1]
				this.form_certificado = promise[2]

				this.init()
			})
	}

	pop_certificado(data=false){

		const $form = $(this.form_certificado)
		$form.find(this.table_archivos_cert).find('tbody').html('')

		if(data){

			$.each(data, (k,v)=>{
				$form.find(`[name="${k}"]`).val(v)
			})

			if(data.matricula_nacional!=null) $form.find('[data-toggle="validar-matricula"]').trigger('click')

			if(data.archivos.length>0){

				data.archivos.map((archivo,k)=>{

					const tr = $(this.tr_certificado_ausentismo_readonly)
					tr.find('a').text(archivo.archivo).attr({
						href:`../documentacion_ausentismo/archivo/${archivo.ausentismo_documentacion_id}`
					})
					$form.find(this.table_archivos_cert).find('tbody').append(tr)

				})
			}

		}else{
			$form.find('[name="id_ausentismo"]').val($('[data-toggle="crear-certificado"]').attr('data-ausenciaid'))
		}

		$form.find('[name="fecha_documento"]').datepicker()

		this.popup.find('.modal-body').html($form)
		this.popup.find('.modal-dialog').addClass('modal-lg')
		this.popup.modal('show')

		if(!data) this.popup.find('[data-toggle="agregar-archivo-cert"]').trigger('click')
	}


	init(){

		console.log('certificados')


		this.popup.on('click','[data-toggle="validar-matricula"]',btn=>{
			if(this.popup.find('[name="matricula_nacional"]').val()==''){
				Swal.fire({
					icon:'warning',
					title:'Debes agregar algún número de matrícula'
				})
				return false
			}
			this.popup.find('[data-toggle="certificado-validar-icon"][data-value="ok"]').removeClass('d-none')
			this.popup.find('[name="matricula_validada"]').val(1)
		})


		this.popup.on('click','[data-toggle="agregar-archivo-cert"]',btn=>{
			const tr = $(this.tr_certificado_ausentismo)
			console.log(tr)
			this.popup.find(this.table_archivos_cert).find('tbody').append(tr)
		})
		this.popup.on('click','button[data-toggle="quitar-archivo"]',btn=>{
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
		this.popup.on('change',`${this.table_archivos_cert} input[type="file"]`,event=>{
			event.preventDefault()
			const wrapper = $(event.currentTarget).closest('.custom-file')
			wrapper.find('label').text(event.target.files[0].name)
		})



		///editar
		this.table.on('click','[data-toggle="editar-certificado"]',async btn=>{
			const tr = $(btn.currentTarget).closest('tr')
			const id = tr.attr('data-id')
			const response = await axios.get(`/empleados/documentaciones/find_ajax/${id}`)
			if(response.status!=200){
				Swal.fire({
					icon:'error',
					title:'No se pudo encontrar el certificado'
				})
				return false
			}
			this.pop_certificado(response.data)

		})
		///new
		$('[data-toggle="crear-certificado"]').click(btn=>{
			const id_ausentismo = $(btn.currentTarget).attr('data-ausenciaid')
			this.pop_certificado()
		})



	}

}

export default Certificado