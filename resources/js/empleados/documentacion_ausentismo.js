class Certificado {

	constructor(){

		Promise.all([
			get_template('/templates/tr_certificado_ausentismo'),
			get_template('/templates/tr_certificado_ausentismo_readonly')
		])
			.then(promise=>{
				this.modal = $('#modal_certificado')
				this.table_archivos_cert = this.modal.find('[data-table="certificaciones_archivos"]')
				this.tr_certificado_ausentismo = promise[0]
				this.tr_certificado_ausentismo_readonly = promise[1]
				this.init()
			})
	}

	validar_matricula(){
		// let matricula_nacional = $('.nro_matricula_nacional').val();
		// let url = '/empleados/documentaciones/validarMatricula';
		// let usuario = 'jrpichot';
		// let clave = 'JavierPichot00';
		// let nombre = 'Juan';
		// let apellido = 'Perez';
		// let codigo = '025158';
		// let nrodoc = '32105897';

		// axios.post(url, {
		//   usuario: usuario,
		//   clave: clave,
		//   nombre: nombre,
		//   apellido: apellido,
		//   codigo: codigo,
		//   nrodoc: nrodoc,
		//   matricula: matricula_nacional
		//   })
		//   .then(function (response) {
		//     console.log(response);
		//     $('.matricula_validada_hidden').val(1);
		//     $('.matricula_tilde').css('display', 'block');
		//     $('.matricula_cruz').css('display', 'none');
		//   })
		//   .catch(function (error) {
		//     $('.matricula_cruz').css('display', 'block');
		//     $('.matricula_tilde').css('display', 'none');
		//     $('.matricula_validada_hidden').val(0);
		//     console.log(error);
		//   });

	}

	reset_table(){
		this.table_archivos_cert.find('tbody tr').not(':first-of-type').remove()
		this.modal.find('[name]').not('[name="id_ausentismo"]').val('')
		this.modal.find('[data-toggle="certificado-validar-icon"][data-value="ok"]').addClass('d-none')
	}


	init(){

		console.log('modal.certificado')


		this.modal.on('click','[data-toggle="validar-matricula"]',btn=>{
			if(this.modal.find('[name="matricula_nacional"]').val()==''){
				Swal.fire({
					icon:'warning',
					title:'Debes agregar algún número de matrícula'
				})
				return false
			}
			this.modal.find('[data-toggle="certificado-validar-icon"][data-value="ok"]').removeClass('d-none')
			this.modal.find('[name="matricula_validada"]').val(1)
		})

		this.modal.find('[name="fecha_documento"]').datepicker()


		this.modal.on('hide.bs.modal',event=>{
			this.reset_table()
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
		})



		///editar
		$('[data-toggle="editar-certificado"]').click(async btn=>{
			const id = $(btn.currentTarget).attr('data-id')
			const response = await axios.get(`/empleados/documentaciones/find_ajax/${id}`)
			if(response.status!=200){
				Swal.fire({
					icon:'error',
					title:'No se pudo encontrar el certificado'
				})
				return false
			}
			const data = response.data
			console.log(data)

			$.each(data, (k,v)=>{
				this.modal.find(`[name="${k}"]`).val(v)
			})
			this.modal.find('[data-toggle="validar-matricula"]').trigger('click')

			this.table_archivos_cert.find('tbody').html('')

			if(data.archivos.length>0){

				data.archivos.map((archivo,k)=>{

					const tr = $(this.tr_certificado_ausentismo_readonly)
					tr.find('a').text(archivo.archivo).attr({
						href:`../documentacion_ausentismo/archivo/${archivo.ausentismo_documentacion_id}`
					})
					this.table_archivos_cert.find('tbody').append(tr)

				})
			}

			this.modal.modal('show')
		})

	}

}


new Certificado
