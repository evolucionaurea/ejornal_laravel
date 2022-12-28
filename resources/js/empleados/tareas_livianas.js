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
					render:v=>{
						if(v.fecha_regreso_trabajar == null){
							return '[Ausente]'
						}else{
							let str = v.fecha_regreso_trabajar;
							let [dia, mes, anio] = str.split('/');
							let regreso_trabajar = new Date(+anio, mes - 1, +dia);
							let hoy = new Date();
							return regreso_trabajar > hoy  ? '[Ausente]' : ''
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

								<a title="Historial" href="tareas_livianas/${v.id_trabajador}">
									<i title="Historial" class="fas fa-book"></i>
								</a>

								<a title="Editar" href="tareas_livianas/${v.id}/edit">
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
