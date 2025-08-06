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
					render:v=>{
						if(v==null) return ''
						return `
							<div>${v.nombre}</div>
							<div class="small">DNI: ${v.dni}</div>
						`
					}
				},
				{
					data:'cliente',
					name:'cliente',
					render:v=>{
						if(v==null) return ''
						return v.nombre
					}
				},
				{
					data:'usuario',
					name:'usuario',
					render:v=>{
						if(v==null) return ''
						return v.nombre
					}
				},
				{
					data:'created_at',
					name:'created_at'
				}

			]
		}

	})

})