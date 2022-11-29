import Tablas from '../../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/admin/reportes',
		get_path:'/ausentismos_ajax',
		table:$('.tabla_reporte_ausentismos'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),

		datatable_options:{
			//ordering:false,
			order:[[4,'desc']],
			columns:[
				{
					data:'cliente_nombre',
					name:'cliente_nombre'
				},
				{
					data:'trabajador_nombre',
					name:'trabajador_nombre'
				},
				{
					data:'user',
					name:'user'
				},
				{
					data:'ausentismo_tipo_nombre',
					name:'ausentismo_tipo_nombre'
				},
				{
					data:'fecha_inicio',
					name:'fecha_inicio'
				},
				{
					data:'fecha_final',
					name:'fecha_final',
					render:v=>{
						return v==null ? '<i class="text-muted">[no cargada]</i>' : v
					}
				},
				{
					data:'dias_ausente',
					name:'dias_ausente'
				}

			]
		},
		server_side:true

	})

})
