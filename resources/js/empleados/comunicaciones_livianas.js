import Tablas from '../classes/Tablas.js';

$(()=>{
	new Tablas({
		controller:'/empleados/comunicaciones_livianas',
		get_path:'/busqueda',
		table:$('.tabla_comunicaciones_livianas_listado'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		datatable_options:{order:[[ 3, "desc" ]]},
		render_row:comunicacion=>{

			// Formatear la fecha
			/* const fecha = new Date(comunicacion.created_at);
			 const dia = fecha.getDate().toString().padStart(2, '0');
			 const mes = (fecha.getMonth() + 1).toString().padStart(2, '0'); // Los meses van de 0 a 11
			 const anio = fecha.getFullYear();*/

			let trabajador = `<div>${comunicacion.nombre}</div>`
			let status = `<span class="tag_ejornal tag_ejornal_${comunicacion.estado==1?'success':'danger'}">${comunicacion.estado==1?'Activo':'Inactivo'}</span>`

			if(comunicacion.id_cliente != comunicacion.trabajador_cliente) status = `<span class="badge badge-dark">transferido</span>`

			if(comunicacion.tarea_liviana.trabajador.deleted_at==null){
				trabajador = `<div><a href="${comunicacion.tarea_liviana.trabajador.perfil_url}" target="_blank" class="text-dark">${comunicacion.tarea_liviana.trabajador.nombre}</a></div>`
			}else{
				trabajador += `<span class="badge badge-danger">eliminado</span>`
				status = ''
			}
			return $(`
			<tr data-id="${trabajador.id}">
				<td>
					${trabajador}
				</td>
				<td>${(comunicacion.email == null) ? 'No fue cargado' : comunicacion.email}</td>
				<td>${comunicacion.tipo}</td>
				<td>
					${comunicacion.created_at_formatted}
				</td>
				<td>
					${status}
				</td>
			</tr>`
			)
		}
	})

})
