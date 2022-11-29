import Tablas from '../classes/Tablas.js';

$(()=>{


	new Tablas({
		controller:'/admin/medicamentos',
		get_path:'/busqueda',
		delete_path:'/destroy',
		table:$('.tabla'),
		modulo_busqueda:$('[data-toggle="busqueda-filtros"]'),
		datatable_options:{order:[[0,'asc']]},
		delete_message:'¿Seguro deseas borrar este medicamento?',
		render_row:medicamento=>{

			return $(`
				<tr>
				<td>${medicamento.nombre}</td>
				<td class="text-center">${medicamento.stock_total==0 ? '[sin stock]' : medicamento.stock_total}</td>
				<td class="text-center">${medicamento.suministrados_total}</td>


				<td class="acciones_tabla" scope="row">
					<a title="Ver" href="medicamentos/${medicamento.id}/edit">
						<i class="fas fa-pencil"></i>
					</a>

					<button data-toggle="delete" data-id="${medicamento.id}" title="Eliminar" type="submit">
						<i class="fas fa-trash"></i>
					</button>

				</td>
			</tr>`
			)
		}
	})

})