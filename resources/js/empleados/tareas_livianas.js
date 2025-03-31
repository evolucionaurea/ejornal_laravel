import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/tareas_livianas',
		get_path:'/busqueda',
		delete_path:'/destroy',
		table:$('.tabla_tareas_livianas'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		//datatable_options:{order:false},
		delete_message:'¿Seguro deseas borrar esta tarea liviana?',

		datatable_options:{
			dom:'<"table-spacer-top"l>t<"table-spacer-bottom"ip>',
			columns:[
				{
					data:null,
					name:'nominas.nombre',
					render:v=>{

						if(v.trabajador.deleted_at != null){
							return `
								<div>${v.trabajador.nombre}</div>
								<span class="badge badge-danger">eliminado</span>
							`
						}

						let output = `
						<div>
							<a href="${v.trabajador_perfil_url}" target="_blank" class="text-dark">${v.trabajador.nombre}</a>
						</div>`
						if(v.id_cliente != v.trabajador_cliente){
							output += `<span class="badge badge-dark">transferido</span>`
						}else{
							output += `<span class="badge badge-${v.trabajador.estado==1 ? 'success' : 'danger'}">${v.trabajador.estado==1 ? 'activo' : 'inactivo'}</span>`
						}
						return output

					}
				},
				{
					data:row=>row,
					name:'nominas.dni',
					render:v=>{
						return v.dni==null ? '[no cargado]' : v.dni
					}
				},
				{
					data:row=>row,
					name:'nominas.sector',
					render:v=>{
						return v.sector==null ? '[no cargado]' : v.sector
					}
				},
				{
					data:'nombre_tarea_liviana',
					name:'tarea_liviana_tipo.nombre'
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
				{
					data:'fecha_regreso_trabajar',
					name:'fecha_regreso_trabajar',
					render:v=>{
						return v==null ? '[no cargada]' : v
					}
				},
				{
					data:row=>row,
					orderable:false,
					render:v=>{

						if(v.fecha_final == null){
							return '[Ausente]'
						}else{
							let str = v.fecha_final;
							let [dia, mes, anio] = str.split('/');
							let regreso_trabajar = new Date(+anio, mes - 1, +dia);
							let hoy = new Date();
							return regreso_trabajar > hoy  ? '<span class="badge badge-success">vigente</span>' : ''
						}
					}
				},

				{
					data:row=>row,
					name:'actions',
					orderable:false,
					render:(v,type,row,meta)=>{

						if(meta.settings.json.fichada_user!=1 && meta.settings.json.fichar_user) return ''

						let regreso_trabajar = null;
						let hoy;
						let mostrar_extension;

						if (v.fecha_final == null) {
							regreso_trabajar = null;
							mostrar_extension = true;
						} else {
							let str = v.fecha_final;
							let [dia, mes, anio] = str.split('/');
							regreso_trabajar = new Date(+anio, mes - 1, +dia);
							hoy = new Date();
							hoy.setHours(0, 0, 0, 0);

							// Calcula la fecha actual más 72 horas
							let limiteSuperior = new Date(hoy.getTime() + (72 * 60 * 60 * 1000));

							if (regreso_trabajar >= hoy && regreso_trabajar < limiteSuperior) {
								mostrar_extension = true;
							} else {
								mostrar_extension = false;
							}
						}

						return `
							<div class="acciones_tabla">
								${
									(mostrar_extension == true)
									?
									`
									<button
										class="extension_de_licencia_adecuada"
										title="extension de licencia adecuada"
										data-toggle="modal"
										data-target="#extensionLicenciaAdecuadaModal"
										data-info="${v.id}"
									>
										<i title="extension de licencia" class="fas fa-forward"></i>
									</button>
									`
									:
									''
								}
								<a title="Comunicaciones" href="comunicaciones_livianas/${v.id}">
									<i title="Comunicaciones" class="fas fa-bullhorn"></i>
								</a>

								<a title="Certificados" href="documentaciones_livianas/${v.id}">
									<i title="Certificados" class="fas fa-files-medical"></i>
								</a>

								<a title="Historial" href="tareas_livianas/${v.id_trabajador}">
									<i title="Historial" class="fas fa-book"></i>
								</a>

								${
									v.id_cliente == v.trabajador_cliente ?
									`<a title="Editar" href="tareas_livianas/${v.id}/edit">
											<i class="fas fa-pencil"></i>
										</a>
										<button data-toggle="delete" data-id="${v.id}" title="Eliminar" type="submit">
											<i class="fas fa-trash"></i>
										</button>`
									:
									''
								}
							</div>
						`

					}
				}

			]
		},
		server_side:true

	})

})
