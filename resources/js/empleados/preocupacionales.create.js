class Preocupacional {

	constructor(){

		get_template('/templates/tr-certificado-ausentismo')
			.then(template=>{
				this.table_archivos = $('[data-table="archivos"]')
				this.archivo_row = template
				this.init()
			})

	}

	init(){

		$('[data-toggle="select2"]').select2()

		$('[name="fecha"],[name="fecha_vencimiento"]').datepicker()


		$('[name="tiene_vencimiento"]').change(select=>{
			const value = $(select.currentTarget).val()

			if(value=='1'){
				$('[data-toggle="vencimiento"]').removeClass('d-none')
				$('[name="fecha_vencimiento"]').attr({required:true})
			}else{
				$('[data-toggle="vencimiento"]').addClass('d-none')
				$('[name="fecha_vencimiento"]').attr({required:false})
			}
		})
		$('[name="completado"]').change(select=>{
			const value = $(select.currentTarget).val()

			if(value=='1'){
				$('[name="completado_comentarios"]').attr({required:true,disabled:false})
			}else{
				$('[name="completado_comentarios"]').attr({required:false,disabled:true})
			}
		})


		/// ARCHIVOS
		$('[data-toggle="agregar-archivo"]').click(btn=>{
			const tr = $(this.archivo_row)
			this.table_archivos.find('tbody').append(tr)
		})
		this.table_archivos.on('click','tbody tr button[data-toggle="quitar-archivo"]',btn=>{
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
		this.table_archivos.on('change','input[type="file"]',event=>{
			event.preventDefault()
			const wrapper = $(event.currentTarget).closest('.custom-file')
			wrapper.find('label').text(event.target.files[0].name)
		})

	}

}

new Preocupacional