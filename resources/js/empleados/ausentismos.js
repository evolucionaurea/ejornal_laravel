import Tablas from '../classes/Tablas.js';

$(()=>{

	//testing

	new Tablas({
		controller:'/empleados/ausentismos',
		get_path:'/busqueda',
		delete_path:'/destroy',
		table:$('.tabla_ausentismos'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		//datatable_options:{order:false},
		delete_message:'¿Seguro deseas borrar este ausentismo?',
		server_side:true,
		datatable_options:{
			order:[[5,'desc']],
			dom:'<"table-spacer-top"l>t<"table-spacer-bottom"ip>',
			columns:[
				{
					data:row=>row,
					name:'ausentismos.id',
					className:'align-middle',
					render:v=>{
						return `
							<div><b>${v.id}</b></div>
						`
					}
				},
				{
					data:row=>row,
					name:'nominas.nombre',
					className:'align-middle',
					render:v=>{
						let output = `
						<div>
							<a href="${v.trabajador_perfil_url}" target="_blank" class="text-dark">${v.trabajador_nombre}</a>
						</div>`
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
					name:'nominas.dni',
					className:'align-middle',
					render:v=>{
						return v.trabajador_dni==null ? '[no cargado]' : v.trabajador_dni
					}
				},
				{
					data:row=>row,
					name:'nominas.sector',
					className:'align-middle',
					render:v=>{
						return v.trabajador_sector==null ? '[no cargado]' : v.trabajador_sector
					}
				},
				{
					data:'ausentismo_tipo',
					className:'align-middle',
					name:'ausentismo_tipo.nombre'
				},

				{
					data:'fecha_inicio',
					className:'align-middle',
					name:'fecha_inicio'
				},
				{
					data:'fecha_final',
					className:'align-middle',
					name:'fecha_final',
					render:v=>{
						return v==null ? '[no cargada]' : v
					}
				},
				{
					data:'fecha_regreso_trabajar',
					className:'align-middle',
					name:'fecha_regreso_trabajar',
					render:v=>{
						return v==null ? '[no cargada]' : v
					}
				},

				{
					data:'dias_mes_actual',
					///orderable:false,
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
					className:'align-middle',
					name:'fecha_final',
					orderable:false,
					render:v=>{
						if(v.ausente===1 && v.incluir_indice===1) return '<span class="badge badge-danger">ausente</span>'
						if(v.ausente===1 && v.incluir_indice===0) return '<span class="badge badge-warning">vigente</span>'
						return ''
					}
				},

				{
					data:row=>row,
					name:'actions',
					orderable:false,
					className:'align-middle',
					render:(v,type,row,meta)=>{

						if(meta.settings.json.fichada_user!=1 && meta.settings.json.fichar_user) return ''

						//if(v.id_cliente != v.trabajador_cliente) return ''

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

						/**/

						/*	<a title="Ver Ausentismo (Com: ${v.comunicaciones_count} / Cert: ${v.documentaciones_count})" href="ausentismo/${v.id}">
										<i class="fa fa-eye"></i>
									</a>*/

						return `
							<div class="acciones_tabla justify-content-end">
								${
									(mostrar_extension == true)
									?
									`
									 <button
										class="extension_de_licencia"
										title="extension de licencia"
										data-toggle="modal"
										data-target="#extensionLicenciaModal"
										data-info="${v.id}"
									 >
										<i title="extension de licencia" class="fas fa-forward"></i>
									 </button>
									 `
									 :
									 ''
								}

								<a title="Historial" href="ausentismos/${v.id}">
									<i title="Historial" class="fas fa-book"></i>
								</a>

								${
									v.id_cliente == v.trabajador_cliente ? `

									<a title="Comunicaciones" href="comunicaciones/${v.id}" class="btn-label">
										<i title="Comunicaciones" class="fas fa-bullhorn"></i>
										<span>(${v.comunicaciones_count})</span>
									</a>

									<a title="Certificados" href="documentaciones/${v.id}" class="btn-label">
										<i title="Certificados" class="fas fa-files-medical"></i>
										<span>(${v.documentaciones_count})</span>
									</a>

									<a title="Editar" href="ausentismos/${v.id}/edit">
										<i class="fas fa-pencil"></i>
									</a>
									<button data-toggle="delete" data-id="${v.id}" title="Eliminar" >
										<i class="fas fa-trash"></i>
									</button>`
									:
									''
								}
							</div>`

					}
				}

			]
		}

	})


})
