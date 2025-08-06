import Tablas from '../classes/Tablas.js';
import Certificado from '../classes/Certificado.js'

$(()=>{

	new Tablas({
		controller:'/empleados/certificados',
		get_path:'/busqueda',

		table:$('[data-table="certificados"]'),

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
						let output = `
							<div>${v.ausentismo.trabajador.nombre}</div>
							<div class="small text-muted">${v.ausentismo.trabajador.dni}</div>
						`
						if(v.id_cliente != v.trabajador_cliente) output += `<span class="badge badge-dark">transferido</span>`

						return output
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
					render:(archivos,type,row,meta)=>{

						if(meta.settings.json.fichada_user!=1 && meta.settings.json.fichar_user) return '<span class="text-muted small font-italic">[debes fichar]</span>'

						if(archivos.length==0) return ''
						let buttons = ''
						archivos.map((archivo,k)=>{
							///console.log(archivo.file_path)
							/// /empleados/documentacion_ausentismo/archivo/${archivo.id}
							buttons += `<div class="flex flex-wrap">
								<button data-toggle="open-file" class="btn btn-info btn-tiny mr-3 mb-1" data-href="${archivo.file_path}" title="${archivo.archivo}" >
									<i class="fa fa-download fa-fw"></i> <span>${archivo.archivo}</span>
								</button>
							</div>`
						})

						return buttons
					}
				},

				{
					data:row=>row,
					className:'align-middle',
					name:'actions',
					orderable:false,
					render:(v,type,row,meta)=>{

						if(meta.settings.json.fichada_user!=1 && meta.settings.json.fichar_user) return '<span class="text-muted small font-italic">[debes fichar]</span>'
						if(v.id_cliente != v.trabajador_cliente) return ''

						return `
						<button data-toggle="editar-certificado" class="btn-ejornal btn-ejornal-dark btn-tiny">
							<i class="fa fa-pencil fa-fw"></i> <span>Editar</span>
						</button>`
					}
				}
			]
		}
	})
	new Certificado



	$('[data-table="certificados"]').on('click','[data-toggle="open-file"]',btn=>{
		const href = $(btn.currentTarget).attr('data-href')
		window.open(href)
	})

})
