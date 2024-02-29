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
					data:'nombre',
					name:'nombre',
					className:'align-middle border-left'
				},
				{
					data:'email',
					name:'email',
					className:'align-middle border-left'
				},
				{
					data:'telefono',
					name:'telefono',
					className:'align-middle border-left'
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
					className:'align-middle border-left',
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
						const ahora = new Date


						if(v.ausentismos.length==0) return ''

						let ausente = false

						v.ausentismos.map(ausentismo=>{

							if(ausentismo.ausente_hoy){
								ausente = true
								nombre = `<div class="small text-muted font-italic">${ausentismo.tipo.nombre}</div>`
							}
							/*if(ausentismo.fecha_final==null) {
								ausente = true
							}else{
								const fecha_arr = ausentismo.fecha_final.split('/')
								const fecha_final = new Date(parseInt(fecha_arr[2]),parseInt(fecha_arr[1])-1,parseInt(fecha_arr[0]),23,59,59)

								if(v.id==5098){
									console.log(ausentismo)
								}

								if(fecha_final>=ahora){
									ausente = true
									nombre = `<div class="small text-muted font-italic">${ausentismo.tipo.nombre}</div>`
								}
							}*/

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

						if(meta.settings.json.fichada_user!=1 && meta.settings.json.fichar_user!=0) return ''

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
