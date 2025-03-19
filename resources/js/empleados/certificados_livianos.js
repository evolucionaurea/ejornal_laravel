import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/certificados_livianos',
		get_path:'/busqueda',
		table:$('.tabla_certificados_livianos_listado'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		datatable_options:{order:[[ 4, "desc" ]]},
		render_row:certificado=>{

			let trabajador = `<div>${certificado.nombre}</div>`
			if(certificado.tarea_liviana.trabajador.deleted_at==null){
				trabajador = `<div><a href="${certificado.tarea_liviana.trabajador.perfil_url}" target="_blank" class="text-dark">${certificado.tarea_liviana.trabajador.nombre}</a></div>`
			}else{
				trabajador += `<span class="badge badge-danger">eliminado</span>`
			}

			return $(`
				<tr data-id="${trabajador.id}" >
					<td>
						<div>${trabajador}</div>
						${(certificado.id_cliente != certificado.trabajador_cliente) ? `<span class="badge badge-dark">transferido</span>` : ''}
					</td>
					<td>${certificado.medico}</td>
					<td>${certificado.institucion}</td>
					<td>${certificado.fecha_inicio}</td>
					<td>${certificado.fecha_final}</td>
					<td>${certificado.fecha_regreso_trabajar==null ? 'no cargada' : certificado.fecha_regreso_trabajar}</td>
					<td>${certificado.matricula_nacional==null ? 'no cargada' : certificado.matricula_nacional}</td>
				</tr>`
			)
		}
	})

})
