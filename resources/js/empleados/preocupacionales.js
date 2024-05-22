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
			order:[[ 3, "desc" ]],
			columns:[
				{
					data:'trabajador.nombre',
					name:'nominas.nombre'
				},
				{
					data:'trabajador.email',
					name:'nominas.email'
				},
				{
					data:'trabajador.telefono',
					name:'nominas.telefono'
				},
				{
					data:'fecha',
					name:'fecha'
				},
				{
					data:null,
					name:'fecha_vencimiento',
					render:v=>{
						if(v.fecha_vencimiento==null) return `<span class="text-muted font-style-italic">[sin vencimiento]</span>`
						return v.fecha_vencimiento
					}
				},
				{
					data:'vencimiento_label',
					name:'vencimiento_label',
					orderable:false
				},
				{
					data:null,
					name:'completado',
					render:(v,type,row,meta)=>{
						if(v.fecha_vencimiento==null) return ''
						return `<span class="badge badge-${v.completado?'success':'danger'}">${v.completado?'completado':'sin completar'}</span>`
					}
				},
				{
					data:row=>row,
					name:'file_path',
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
							<a title="Editar" href="preocupacionales/${v.id}/edit" >
								<i class="fas fa-pen"></i>
							</a>

							<button data-toggle="delete" data-id="${v.id}" title="Eliminar" type="submit" >
								<i class="fas fa-trash"></i>
							</button>
						</div>`
					}
				},
			]
		},

		/*render_row:preocupacional=>{

			// Formatear la fecha
			const fecha = new Date(preocupacional.fecha);
			const dia = fecha.getDate().toString().padStart(2, '0');
			const mes = (fecha.getMonth() + 1).toString().padStart(2, '0'); // Los meses van de 0 a 11
			const anio = fecha.getFullYear();

			return $(`
				<tr>
					<td>${preocupacional.nombre}</td>
					<td>${preocupacional.email==null ? 'no cargado' : preocupacional.email}</td>
					<td>${preocupacional.telefono==null ? 'no cargado' : preocupacional.telefono}</td>
					<td>${dia}/${mes}/${anio}</td>
					<td>
						<a class="btn-ejornal btn-ejornal-gris-claro" href="preocupacionales/archivo/${preocupacional.id}" target="_blank">
							<i class="fa fa-file"></i>${preocupacional.archivo}
						</a>
					</td>

					<td class="acciones_tabla" scope="row">

						<a title="Editar" href="preocupacionales/${preocupacional.id}/edit">
							<i class="fas fa-pen"></i>
						</a>

						<button data-toggle="delete" data-id="${preocupacional.id}" title="Eliminar" type="submit">
							<i class="fas fa-trash"></i>
						</button>

					</td>

				</tr>`
			)
		}*/
	})

	$('[data-table="preocupacionales"]').on('click','[data-toggle="open-file"]',btn=>{
		const href = $(btn.currentTarget).attr('data-href')
		window.open(href)
	})

})
