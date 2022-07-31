import Tablas from '../Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/covid/testeos',
		table:$('.tabla_testeos_listado'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		datatable_options:{order:[[ 2, "desc" ]]},
		delete_message:'¿Seguro deseas borrar este testeo?',
		render_row:testeo=>{
			return $(`
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
			</tr>`
			)
		}
	})

})