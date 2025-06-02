import Tablas from '../../classes/Tablas.js';

$(()=>{


	new Tablas({
		controller:'/admin/reportes/tareas-adecuadas',
		get_path:'/busqueda',
		delete_path:'/destroy',
		table:$('[data-table="tareas-livianas"]'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),

		server_side:true,

		datatable_options:{

			order:[[ 4, "desc" ]],
			dom:'<"table-spacer-top"l>t<"table-spacer-bottom"ip>',

			columns:[
				{
					data:null,
					className:'align-middle',
					name:'nominas.nombre',
					render:v=>{

						if(v==null) return '<span class="text-muted">[No Ingresado]</span>'

						return `
							<div>${v.trabajador.nombre}</div>
							<div class="small">DNI: ${v.trabajador.dni}</div>
							${v.id_cliente != v.trabajador.id_cliente ? '<span class="badge badge-dark">transferido</span>' : ''}
						`;

					}
				},
				{
					data:'cliente',
					className:'align-middle',
					name:'cliente',
					render:v=>{
						if(!v) return '<span class="text-muted">[No Ingresado]</span>'
						return v.nombre
					}
				},
				{
					data:'fecha_inicio',
					className:'align-middle',
					name:'fecha_inicio'
				},
				{
					data:'fecha_final',
					className:'align-middle',
					name:'fecha_final'
				},
				{
					data:'created_at_formatted',
					className:'align-middle',
					name:'created_at'
				},
				{
					data:'tipo.nombre',
					className:'align-middle',
					name:'tareas_livianas_tipos.nombre'
				},
				{
					data:'user',
					className:'align-middle',
					name:'user',
				},
				{
					data:null,
					name:'archivos',
					orderable:false,
					className:'align-middle',
					render:data=>{

						if(data.archivo==null) return `<span class="text-muted font-style-italic">[sin archivos]</span>`

						return `<div class="flex flex-wrap">
								<button data-toggle="open-file" class="btn btn-info btn-tiny mr-3 mb-1" data-href="${data.archivo_path_admin}" title="${data.archivo}" >
									<i class="fa fa-download fa-fw"></i> <span>descargar</span>
								</button>
							</div>`
					}
				},
				{
					data:'comunicacion',
					name:'comunicacion',
					width:280,
					render:comunicacion=>{
						return `
							<p><span class="font-weight-bold">Res.:</span> ${comunicacion.tipo.nombre ?? '[no indicado]'}</p>
							<div class="small font-italic"><b>Obs.:</b> ${comunicacion.descripcion}</div>
						`
					}
				}
			]

		}
	})

	$('[data-table="tareas-livianas"]').on('click','[data-toggle="open-file"]',btn=>{
		const href = $(btn.currentTarget).attr('data-href')
		window.open(href)
	})


})
