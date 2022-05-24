export default class Tablas {

	constructor(obj={}){
		this.first_render = true

		$.extend(this,obj)
		$.extend(window.datatable_options,obj.datatable_options)

		this.modulo_busqueda.find('[name="from"],[name="to"]').datepicker({
			dateFormat:'dd/mm/yy'
		})

		/*Buscar*/
		this.modulo_busqueda.find('[data-toggle="search"]').click(async btn=>{
			loading()

			let post = {}
			$.each(this.modulo_busqueda.find('[name]'),(k,v)=>{
				post[$(v).attr('name')] = $(v).val()
			})

			let response = await this.get(post)
			this.render_table(response)
		}).trigger('click')

		/*Mostrar Todo*/
		this.modulo_busqueda.find('[data-toggle="clear"]').click(btn=>{
			this.modulo_busqueda.find('[name]').val('')
			this.modulo_busqueda.find('[data-toggle="search"]').trigger('click')
		})


		/*Borrar*/
		this.table.on('click','[data-toggle="delete"]',btn=>{
			const id = $(btn.currentTarget).attr('data-id')

			SwalWarning.fire({
				title:'delete_message' in this ? this.delete_message : '¿Seguro deseas borrar este item?'
			})
			.then(async swal=>{
				if(swal.value){
					await this.delete(id)
					let data = await this.get()
					this.render_table(data)

				}
			})
		})

	}


	get(filters={}){
		return new Promise((resolve,reject)=>{
			axios.post(`${this.controller}/busqueda`,filters)
				.then(response=>resolve(response.data))
				.catch(error=>reject(error))
		})
	}
	delete(id){
		return new Promise((resolve,reject)=>{
			axios.post(`${this.controller}/destroy/${id}`,{
				_method:'DELETE',
				_token:csfr
			})
				.then(response=>resolve(response.data))
				.catch(error=>reject(error))
		})
	}
	render_table(data){

		//console.log(data)

		this.table.find('tbody').html('')
		loading({show:false})

		if(data.results.length==0) return false

		if(!this.first_render){
			this.datatable.clear()
			this.datatable.destroy()
		}

		data.results.map(v=>{
			let tr = this.render_row(v)
			if(v.fichada==0) tr.find('td.acciones_tabla').remove()
			this.table.find('tbody').append(tr)
		})

		if(this.first_render){
			this.datatable = this.table.DataTable(window.datatable_options);
			this.first_render = false
		}else{
			this.datatable = this.table.DataTable(window.datatable_options);
		}


	}


}