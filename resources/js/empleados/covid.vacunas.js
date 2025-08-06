import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/covid/vacunas',
		get_path:'/busqueda',
		delete_path:'/destroy',
		table:$('.tabla_vacunas_listado'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		datatable_options:{order:[[ 2, "desc" ]]},
		delete_message:'¿Seguro deseas borrar esta vacuna?',
		render_row:vacuna=>{
			return $(`
			<tr data-id="${vacuna.id}">
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
			</tr>`
			)
		}
	})

})