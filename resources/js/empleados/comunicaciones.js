import Tablas from '../classes/Tablas.js';

$(()=>{
	new Tablas({
		controller:'/empleados/comunicaciones',
		get_path:'/busqueda',
		table:$('.tabla_comunicaciones_listado'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		server_side:true,
		datatable_options: {
			order: [[3, "desc"]],
			dom:'<"table-spacer-top"l>t<"table-spacer-bottom"ip>',
			columns:[
				{
					data:'nombre',
					className:'align-middle',
					name:'nombre'
				},
				{
					data:'email',
					className:'align-middle',
					name:'email'
				},
				{
					data:'tipo',
					className:'align-middle',
					name:'tipo'
				},
				{
					data:'created_at',
					className:'align-middle',
					name:'created_at'
				},
				{
					data:'estado',
					className:'align-middle',
					name:'estado',
					render:v=>{
						return `<span class="tag_ejornal tag_ejornal_${v==1?'success':'danger'}">${v==1?'Activo':'Inactivo'}</span>`
					}
				}
			]
		}

	})

})
