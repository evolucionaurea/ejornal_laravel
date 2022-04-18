class ConsultasEnfermeria {

	constructor(){
		this.init()
	}


	get(filters={}){
		return new Promise((resolve,reject)=>{
			axios.post('/empleados/consultas/enfermeria/busqueda',filters)
				.then(response=>resolve(response.data))
				.catch(error=>reject(error))
		})
	}
	render_table(data){

		this.table.find('tbody').html('')
		loading({show:false})
		if(data.consultas.length==0) return false

		if(!this.first_render){
			this.datatable.clear()
			this.datatable.destroy()
		}

		data.consultas.map(consulta=>{
			let tr = $(`
			<tr>
				<td>${consulta.nombre}</td>
				<td>${consulta.fecha}</td>
				<td>${consulta.derivacion_consulta}</td>

				<td class="acciones_tabla" scope="row">
					<a title="Ver" href="enfermeria/${consulta.id}">
						<i class="fas fa-eye"></i>
					</a>
				</td>
			</tr>`)
			if(data.fichada==0) tr.find('td:last-of-type').remove()
			this.table.find('tbody').append(tr)
		})


		if(this.first_render){
			this.datatable = this.table.DataTable(window.datatable_options);
			this.first_render = false
		}else{
			this.datatable = this.table.DataTable(window.datatable_options);
		}


	}



	init(){

		this.first_render = true
		this.table = $('.tabla_consultas_enfermeria')
		this.modulo_busqueda = $('[data-toggle="busqueda-fecha"]')
		window.datatable_options.order = [[ 1, "desc" ]]


		this.modulo_busqueda.find('[name="fecha_inicio"],[name="fecha_final"]').datepicker({
			dateFormat:'dd/mm/yy'
		})
		this.modulo_busqueda.find('[data-toggle="search"]').click(async btn=>{
			loading()
			let consultas = await this.get({
				from:this.modulo_busqueda.find('[name="fecha_inicio"]').val(),
				to:this.modulo_busqueda.find('[name="fecha_final"]').val()
			})
			this.render_table(consultas)
		})

		this.modulo_busqueda.find('[data-toggle="clear"]').click(btn=>{
			this.modulo_busqueda.find('[name="fecha_inicio"],[name="fecha_final"]').val('')
			this.modulo_busqueda.find('[data-toggle="search"]').trigger('click')
		})

		if(this.modulo_busqueda.find('[name="filtro"]').val()!=''){
			this.modulo_busqueda.find('[data-toggle="search"]').trigger('click')
		}else{
			this.get().then(data=>this.render_table(data))
		}



	}
}
new ConsultasEnfermeria;
