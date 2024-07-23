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
			dom:'<"table-spacer-top"lf>t<"table-spacer-bottom"ip>',
			columns:[
				{
					data:'nombre',
					className:'align-middle',
					name:'nombre',
					width:180
				},
				{
					data:'tipo',
					className:'align-middle',
					name:'tipo',
					width:180
				},
				{
					data:'user',
					className:'align-middle',
					name:'user',
					orderable:false,
					width:180
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
						return `<span class="badge badge-${v==1?'success':'danger'}">${v==1?'Activo':'Inactivo'}</span>`
					}
				},
				{
					data:'descripcion',
					className:'align-middle small',
					name:'descripcion',
					orderable:false
				}
			]
		}

	})

})
