import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/consultas/nutricionales',
		get_path:'/busqueda',
		table:$('[data-table="consultas-nutricionales"]'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),

		server_side:true,

		datatable_options:{
			dom:'<"table-spacer-top"il>t<"table-spacer-bottom"ip>',
			order:[[4,'desc']],
			columns:[
				{
					className:'align-middle',
					name:'id',
					data:'id'
				},
				{
					className:'align-middle',
					name:'nombre',
					data:'trabajador',
					render:v=>{
						if(v==null) return '<span class="text-muted font-italic">[trabajador no encontrado]</span>'
						return `
							<div>${v.nombre}</div>
							<div class="small">DNI: ${v.dni}</div>
							`
					}
				},
				{
					className:'align-middle',
					name:'legajo',
					data:'trabajador',
					render:v=>{
						if(v.legajo==null) return '<span class="text-muted font-italic">[Legajo no encontrado]</span>'
						return `
							<div>${v.legajo}</div>
							`
					}
				},
				{
					className:'align-middle',
					name:'tipo',
					data:'tipo',
					orderable:false					
				},

				{
					className:'align-middle',
					name:'fecha_atencion',
					data:'fecha_atencion'
				},
				

				{
					className:'align-middle',
					name:'user',
					data:'user'
				},

				{
					className:'align-middle',
					name:'acciones',
					data:row=>row,
					className:'text-right',
					render:(v,type,row,meta)=>{
						if(meta.settings.json.fichada_user!=1 && meta.settings.json.fichar_user) return ''
						return `
						<div class="acciones_tabla justify-content-end">
							<a title="Ver" href="nutricionales/${v.id}">
								<i class="fas fa-eye"></i>
							</a>
						</div>`
					}
				}
			]
		},


	})

})