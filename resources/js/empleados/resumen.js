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
					data:'trabajador.nombre',
					name:'nominas.nombre',
					className:'align-middle'
				},
				{
					data:'trabajador.email',
					name:'nominas.email',
					className:'align-middle'
				},
				{
					data:'trabajador.telefono',
					name:'nominas.telefono',
					className:'align-middle'
				},
				{
					data:'fecha',
					name:'fecha',
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
					render:v=>{
						return `
						<button data-toggle="open-file" class="btn btn-info btn-tiny mr-3 mb-1" data-href="${v.file_path}" title="${v.archivo}" >
							<i class="fa fa-download fa-fw"></i> <span>${v.archivo}</span>
						</button>`
					}
				},
				{
					data:row=>row,
					name:'actions',
					className:'text-right',
					orderable:false,
					render:v=>{
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
		try{
			const response = await axios.post(`preocupacionales/completar/${id}`)
			toastr.success(response.data.message)
			tr.remove()
		}catch(e){
			toastr.error('Hubo un error en la solicitud')
		}
	})

})
