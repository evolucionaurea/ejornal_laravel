import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/clientes/ausentismos',
		get_path:'/busqueda',
		table:$('.tabla_ausentismos'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		server_side:true,
		datatable_options:{
			//ordering:false,
			order:[[0,'asc']],
			columns:[
				{
					data:row=>row,
					name:'nominas.nombre',
					render:v=>{
						return v.trabajador_nombre
					}
				},
				{
					data:row=>row,
					name:'ausentismo_tipo',
					render:v=>{
						return v.ausentismo_tipo
					}
				},
				{
					data:'fecha_inicio',
					name:'fecha_inicio'
				},
				{
					data:'fecha_final',
					name:'fecha_final',
					render:v=>{
						return v==null ? '[no cargada]' : v
					}
				},
				{
					data:'fecha_regreso_trabajar',
					name:'fecha_regreso_trabajar',
					render:v=>{
						return v==null ? '[no cargada]' : v
					}
				}
			]
		},
		/*datatable_options:{order:false},
		render_row:ausentismo=>{
			///console.log(ausentismo)
			return $(`
				<tr>
					<td>${ausentismo.nombre}</td>
					<td>${ausentismo.nombre_ausentismo}</td>
					<td>${ausentismo.fecha_inicio}</td>
					<td>${ausentismo.fecha_final}</td>
					<td>${ausentismo.fecha_regreso_trabajar==null ? '[no cargada]' : ausentismo.fecha_regreso_trabajar}</td>
				</tr>`
			)
		}*/
	})

})
