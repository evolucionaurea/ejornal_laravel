
class CovidVacunas {

	constructor(){
		this.init()
	}

	get(filters={}){
		return new Promise((resolve,reject)=>{
			axios.post('/empleados/covid/vacunas/busqueda',filters)
				.then(response=>resolve(response.data))
				.catch(error=>reject(error))
		})
	}

	render_table(data){

		this.table.find('tbody').html('')
		loading({show:false})
		if(data.vacunas.length==0) return false

		if(!this.first_render){
			this.datatable.clear()
			this.datatable.destroy()
		}

		data.vacunas.map(vacuna=>{
			let tr = $(`
			<tr>
				<td>${vacuna.nombre}</td>
				<td>${vacuna.tipo}</td>
				<td>${vacuna.fecha}</td>

				<td>${vacuna.institucion}</td>

				<td class="acciones_tabla" scope="row">
					<a title="Ver" href="vacunas/${vacuna.id}/edit">
						<i class="fas fa-pencil"></i>
					</a>

					<button data-toggle="delete" data-id="${vacuna.id}" title="Eliminar" type="submit">
						<i class="fas fa-trash"></i>
					</button>

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
		this.table = $('.tabla_vacunas_listado')
		this.modulo_busqueda = $('[data-toggle="busqueda-fecha"]')
		window.datatable_options.order = [[ 2, "desc" ]]

		this.modulo_busqueda.find('[name="fecha_inicio"],[name="fecha_final"]').datepicker({
			dateFormat:'dd/mm/yy'
		})

		/*Buscar*/
		this.modulo_busqueda.find('[data-toggle="search"]').click(async btn=>{
			loading()
			let vacunas = await this.get({
				from:this.modulo_busqueda.find('[name="fecha_inicio"]').val(),
				to:this.modulo_busqueda.find('[name="fecha_final"]').val(),
				filtro:this.modulo_busqueda.find('[name="filtro"]').val()
			})
			this.render_table(vacunas)
		}).trigger('click')

		/*Mostrar Todo*/
		this.modulo_busqueda.find('[data-toggle="clear"]').click(btn=>{
			this.modulo_busqueda.find('[name="fecha_inicio"],[name="fecha_final"]').val('')
			this.modulo_busqueda.find('[data-toggle="search"]').trigger('click')
		})

		this.table.on('click','[data-toggle="delete"]',btn=>{
			const id = $(btn.currentTarget).attr('data-id')

			SwalWarning.fire({
				title:'¿Seguro desea borrar este vacuna?'
			})
			.then(async swal=>{
				if(swal.value){
					const response = await axios.post(`/empleados/covid/vacunas/destroy/${id}`,{
						_method:'DELETE',
						_token:csfr
					})

					this.get().then(data=>this.render_table(data))

				}
			})
		})



	}

}

new CovidVacunas;