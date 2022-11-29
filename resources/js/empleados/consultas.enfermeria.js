import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/consultas/enfermeria',
		get_path:'/busqueda'
		table:$('.tabla_consultas_enfermeria'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		datatable_options:{order:[[ 1, "desc" ]]},

		render_row:consulta=>{
			return $(`
				<tr>
				<td>${consulta.nombre}</td>
				<td>${consulta.fecha}</td>
				<td>${consulta.derivacion_consulta}</td>

				<td class="acciones_tabla" scope="row">
					<a title="Ver" href="enfermeria/${consulta.id}">
						<i class="fas fa-eye"></i>
					</a>
				</td>
			</tr>`
			)
		}
	})

})