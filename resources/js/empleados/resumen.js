import Tablas from '../classes/Tablas.js';
import toastr from 'toastr';

$(()=>{

	new Tablas({
		controller:'/empleados/preocupacionales',
		get_path:'/busqueda',
		delete_path:'/destroy',
		table:$('[data-table="preocupacionales"]'),
		modulo_busqueda:$('[data-toggle="busqueda-preocupacionales"]'),

		delete_message:'¿Seguro deseas borrar este estudio médico?',

		server_side:true,

		datatable_options:{
			order:[[ 3, "desc" ]],
			columns:[
				{
					data:'trabajador',
					name:'nominas.nombre',
					className:'align-middle',
					render:v=>{
						return `
							<div class="font-weight-bold">${v.nombre}<div>
							<div class="small">DNI: ${v.dni}</div>
							<div class="small">Tel: ${v.telefono}</div>

							${v.trabajador_cliente != v.id_cliente ? `<span class="badge badge-dark">transferido</span>` : ''}
						`
					}
				},
				{
					data:'fecha',
					name:'fecha',
					className:'align-middle'
				},
				{
					data:'tipo.name',
					name:'preocupacionales_tipos_estudio.name',
					className:'align-middle'
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
					data:row=>row,
					name:'file_path',
					className:'align-middle',
					orderable:false,
					render:(v,type,row,meta)=>{

						if(v.archivos.length==0) return `<span class="text-muted font-style-italic">[sin archivos]</span>`
						if(meta.settings.json.fichada_user!=1 && meta.settings.json.fichar_user) return '<span class="text-muted small font-italic">[debes fichar]</span>'

						let buttons = ''
						v.archivos.map((archivo,k)=>{
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


						return `
						<div class="acciones_tabla justify-content-end">
							<button data-toggle="completado" data-id="${v.id}" title="Marcar como completado" class="btn-success" >
								<i class="fas fa-check"></i>
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
		const swal = await Swal.fire({
			input:'textarea',
			inputLabel:'Marcar completado y dejar un comentario',
			inputPlaceholder:'Ingresa un comentario',
			inputAttributes:{
				required:true
			},
			showCancelButton:true,
			cancelButtonText:'Cancelar',
			confirmButtonText:'Guardar',
			reverseButtons:true
		})
		if(swal.isDismissed) return false
		try{
			const response = await axios.post(`preocupacionales/completar`,{
				id:id,
				comentarios:swal.value
			})
			toastr.success(response.data.message)
			tr.remove()
		}catch(e){
			toastr.error('Hubo un error en la solicitud')
		}
	})

})
