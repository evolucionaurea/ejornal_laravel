export default class Tablas {

	constructor(obj={}){
		this.first_render = true

		$.extend(this,obj)

		this.init()

	}

	set_filters(){

		if(!('modulo_busqueda' in this)) return true

		let filters = {}
		$.each(this.modulo_busqueda.find('[name]'),(k,v)=>{
			filters[$(v).attr('name')] = $(v).val()
		})

		return filters
	}

	filters_params(){
		const filters = this.set_filters()
		const query_string = Object.keys(filters).map(key=>{
			return `${encodeURIComponent(key)}=${encodeURIComponent(filters[key])}`
		}).join('&')

		return query_string
	}


	dt_server_side(){

		this.datatable_options.serverSide = true
		this.datatable_options.processing = true
		this.datatable_options.deferRender = true
		this.datatable_options.dom = '<"table-spacer-top"lf>t<"table-spacer-bottom"ip>'

		this.datatable_options.ajax = {
			url:`${this.controller}${this.get_path}`,
			type:'POST',
			data:d=>{
				d._token = csfr
				$.extend(d,this.set_filters())
				loading()
			}
		}
		datatable_options.fnDrawCallback = settings=>{
			loading({show:false})
			///console.log(settings.json)
		}

		$.extend(window.datatable_options,this.datatable_options)
		this.datatable_instance = this.table.DataTable(window.datatable_options);

		return true
	}


	get(filters={}){
		loading()
		return new Promise((resolve,reject)=>{
			axios.post(`${this.controller}${this.get_path}`,this.set_filters())
				.then(response=>resolve(response.data))
				.catch(error=>reject(error))
		})
	}
	delete(id){
		return new Promise((resolve,reject)=>{
			axios.post(`${this.controller}${this.delete_path}/${id}`,{
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
			this.datatable_instance.clear()
			this.datatable_instance.destroy()
		}

		data.results.map(v=>{
			let tr = this.render_row(v)
			if('fichada_user' in v && v.fichada_user==0) tr.find('.acciones_tabla').remove()
			tbody.append(tr)
		})


		this.table.append(tbody)
		if(!$.fn.dataTable.isDataTable(this.table)){
			this.first_render = false
			this.datatable_instance = this.table.DataTable(window.datatable_options);
		}


	}



	init(){


		/*Borrar*/
		this.table.on('click','[data-toggle="delete"]',btn=>{
			const id = $(btn.currentTarget).attr('data-id')

			SwalWarning.fire({
				title:'delete_message' in this ? this.delete_message : '¿Seguro deseas borrar este item?'
			})
			.then(async swal=>{
				if(swal.value){
					await this.delete(id)
					//let data = await this.get()
					//this.render_table(data)
					window.location.reload()

				}
			})
		})

		$.extend(window.datatable_options,this.datatable_options)


		if('server_side' in this && this.server_side==true){
			this.dt_server_side()
		}


		if('modulo_busqueda' in this){

			this.modulo_busqueda.find('[name="from"],[name="to"]').datepicker({
				dateFormat:'dd/mm/yy'
			})

			/*Mostrar Todo*/
			this.modulo_busqueda.find('[data-toggle="clear"]').click(btn=>{
				this.modulo_busqueda.find('[name]').val('')
				this.modulo_busqueda.find('[data-toggle="search"]').trigger('click')
			})


			/*Buscar*/
			this.modulo_busqueda.find('[data-toggle="search"]').click(async btn=>{

				if(!('server_side' in this)) {
					let response = await this.get()
					this.render_table(response)
				}else{
					this.datatable_instance.ajax.reload()
				}
			})

			/*Exportar*/
			this.modulo_busqueda.find('[data-toggle="export"]').click(async btn=>{
				const href = $(btn.currentTarget).attr('data-href')
				const filters = this.set_filters()
				const query_string = Object.keys(filters).map(key=>{
					return `${encodeURIComponent(key)}=${encodeURIComponent(filters[key])}`
				}).join('&')
				window.open(`${href}?${query_string}`,'_blank')
			})

			if(!('server_side' in this)) {
				this.modulo_busqueda.find('[data-toggle="search"]').trigger('click')
			}



			/*Inputs listeners*/
			/*this.modulo_busqueda.on('change','[name]',el=>{
				const value = $(el.currentTarget).val()
				console.log(value)
			})*/

			/* Intercepto el click de los botones y le agrego parametros a la url para mantener la búsqueda al volver */
			this.table.on('click','a',btn=>{
				btn.preventDefault()
				const url = $(btn.currentTarget).attr('href')
				window.location.href = `${url}?${this.filters_params()}`
			})

		}else{

			if(!('server_side' in this)) {
				this.get().then(response=>this.render_table(response))
			}

		}



	}


}