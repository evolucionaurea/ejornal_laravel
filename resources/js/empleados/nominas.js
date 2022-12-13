import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/nominas',
		get_path:'/busqueda',
		delete_path:'/destroy',
		table:$('.tabla_user'),
		delete_message:'¿Seguro deseas borrar este empleado?',
		modulo_busqueda:$('[data-toggle="busqueda-filtros"]'),
		server_side:true,
		datatable_options:{
			columns:[
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
						return ''

						/*if(v.fecha_regreso_trabajar == null){
							return '[Ausente]'
						}else{
							let str = v.fecha_regreso_trabajar;
							let [dia, mes, anio] = str.split('/');
							let regreso_trabajar = new Date(+anio, mes - 1, +dia);
							let hoy = new Date();
							return regreso_trabajar > hoy  ? '[Ausente]' : ''
						}*/
					}
				},

				{
					data:row=>row,
					name:'actions',
					orderable:false,
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



		/*render_row:trabajador=>{

			let ausentismo = ''
			if('hoy' in trabajador && trabajador.hoy.estado=='Ausente'){
				ausentismo = `
				<a href="ausentismos/${trabajador.hoy.id}/edit" title="Click para editar ausentismo">
					<span class="tag_ejornal tag_ejornal_danger">Ausente</span>
				</a>
				<div class="small mt-3 text-danger">${trabajador.hoy.tipo}</div>`
			}
			return `
				<tr>
					<td class="d-flex align-items-center">
						<div class="foto-user-tabla ${'foto' in trabajador && trabajador.foto!='' ? 'has-image' : '' }" style="background-image:url(${'foto' in trabajador && trabajador.foto!=null ? '/storage/nominas/fotos/'+trabajador.id+'/'+trabajador.hash_foto : ''})"></div>
						${trabajador.nombre}
					</td>
					<td class="align-middle">${trabajador.email==null ? 'no cargado' : trabajador.email}</td>
					<td class="align-middle">
					${trabajador.telefono==null ? 'no cargado' : trabajador.telefono}
					</td>
					<td class="align-middle">${trabajador.dni==null ? 'no cargado' : trabajador.dni}</td>
					<td class="align-middle">
						<span class="tag_ejornal tag_ejornal_${trabajador.estado==1 ? 'success' : 'danger'}">${trabajador.estado==1 ? 'Activo' : 'Inactivo'}</span>
					</td>
					<td class="align-middle">${trabajador.sector==null ? 'no cargado' : trabajador.sector}</td>
					<td class="align-middle">
						${ausentismo}
					</td>

					<td scope="row" class="d-flex align-items-center">

						<div class="acciones_tabla">
							<a title="Historial" href="nominas/${trabajador.id}">
								<i class="fas fa-book"></i>
							</a>
							<a title="Editar" href="nominas/${trabajador.id}/edit">
								<i class="fas fa-pen"></i>
							</a>

							<button data-toggle="delete" data-id="${trabajador.id}" title="Eliminar" type="submit">
								<i class="fas fa-trash"></i>
							</button>
						</div>

					</td>

				</tr>`
		}*/
	})
})
