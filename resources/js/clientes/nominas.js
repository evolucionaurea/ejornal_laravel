import Tablas from '../empleados/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/clientes/nominas',
		table:$('.tabla_nominas'),
		modulo_busqueda:$('[data-toggle="busqueda"]'),
		datatable_options:{order:[[0,'desc']]},

		render_row:nomina=>{
			return $(`
				<tr>
					<td>${nomina.nombre}</td>
					<td>${nomina.email}</td>
					<td>${nomina.telefono}</td>
					<td>${nomina.dni}</td>
					<td>
						<span class="tag_ejornal tag_ejornal_${nomina.estado==1?'success':'danger'}">${nomina.estado==1?'Activo':'Inactivo'}</span>
					</td>
					<td>${nomina.sector}</td>
				</tr>`
			)
		}
	})

})
