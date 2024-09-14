import Tablas from '../classes/Tablas.js';

$(()=>{

	console.log('nominas.movimientos')


	new Tablas({
		controller:'/empleados/nominas',
		get_path:'/movimientos_search',
		table:$('[data-table="movimientos"]'),
		modulo_busqueda:$('[data-toggle="busqueda-filtros"]'),
		server_side:true,

		datatable_options:{
			ordering:false,
			dom:'<"table-spacer-top"l>t<"table-spacer-bottom"ip>',
			columns:[
				{
					data:'trabajador',
					name:'trabajador',
				},
				{
					data:'cliente',
					name:'cliente'
				},
				{
					data:'usuario',
					name:'usuario'
				},
				{
					data:'added',
					name:'added'
				}

			]
		}

	})

})