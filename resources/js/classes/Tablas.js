export default class Tablas {

	constructor(obj={}){
		this.first_render = true

		$.extend(this,obj)

		this.typing_timer
		this.typing_interval = 3000

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
		if(('dom' in this.datatable_options)==false) this.datatable_options.dom = '<"table-spacer-top"ilf>t<"table-spacer-bottom"ip>'

		this.datatable_options.ajax = {
			url:`${this.controller}${this.get_path}`,
			type:'POST',
			data:d=>{
				d._token = csfr
				$.extend(d,this.set_filters())
				//console.log(d)
				loading()
			}
		}
		datatable_options.fnDrawCallback = settings=>{
			loading({show:false})
			///console.log(settings.json)
		}
		datatable_options.createdRow = (row,data,dataIndex)=>{
			$(row).attr('data-id',data.id)
		}
		/*datatable_options.createdRow = (row,data,dataIndex)=>{
			$(row).attr('data-id',data.id)
		}*/

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

		if(this.table.attr('id')==undefined){
			const rand_id = `table_${window.random(1111,9999)}`
			this.table.attr('id',rand_id)
		}
		this.table_id = this.table.attr('id')


		/*Borrar*/
		this.table.on('click','[data-toggle="delete"]',async btn=>{
			let tr = $(btn.currentTarget).closest('tr')
			if(tr.hasClass('child')){
				let tr_prev = $(btn.currentTarget).closest('tr').prev()
				if(tr_prev.hasClass('parent')) tr = tr_prev
			}
			const id = tr.attr('data-id')

			const swal_warn = await SwalWarning.fire({
				title:'delete_message' in this ? this.delete_message : '¿Seguro deseas borrar este item?'
			})
			if(!swal_warn.value) return false

			const response = await this.delete(id)
			window.location.reload()

		})
		$.extend(window.datatable_options,this.datatable_options)


		if('server_side' in this && this.server_side==true){

			/*$(`#${this.table_id} input`).on('keyup', input=>{
				// Reiniciar el temporizador
				clearTimeout(this.typing_timer)
				const query = $(input.currentTarget).val()

				// Configurar un nuevo temporizador
				this.typing_timer = setTimeout(function() {
					// Realizar la búsqueda después del tiempo de espera
					table.search(query).draw()
				}, this.typing_interval)
			})*/


			this.dt_server_side()
		}


		if('modulo_busqueda' in this){

			this.modulo_busqueda.find('[name="from"],[name="to"]').datepicker({
				dateFormat:'dd/mm/yy'
			})

			/*Mostrar Todo*/
			this.modulo_busqueda.find('[data-toggle="clear"]').click(btn=>{
				btn.preventDefault()
				this.modulo_busqueda.find('[name]').not('[data-toggle="no-reset"]').val('')
				this.modulo_busqueda.find('[data-toggle="search"]').trigger('click')
			})


			/*Buscar*/
			this.modulo_busqueda.find('[data-toggle="search"]').click(async btn=>{
				btn.preventDefault()

				if(!('server_side' in this)) {
					let response = await this.get()
					this.render_table(response)
				}else{
					this.datatable_instance.ajax.reload()
				}
			})
			this.modulo_busqueda.on('keyup','[name="search"]',input=>{
				if(input.keyCode==13) this.modulo_busqueda.find('[data-toggle="search"]').trigger('click')
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