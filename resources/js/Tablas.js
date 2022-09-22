export default class Tablas {

	constructor(obj={}){
		this.first_render = true

		$.extend(this,obj)
		$.extend(window.datatable_options,obj.datatable_options)

		if('modulo_busqueda' in this){
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
		}else{
			loading()
			this.get()
				.then(response=>this.render_table(response))
		}



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


		this.table.find('tbody').remove()
		let tbody = dom('tbody')
		loading({show:false})

		if(!data.results || data.results.length==0) return false

		if($.fn.dataTable.isDataTable(this.table)){
			this.datatable.clear()
			this.datatable.destroy()
		}

		data.results.map(v=>{
			let tr = this.render_row(v)
			if('fichada_user' in v && v.fichada_user==0) tr.find('.acciones_tabla').remove()
			tbody.append(tr)
		})


		this.table.append(tbody)
		if(!$.fn.dataTable.isDataTable(this.table)){
			this.first_render = false
			this.datatable = this.table.DataTable(window.datatable_options);
		}


	}


}