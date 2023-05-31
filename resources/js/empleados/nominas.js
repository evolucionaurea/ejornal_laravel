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
					className:'align-middle'
				},
				{
					data:'email',
					name:'email',
					className:'align-middle'
				},
				{
					data:'telefono',
					name:'telefono',
					className:'align-middle'
				},
				{
					data:row=>row,
					name:'dni',
					className:'align-middle',
					render:v=>{
						return v.dni==null ? '[no cargado]' : v.dni
					}
				},
				{
					data:'estado',
					name:'estado',
					className:'align-middle',
					render:v=>{
						return `<span class="badge badge-${v==1 ? 'success' : 'danger'}">${v==1 ? 'activo' : 'inactivo'}</span>`
					}
				},
				{
					data:'sector',
					name:'sector',
					className:'align-middle'
				},

				{
					data:row=>row,
					name:'hoy',
					orderable:false,
					className:'align-middle',
					render:v=>{

						let label = ''
						let nombre = ''

						if(v.ausentismos.length==0) return ''

						let ausente = false
						v.ausentismos.map(ausentismo=>{

							if(ausentismo.fecha_regreso_trabajar==null) {
								ausente = true
								nombre = `<div class="small text-muted font-italic">${ausentismo.tipo.nombre}</div>`
							}else{
								const fecha_arr = ausentismo.fecha_regreso_trabajar.split('/')
								const fecha_regreso = new Date(fecha_arr[2],fecha_arr[1],fecha_arr[0],0,0,0)
								const ahora = new Date
								if(fecha_regreso>ahora){
									ausente = true
									nombre = `<div class="small text-muted font-italic">${ausentismo.tipo.nombre}</div>`
								}
							}

						})

						if(ausente){
							label = '<span class="badge badge-danger">ausente</span>'
						}

						return `${label} ${nombre}`
					}
				},

				{
					data:row=>row,
					name:'actions',
					orderable:false,
					className:'align-middle',
					render:(v,type,row,meta)=>{

						if(meta.settings.json.fichada_user!=1) return ''

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
