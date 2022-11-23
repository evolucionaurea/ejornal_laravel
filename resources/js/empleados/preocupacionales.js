import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/preocupacionales',
		table:$('.tabla_preocupacionales_listado'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		datatable_options:{order:[[ 3, "desc" ]]},
		delete_message:'¿Seguro deseas borrar este estudio médico?',
		render_row:preocupacional=>{
			return $(`
				<tr>
					<td>${preocupacional.nombre}</td>
					<td>${preocupacional.email==null ? 'no cargado' : preocupacional.email}</td>
					<td>${preocupacional.telefono==null ? 'no cargado' : preocupacional.telefono}</td>
					<td>${preocupacional.fecha}</td>
					<td>
						<a class="btn-ejornal btn-ejornal-gris-claro" href="preocupacionales/archivo/${preocupacional.id}">
							<i class="fa fa-file"></i>${preocupacional.archivo}
						</a>
					</td>

					<td class="acciones_tabla" scope="row">

						<a title="Editar" href="preocupacionales/${preocupacional.id}/edit">
							<i class="fas fa-pen"></i>
						</a>

						<button data-toggle="delete" data-id="${preocupacional.id}" title="Eliminar" type="submit">
							<i class="fas fa-trash"></i>
						</button>

					</td>

				</tr>`
			)
		}
	})

})
