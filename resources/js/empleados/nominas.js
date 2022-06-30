import Tablas from './Tablas.js';

$(()=>{

	//window.datatable_options.order = false;
	//$('.tabla_user').dataTable(window.datatable_options);

	new Tablas({
		controller:'/empleados/nominas',
		table:$('.tabla_user'),
		datatable_options:{ordering:false},
		delete_message:'¿Seguro deseas borrar este empleado?',
		modulo_busqueda:$('[data-toggle="busqueda-filtros"]'),
		render_row:trabajador=>{
			//asset('storage/nominas/fotos/'.$trabajador->id.'/'.$trabajador->hash_foto)

			let ausentismo = ''
			if('hoy' in trabajador && trabajador.hoy.estado=='Ausente'){
				ausentismo = `
				<a href="ausentismos/${trabajador.hoy.id}/edit" title="Click para editar ausentismo">
					<span class="tag_ejornal tag_ejornal_danger">Ausente</span>
				</a>
				<div class="small mt-3 text-danger">${trabajador.hoy.tipo}</div>`
			}
			return `
				<tr>
					<td class="align-middle d-flex align-items-center">
						<div class="foto-user-tabla ${'foto' in trabajador && trabajador.foto!='' ? 'has-image' : '' }" style="background-image:url(${'foto' in trabajador && trabajador.foto!=null ? '/storage/nominas/fotos/'+trabajador.id+'/'+trabajador.hash_foto : ''})"></div>
						${trabajador.nombre}
					</td>
					<td>${trabajador.email}</td>
					<td>${trabajador.telefono}</td>
					<td>${trabajador.dni}</td>
					<td>
						<span class="tag_ejornal tag_ejornal_${trabajador.estado==1 ? 'success' : 'danger'}">${trabajador.estado==1 ? 'Activo' : 'Inactivo'}</span>
					</td>
					<td>${trabajador.sector}</td>
					<td>
						${ausentismo}
					</td>

					<td scope="row" class="align-middle">

						<div class="acciones_tabla">
							<a title="Historial" href="nominas/${trabajador.id}">
								<i class="fas fa-book"></i>
							</a>
							<a title="Editar" href="nominas/${trabajador.id}/edit">
								<i class="fas fa-pen"></i>
							</a>

							<button data-toggle="delete" data-id="${trabajador.id}" title="Eliminar" type="submit">
								<i class="fas fa-trash"></i>
							</button>
						</div>

					</td>

				</tr>`
		}
	})
})