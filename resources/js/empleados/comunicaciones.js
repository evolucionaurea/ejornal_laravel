import Tablas from '../Tablas.js';

$(()=>{
	new Tablas({
		controller:'/empleados/comunicaciones',
		table:$('.tabla_comunicaciones_listado'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		datatable_options:{order:[[ 3, "desc" ]]},
		render_row:comunicacion=>{
			return $(`
				<tr>
					<td>${comunicacion.nombre}</td>
					<td>${(comunicacion.email == null) ? 'No fue cargado' : comunicacion.email}</td>
					<td>${comunicacion.tipo}</td>
					<td>
						${comunicacion.created_at}
					</td>
					<td>
						<span class="tag_ejornal tag_ejornal_${comunicacion.estado==1?'success':'danger'}">${comunicacion.estado==1?'Activo':'Inactivo'}</span>
					</td>
				</tr>`
			)
		}
	})

})
