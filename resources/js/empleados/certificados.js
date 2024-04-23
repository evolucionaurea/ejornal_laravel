import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/certificados',
		get_path:'/busqueda',
		table:$('.tabla_certificados_ausentismo_listado'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		server_side:true,
		datatable_options:{
			order:[[ 4, "desc" ]],
			dom:'<"table-spacer-top"l>t<"table-spacer-bottom"ip>',
			columns:[
				{
					data:row=>row,
					className:'align-middle',
					name:'nominas.nombre',
					render:v=>{
						return v.ausentismo.trabajador.nombre
					}
				},
				{
					data:'medico',
					className:'align-middle',
					name:'medico'
				},
				{
					data:'institucion',
					className:'align-middle',
					name:'institucion'
				},
				{
					data:row=>row,
					className:'align-middle',
					name:'ausentismos.fecha_inicio',
					render:v=>{
						return v.ausentismo.fecha_inicio
					}
				},
				{
					data:row=>row,
					className:'align-middle',
					name:'ausentismos.fecha_final',
					render:v=>{
						return v.ausentismo.fecha_final
					}
				},
				{
					data:row=>row,
					className:'align-middle',
					name:'ausentismos.fecha_regreso_trabajar',
					render:v=>{
						return v.ausentismo.fecha_regreso_trabajar
					}
				},
				{
					data:'matricula_nacional',
					className:'align-middle',
					name:'matricula_nacional',
					render:v=>{
						return v==null ? '<span class="text-muted font-italic">[no cargada]</span>' : v
					}
				},
				{
					data:'archivos',
					className:'align-middle',
					name:'archivos',
					orderable:false,
					render:archivos=>{

						//empleados/documentacion_ausentismo/archivo/${id}
						if(archivos.length==0) return ''
						let buttons = ''
						archivos.map(archivo=>{
							buttons += `
							<a target="_blank" class="btn btn-info btn-tiny mr-3 mb-1 d-block" href="documentacion_ausentismo/archivo/${archivo.id}">
								<i class="fa fa-download fa-fw"></i> <span>${archivo.archivo}</span>
							</a>`
						})
						return buttons
					}
				}
			]
		}
	})

})
