import Tablas from '../Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/ausentismos',
		table:$('.tabla_ausentismos'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		//datatable_options:{order:false},
		delete_message:'¿Seguro deseas borrar este ausentismo?',

		datatable_options:{
			//ordering:false,
			columns:[
				{
					data:'nombre',
					name:'nominas.nombre'
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
					data:'nombre_ausentismo',
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
		},
		server_side:true

	})

})
