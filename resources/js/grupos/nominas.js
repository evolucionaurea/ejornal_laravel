import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/grupos/nominas',
		get_path:'/busqueda',
		table:$('.tabla_nominas'),
		modulo_busqueda:$('[data-toggle="busqueda-filtros"]'),
		server_side:true,
		datatable_options:{
			order:[[1,'asc']],

			dom:'<"table-spacer-top"l>t<"table-spacer-bottom"ip>',


			columns:[
				{
					data:row=>row,
					name:'foto',
					orderable:false,
					render:v=>{
						return `
							<div class="table-thumbnail">
								<div class="image" style="background-image:url(${v.thumb_url})"></div>
							</div>`
					}
				},
				{
					data:'nombre',
					name:'nombre'
				},
				{
					data:'email',
					name:'email'
				},
				{
					data:'telefono',
					name:'telefono'
				},
				{
					data:row=>row,
					name:'dni',
					render:v=>{
						return v.dni==null ? '[no cargado]' : v.dni
					}
				},
				{
					data:row=>row,
					name:'fecha_nacimiento',
					className:'align-middle border-left',
					render:v=>{
						if(v.fecha_nacimiento==null) return '<span class="text-muted font-italic">[no cargado]</span>'
						return `${v.fecha_nacimiento}<br><small>Edad: ${v.edad}</small>`
					}
				},
				{
					data:'estado',
					name:'estado',
					render:v=>{
						return `<span class="badge badge-${v==1 ? 'success' : 'danger'}">${v==1 ? 'activo' : 'inactivo'}</span>`
					}
				},
				{
					data:'sector',
					name:'sector'
				},

				{
					data:row=>row,
					name:'hoy',
					orderable:false,
					render:v=>{

						let label = ''
						let nombre = ''

						if(v.ausentismos.length==0) return ''

						let ausente = false
						v.ausentismos.map(ausentismo=>{

							if(ausentismo.ausente_hoy){
								ausente = true
								nombre = `<div class="small text-muted font-italic">${ausentismo.tipo.nombre}</div>`
							}

						})

						if(ausente){
							label = '<span class="badge badge-danger">ausente</span>'
						}

						return `${label} ${nombre}`
					}
				},

				{
					data:'created_at',
					name:'created_at',
					className:'align-middle'
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
