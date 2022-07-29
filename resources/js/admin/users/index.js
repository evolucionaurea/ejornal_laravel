import Tablas from '../../empleados/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/admin/users',
		table:$('.tabla'),
		modulo_busqueda:$('[data-toggle="busqueda-filtros"]'),
		datatable_options:{order:false},
		delete_message:'¿Seguro deseas borrar este usuario?',
		render_row:user=>{
			return $(`
				<tr>
				<td>${user.nombre}</td>
				<td>${user.email}</td>
				<td>${user.especialidad!=null?user.especialidad:''}</td>
				<td><span class="tag_ejornal tag_ejornal_${user.estado==1?'success':'danger'}">${user.estado==1?'Activo':'Inactivo'}</span></td>
				<td>${user.rol}</td>

				<td class="acciones_tabla" scope="row">
					<a title="Ver" href="users/${user.id}/edit">
						<i class="fas fa-pencil"></i>
					</a>

					<button data-toggle="delete" data-id="${user.id}" title="Eliminar" type="submit">
						<i class="fas fa-trash"></i>
					</button>

				</td>
			</tr>`
			)
		}
	})

})