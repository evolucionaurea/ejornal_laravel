import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/grupos/ausentismos',
		get_path:'/busqueda',
		table:$('.tabla_ausentismos'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		server_side:true,
		datatable_options:{
			//ordering:false,
			order:[[5,'desc']],

			dom:'<"table-spacer-top"l>t<"table-spacer-bottom"ip>',

			columns:[
				{
					data:'id',
					name:'id'
				},
				{
					data:row=>row,
					name:'nominas.nombre',
					render:v=>{
						let output = `<div><b>${v.trabajador_nombre}</b></div>`

						output += `<div class="small">DNI: ${v.trabajador_dni ?? '<span class="small text-muted font-italic">[no cargado]</span>'}</div>`

						if(v.id_cliente != v.trabajador_cliente){
							output += `<span class="badge badge-dark">transferido</span>`
						}else{
							output += `<span class="badge badge-${v.trabajador_estado==1 ? 'success' : 'danger'}">${v.trabajador_estado==1 ? 'activo' : 'inactivo'}</span>`
						}

						return output
					}
				},
				{
					data:row=>row,
					name:'nominas.legajo',
					className:'align-middle',
					render:v=>{
						return v.trabajador_legajo==null ? '<span class="small text-muted font-italic">[no cargado]</span>' : v.trabajador_legajo
					}
				},
				{
					data:row=>row,
					name:'nominas.sector',
					render:v=>{
						return v.trabajador_sector==null ? '<span class="small text-muted font-italic">[no cargado]</span>' : v.trabajador_sector
					}
				},
				{
					data:'ausentismo_tipo',
					name:'ausentismo_tipo.nombre'
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
				/*{
					data:'fecha_regreso_trabajar',
					name:'fecha_regreso_trabajar',
					render:v=>{
						return v==null ? '[no cargada]' : v
					}
				},*/
				{
					data:'comentario_shortened',
					className:'align-middle small lh-sm font-italic',
					name:'comentario',
					width:200
				},

				{
					data:'dias_mes_actual',
					//orderable:false,
					className:'align-middle',
					name:'dias_mes_actual',
					render:v=>{
						return v>0 ? v : 0
					}
				},
				{
					data:'total_dias',
					name:'total_dias',
					//orderable:false,
					className:'align-middle',
					render:v=>{
						return v
					}
				},


				{
					data:row=>row,
					orderable:false,
					render:v=>{
						if(v.ausente===1 && v.incluir_indice===1) return '<span class="badge badge-danger">ausente</span>'
						if(v.ausente===1 && v.incluir_indice===0) return '<span class="badge badge-warning">vigente</span>'
						return ''
					}
				},
				{
					data:row=>row,
					orderable:false,
					render:v=>{
						return ''
					}
				}
			]
		}

	})

})
