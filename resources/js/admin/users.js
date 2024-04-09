import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/admin/users',
		get_path:'/busqueda',
		delete_path:'/destroy',
		table:$('.tabla'),
		modulo_busqueda:$('[data-toggle="busqueda-filtros"]'),
		//datatable_options:{order:false},
		delete_message:'¿Seguro deseas borrar este usuario?',
		render_row:user=>{

			let clientes = [];
			let grupo = '';

			user.clientes_user.map(cliente=>{
				clientes.push(cliente.nombre)
			})

			if(user.rol=='empleado' && user.clientes_user.length==0)
			{
				clientes = ['[sin cliente asignado]']
			}
			if(user.rol=='cliente' && 'cliente_relacionar' in user)
			{
				if(user.cliente_relacionar!=null) clientes = [user.cliente_relacionar.nombre]
			}
			/*if(user.rol=='cliente'){

				clientes = '[clientes]';
			}*/

			if(user.grupo!=null) grupo = user.grupo.nombre

			return $(`
				<tr>
				<td>${user.nombre}</td>
				<td>${user.email}</td>
				<td>${user.especialidad!=null?user.especialidad:''}</td>
				<td><span class="badge badge-${user.estado==1?'success':'danger'}">${user.estado==1?'Activo':'Inactivo'}</span></td>
				<td>
					<span class="badge badge-${(user.fichar==1)?'success':'danger'}">${(user.fichar==1) ? 'Si' : 'No'}</span>
				</td>
				<td style="width:420px">
					<div><b>${user.rol.capitalize()}</b></div>
					<div class="small text-muted font-italic" style="line-height:1.15">${clientes.join(', ')}</div>
					<div class="small text-muted font-italic">${grupo}</div>
				</td>

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

