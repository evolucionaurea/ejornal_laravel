import Tablas from '../classes/Tablas.js';

$(()=>{

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
			order:[[4,'desc']],
			columns:[
				{
					data:'trabajador_nombre',
					name:'nominas.nombre',
					render:v=>{
						return v.nombre==null ? '[no cargado]' : v.nombre
					}
				},
				{
					data:row=>row,
					name:'nominas.dni',
					render:v=>{
						return v.trabajador_dni==null ? '[no cargado]' : v.trabajador_dni
					}
				},
				{
					data:row=>row,
					name:'nominas.sector',
					render:v=>{
						return v.trabajador_sector==null ? '[no cargado]' : v.trabajador_sector
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
				{
					data:'fecha_regreso_trabajar',
					name:'fecha_regreso_trabajar',
					render:v=>{
						return v==null ? '[no cargada]' : v
					}
				},
				{
					data:row=>row,
					render:v=>{
						if(v.fecha_regreso_trabajar == null){
							return '<span class="badge badge-danger">ausente</span>'
						}else{
							let str = v.fecha_regreso_trabajar;
							let [dia, mes, anio] = str.split('/');
							let regreso_trabajar = new Date(+anio, mes - 1, +dia);
							let hoy = new Date();
							return regreso_trabajar > hoy  ? '<span class="badge badge-danger">ausente</span>' : ''
						}
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
								<a title="Comunicaciones" href="comunicaciones/${v.id}">
									<i title="Comunicaciones" class="fas fa-bullhorn"></i>
								</a>

								<a title="Documentacion" href="documentaciones/${v.id}">
									<i title="Documentacion" class="fas fa-files-medical"></i>
								</a>

								<a title="Historial" href="ausentismos/${v.id_trabajador}">
									<i title="Historial" class="fas fa-book"></i>
								</a>

								<a title="Editar" href="ausentismos/${v.id}/edit">
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
