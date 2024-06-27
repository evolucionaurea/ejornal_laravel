import Tablas from '../../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/admin/reportes',
		get_path:'/certificaciones',
		table:$('[data-table="certificaciones"]'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		server_side:true,

		datatable_options:{
			order:[[4,'desc']],
			columns:[
				{
					data:'trabajador',
					name:'clientes.nombre',
					render:v=>{

						if(v==null) return '<span class="text-muted font-italic">[sin asociar]</span>'
						if(v.cliente==null) return '<span class="text-muted font-italic">[sin asociar]</span>'
						return v.cliente.nombre
					}
				},
				{
					data:'trabajador',
					name:'nominas.nombre',
					render:v=>{
						if(v==null) return '<span class="text-muted font-italic">[sin asociar]</span>'
						return v.nombre
					}
				},
				{
					data:'user',
					name:'user'
				},
				{
					data:'tipo',
					name:'ausentismo_tipo.nombre',
					render:v=>{
						return v.nombre
					}
				},
				{
					data:'fecha_inicio',
					name:'fecha_inicio'
				},
				{
					data:'fecha_final',
					name:'fecha_final',
					render:v=>{
						return v==null ? '<i class="text-muted">[no cargada]</i>' : v
					}
				},
				{
					data:'dias_ausente',
					name:'dias_ausente'
				},
				{
					data:'documentaciones',
					name:'documentaciones',
					orderable:false,
					render:v=>{
						if(v.length==0) return '<span class="text-muted font-italic">[no tiene]</span>'

						let docs = []
						v.map(doc=>{

							console.log(doc)

							//

							let el = `
							<div class="mb-3">
								<div class="mb-1 small">
									<b>Institución</b>: ${doc.institucion} -
									<b>Médico</b>: ${doc.medico} -
									<b>Matrícula Nacional</b>: ${doc.matricula_nacional} -
									<b>Matrícula Provincial</b>: ${doc.matricula_provincial} -
									<b>Fecha documento</b>: ${doc.fecha_documento}
								</div>
							`

							doc.archivos.map(archivo=>{
								el += `<a class="small text-success d-block" target="_blank" href="documentacion_ausentismo/descargar/${doc.id}"><i class="fa fa-download fa-fw"></i> ${archivo.archivo}</a>`
							})
							el += `</div>`

							docs.push(el)


						})
						return docs.join('')
					}
				}
			]
		}
	})

})
