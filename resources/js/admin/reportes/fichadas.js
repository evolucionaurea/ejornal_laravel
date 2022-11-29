import Tablas from '../../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/admin/reportes',
		get_path:'/fichadas_ajax',
		table:$('.tabla_reporte_fichadas'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),

		datatable_options:{
			//ordering:false,
			order:[[4,'desc']],
			columns:[
				{
					data:'user_nombre',
					name:'user_nombre'
				},
				{
					data:'cliente_nombre',
					name:'cliente_nombre'
				},
				{
					data:row=>row,
					name:'ingreso',
					render:v=>{
						return `${v.ingreso} al ${v.egreso==null ? '<i class="text-muted">[aún trabajando]</i>' : v.egreso}`
					}
				},
				{
					data:'tiempo_dedicado',
					name:'tiempo_dedicado',
					orderable:false,
					render:v=>{
						return v==null ? '<i class="text-muted">[aún trabajando]</i>' : v
					}
				},
				{
					data:'ip',
					name:'ip'
				}

			]
		},
		server_side:true

	})

})
