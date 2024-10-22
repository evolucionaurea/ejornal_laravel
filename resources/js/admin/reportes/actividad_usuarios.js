import Tablas from '../../classes/Tablas.js';

$(()=>{


	new Tablas({
		controller:'/admin/reportes',
		get_path:'/search_actividad_usuarios',
		table:$('[data-table="actividades"]'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		server_side:true,
		responsive:true,

		datatable_options:{
			order:[[2,'desc']],
			columns:[

				{
					data:'user',
					name:'user'
				},
				{
					data:'cliente',
					name:'clientes.nombre',
					render:v=>{
						if(v==null) return ''
						return v.nombre
					}
				},
				{
					data:'created_at_formatted',
					name:'created_at'
				},
				{
					data:'actividad',
					name:'actividad'
				},
				{
					data:'trabajador',
					name:'trabajador.nombre',
					render:v=>{
						if(v==null) return ''
						return v.nombre
					}
				}

			]
		}
	})


	$('[data-toggle="busqueda-fecha"]').find('[name="from"],[name="to"]').datepicker()



	console.log('actividades.usuarios')
})