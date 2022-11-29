import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/grupos/ausentismos',
		get_path:'/busqueda'
		table:$('.tabla_ausentismos'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),

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
		server_side:true

	})

})
