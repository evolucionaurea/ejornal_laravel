import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/preocupacionales',
		get_path:'/busqueda',
		delete_path:'/destroy',
		table:$('[data-table="preocupacionales"]'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),

		delete_message:'¿Seguro deseas borrar este estudio médico?',

		server_side:true,

		datatable_options:{
			order:[[ 1, "desc" ]],
			columns:[
				{
					data:null,
					className:'align-middle',
					name:'nominas.nombre',
					render:v=>{
						//console.log('objeto v', v);

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
					data:null,
					name:'fecha_vencimiento',
					className:'align-middle',
					render:v=>{
						if(v.fecha_vencimiento==null) return `<span class="text-muted font-style-italic">[sin vencimiento]</span>`
						return v.fecha_vencimiento
					}
				},
				{
					data:'vencimiento_label',
					name:'vencimiento_label',
					className:'align-middle',
					orderable:false
				},
				{
					data:'completado_label',
					className:'align-middle',
					name:'completado'
				},
				{
					data:'archivos',
					name:'file_path',
					orderable:false,
					className:'align-middle',
					render:(archivos,type,row,meta)=>{

						if(archivos.length==0) return `<span class="text-muted font-style-italic">[sin archivos]</span>`
						if(meta.settings.json.fichada_user!=1 && meta.settings.json.fichar_user) return '<span class="text-muted small font-italic">[debes fichar]</span>'

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
				{
					data:row=>row,
					name:'actions',
					className:'text-right align-middle',
					orderable:false,
					render:(v,type,row,meta)=>{

						if(meta.settings.json.fichada_user!=1 && meta.settings.json.fichar_user) return '<span class="text-muted small font-italic">[debes fichar]</span>'

						if(v.id_cliente != v.trabajador_cliente) return ''

						return `
						<div class="acciones_tabla justify-content-end">
							<a title="Editar" href="preocupacionales/${v.id}/edit" >
								<i class="fas fa-pen"></i>
							</a>

							<button data-toggle="delete" data-id="${v.id}" title="Eliminar"  >
								<i class="fas fa-trash"></i>
							</button>

						</div>`
					}
				},
			]
		}
	})

	$('[data-table="preocupacionales"]').on('click','[data-toggle="open-file"]',btn=>{
		const href = $(btn.currentTarget).attr('data-href')
		window.open(href)
	})

	$('[data-table="preocupacionales"]').on('click','[data-toggle="completado"]',async btn=>{
		const id = $(btn.currentTarget).attr('data-id')
		const tr = $(btn.currentTarget).closest('tr')
		try{
			const response = await axios.post(`preocupacionales/completar/${id}`)
			toastr.success(response.data.message)
			tr.remove()
		}catch(e){
			toastr.error('Hubo un error en la solicitud')
		}
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
