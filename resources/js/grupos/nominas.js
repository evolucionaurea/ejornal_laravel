import Tablas from '../Tablas.js';

$(()=>{

	new Tablas({
		controller:'/grupos/nominas',
		table:$('.tabla_nominas'),
		modulo_busqueda:$('[data-toggle="busqueda"]'),
		datatable_options:{
			order:[[0,'asc']],
			columns:[
				{data:'nombre'},
				{data:'email'},
				{data:'telefono'},
				{data:'dni'},
				{
					data:'estado',
					render:v=>{
						return `<span class="tag_ejornal tag_ejornal_${v==1?'success':'danger'}">${v==1?'Activo':'Inactivo'}</span>`
					}
				},
				{data:'sector'}
			]
		},
		server_side:true

		/*render_row:nomina=>{
			return $(`
				<tr>
					<td>${nomina.nombre==null ? 'no cargado' : nomina.nombre}</td>
					<td>${nomina.email==null ? 'no cargado' : nomina.email}</td>
					<td>${nomina.telefono==null ? 'no cargado' : nomina.telefono}</td>
					<td>${nomina.dni==null ? 'no cargado' : nomina.dni}</td>
					<td>
						<span class="tag_ejornal tag_ejornal_${nomina.estado==1?'success':'danger'}">${nomina.estado==1?'Activo':'Inactivo'}</span>
					</td>
					<td>${nomina.sector==null ? 'no cargado' : nomina.sector}</td>
				</tr>`
			)
		}*/
	})

})
