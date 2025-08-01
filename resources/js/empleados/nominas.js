import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/nominas',
		get_path:'/busqueda',
		delete_path:'/destroy',
		table:$('.tabla_nominas'),
		delete_message:'¿Seguro deseas borrar este empleado?',
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
								<div class="image" style="background-image:url(${v.photo_url})"></div>
							</div>`
					}
				},
				{
					data:row=>row,
					name:'nombre',
					className:'align-middle border-left',
					render:v=>{
						return `<a class="text-dark" href="${v.perfil_url}">${v.nombre}</a>`
					}
				},
				{
					data:'email',
					name:'email',
					className:'align-middle border-left'
				},
				{
					data:row=>row,
					name:'telefono',
					className:'align-middle border-left',
					render:v=>{
						return v.telefono==null ? '<span class="text-muted font-italic">[no cargado]</span>' : `<a class="text-dark" href="tel:${v.telefono}">${v.telefono}</a>`
					}
				},
				{
					data:row=>row,
					name:'legajo',
					className:'align-middle border-left',
					render:v=>{
						return v.legajo==null ? '<span class="text-muted font-italic">[no cargado]</span>' : v.legajo
					}
				},
				{
					data:row=>row,
					name:'ultima_caratula',
					className:'align-middle border-left text-center',
					orderable:false,
					render:v=>{
						return v.ultima_caratula ? '<span class="badge badge-success">si</span>' : '<span class="badge badge-danger">no</span>'
					}
				},
				{
					data:row=>row,
					name:'dni',
					className:'align-middle border-left',
					render:v=>{
						return v.dni==null ? '<span class="text-muted font-italic">[no cargado]</span>' : v.dni
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
					className:'align-middle border-left text-center',
					render:v=>{
						return `<span class="badge badge-${v==1 ? 'success' : 'danger'}">${v==1 ? 'activo' : 'inactivo'}</span>`
					}
				},
				{
					data:'sector',
					name:'sector',
					className:'align-middle border-left'
				},

				{
					data:row=>row,
					name:'hoy',
					orderable:false,
					className:'align-middle disable-ordering border-left',
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
					className:'align-middle border-left'
				},

				{
					data:row=>row,
					name:'actions',
					orderable:false,
					className:'align-middle border-left',
					render:(v,type,row,meta)=>{

						if(meta.settings.json.fichada_user!=1 && meta.settings.json.fichar_user) return ''

						return `
							<div class="acciones_tabla">

								<a title="Historial" href="nominas/${v.id}">
									<i class="fas fa-book"></i>
								</a>

								<a title="Editar" href="nominas/${v.id}/edit">
									<i class="fas fa-pencil"></i>
								</a>
								<button data-toggle="delete" data-id="${v.id}" title="Eliminar" type="submit">
									<i class="fas fa-trash"></i>
								</button>
							</div>
						`

					}
				}

			]
		}


	})

})
