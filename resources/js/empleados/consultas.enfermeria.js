import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/consultas/enfermeria',
		get_path:'/busqueda',
		table:$('[data-table="consultas-enfermeria"]'),
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
					name:'estado',
					data:'trabajador',
					orderable:false,
					render:v=>{
						return `<span class="badge badge-${v.estado==1?'success':'danger'}">${v.estado==1?'activo':'inactivo'}</span>`
					}
				},

				{
					className:'align-middle',
					name:'fecha',
					data:'fecha'
				},

				{
					className:'align-middle',
					name:'derivacion_consulta',
					data:'derivacion_consulta'
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
					render:(v,type,row,meta)=>{
						if(meta.settings.json.fichada_user!=1 && meta.settings.json.fichar_user) return ''
						return `
						<div class="acciones_tabla">
							<a title="Ver" href="enfermeria/${v.id}">
								<i class="fas fa-eye"></i>
							</a>
						</div>`
					}
				}
			]
		},

	})

})