
class CovidTesteos {

	constructor(){
		this.init()
	}

	get(filters={}){
		return new Promise((resolve,reject)=>{
			axios.post('/empleados/covid/testeos/busqueda',filters)
				.then(response=>resolve(response.data))
				.catch(error=>reject(error))
		})
	}

	render_table(data){

		console.log(data)

		this.table.find('tbody').html('')
		loading({show:false})
		if(data.testeos.length==0) return false

		if(!this.first_render){
			this.datatable.clear()
			this.datatable.destroy()
		}

		data.testeos.map(testeo=>{
			let tr = $(`
			<tr>
				<td>${testeo.nombre}</td>
				<td>${testeo.tipo}</td>
				<td>${testeo.fecha}</td>
				<td><span class="tag_ejornal tag_ejornal_${testeo.resultado=='negativo'?'danger':'success'}">${testeo.resultado}</span></td>
				<td>${testeo.laboratorio}</td>

				<td class="acciones_tabla" scope="row">
					<a title="Ver" href="testeos/${testeo.id}/edit">
						<i class="fas fa-pencil"></i>
					</a>

					<button data-toggle="delete" data-id="${testeo.id}" title="Eliminar" type="submit">
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
		this.table = $('.tabla_testeos_listado')
		this.modulo_busqueda = $('[data-toggle="busqueda-fecha"]')
		window.datatable_options.order = [[ 2, "desc" ]]

		this.modulo_busqueda.find('[name="fecha_inicio"],[name="fecha_final"]').datepicker({
			dateFormat:'dd/mm/yy'
		})

		/*Buscar*/
		this.modulo_busqueda.find('[data-toggle="search"]').click(async btn=>{
			loading()
			let testeos = await this.get({
				from:this.modulo_busqueda.find('[name="fecha_inicio"]').val(),
				to:this.modulo_busqueda.find('[name="fecha_final"]').val(),
				filtro:this.modulo_busqueda.find('[name="filtro"]').val()
			})
			this.render_table(testeos)
		}).trigger('click')

		/*Mostrar Todo*/
		this.modulo_busqueda.find('[data-toggle="clear"]').click(btn=>{
			this.modulo_busqueda.find('[name="fecha_inicio"],[name="fecha_final"]').val('')
			this.modulo_busqueda.find('[data-toggle="search"]').trigger('click')
		})

		this.table.on('click','[data-toggle="delete"]',btn=>{
			const id = $(btn.currentTarget).attr('data-id')

			SwalWarning.fire({
				title:'¿Seguro desea borrar este testeo?'
			})
			.then(async swal=>{
				if(swal.value){
					const response = await axios.post(`/empleados/covid/testeos/destroy/${id}`,{
						_method:'DELETE',
						_token:csfr
					})

					this.get().then(data=>this.render_table(data))

				}
			})
		})



	}

}

new CovidTesteos;