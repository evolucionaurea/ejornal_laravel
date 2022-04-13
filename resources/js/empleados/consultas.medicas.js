$(()=>{

	//$('.tabla_consultas_medicas').dataTable(window.datatable_options);



	axios.post('/empleados/consultas/medicas/busqueda')
		.then(response=>{

			$('.tabla_consultas_medicas tbody').html('')

			if(response.data.length==0) return false

			response.data.map(consulta=>{
				let tr = `
				<tr>
					<td>${consulta.nombre}</td>
					<td>${consulta.fecha}</td>
					<td>${consulta.derivacion_consulta}</td>
					<td class="acciones_tabla" scope="row">
						<a title="Ver" href="#">
							<i class="fas fa-eye"></i>
						</a>
					</td>
				</tr>`
				$('.tabla_consultas_medicas tbody').append(tr)
			})

			$('.tabla_consultas_medicas').dataTable(window.datatable_options);
		})

		/*.then(response=>{
			$('.tabla_consultas_medicas tbody').html('')
			if(response.data.length==0) return false

			response.data.map(consulta=>{
				//let row
				//console.log(consulta)
			})

		})*/

})