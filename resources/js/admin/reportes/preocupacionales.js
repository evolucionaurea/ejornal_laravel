import Tablas from '../../classes/Tablas.js';

$(()=>{


	new Tablas({
		controller:'/admin/reportes/preocupacionales',
		get_path:'/busqueda',
		delete_path:'/destroy',
		table:$('[data-table="preocupacionales"]'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),

		server_side:true,

		datatable_options:{

			order:[[ 2, "desc" ]],
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
							<div class="small">Tel: ${v.trabajador.telefono}</div>
							${v.id_cliente != v.trabajador_cliente ? '<span class="badge badge-dark">transferido</span>' : ''}
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
					data:'fecha',
					className:'align-middle',
					name:'fecha'
				},
				{
					data:'tipo.name',
					className:'align-middle',
					name:'preocupacionales_tipos_estudio.name'
				},
				{
					data:'user',
					className:'align-middle',
					name:'user',
				},
				{
					data:null,
					name:'fecha_vencimiento',
					className:'align-middle',
					render:v=>{
						if(v.fecha_vencimiento==null) return `<span class="text-muted font-style-italic">[sin vencimiento]</span>`
						return `
							<div>${v.fecha_vencimiento}</div>
							${v.estado_vencimiento_label}
						`
					}
				},
				{
					data:'archivos',
					name:'file_path',
					orderable:false,
					className:'align-middle',
					render:(archivos,type,row,meta)=>{

						if(archivos.length==0) return `<span class="text-muted font-style-italic">[sin archivos]</span>`

						let buttons = ''
						archivos.map((archivo,k)=>{
							buttons += `<div class="flex flex-wrap">
								<button data-toggle="open-file" class="btn btn-info btn-tiny mr-3 mb-1" data-href="${archivo.file_path}" title="${archivo.archivo}" >
									<i class="fa fa-download fa-fw"></i> <span>${archivo.archivo}</span>
								</button>
							</div>`
						})

						return buttons
					}
				},
			]

		}
	})

	$('[data-table="preocupacionales"]').on('click','[data-toggle="open-file"]',btn=>{
		const href = $(btn.currentTarget).attr('data-href')
		window.open(href)
	})

	$('[data-toggle="busqueda-fecha"]').on('change','[name="vencimiento"]',select=>{
		const value = $(select.currentTarget).val()
		if(value=='1'){
			$('[data-toggle="vencimiento"]').removeClass('d-none')
		}else{

			$('[data-toggle="vencimiento"]').addClass('d-none')
			$('[data-toggle="vencimiento"] select').val(null)
		}
	})
	$('[data-toggle="busqueda-fecha"] [data-toggle="clear"]').click(btn=>{
		$('[data-toggle="busqueda-fecha"] [name="vencimiento"]').trigger('change')
	})

})
