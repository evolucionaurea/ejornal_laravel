import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/caratulas',
		get_path:'/busqueda',
		//delete_path:'/destroy',
		table:$('[data-table="caratulas"]'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		//datatable_options:{order:false},
		// delete_message:'¿Seguro deseas borrar este ausentismo?',
		server_side:true,
		datatable_options:{
			order:[[1,'desc']],
			dom:'<"table-spacer-top"l>t<"table-spacer-bottom"ip>',
			columns:[
				{
					data:'id',
					name:'id',
					className:'align-middle'					
				},
				{
					className:'align-middle',
					name:'nombre',
					data:null,
					render:v=>{
						if(v==null) return '<span class="text-muted font-italic">[trabajador no encontrado]</span>'
						return `
							<div>${v.nombre}</div>
							<div class="small">DNI: ${v.dni}</div>
							<div class="small">Legajo: ${v.legajo ? v.legajo : '<i>[Sin legajo]</i>'}</div>
							<span class="badge badge-${v.estado==1 ? 'success' : 'danger'}">${v.estado == 1 ? 'Activo' : 'Inactivo'}</span>
							`
					}
				},
				{
					data:'ultima_caratula',
					name:'ultima_caratula',
					orderable:false,
					className:'align-middle',
					render:v=>{
						if(v.patologias.length==0) return '<span class="text-muted font-italic">[Sin patologías]</span>'
						let patologias = []
						v.patologias.map(p=>{
							patologias.push(`<span class="badge badge-dark p-2 mr-1 mb-1">${p.nombre}</span>`)
						})
						return patologias.join('')
					}
				},
				{
					data:'ultima_caratula',
					name:'ultima_caratula',
					className:'align-middle',
					orderable:false,
					render:v=>{
						return v.user
					}
				},

				{
					data:'ultima_caratula',
					name:'ultima_caratula',
					className:'align-middle',
					orderable:false,
					render:v=>{
						return `
							<div>Peso: ${v.peso} kg.</div>
							<div>Altura: ${v.altura} cm.</div>
							<div>IMC: ${v.imc}</div>
						`
					}
				},
				{
					data:'ultima_caratula',
					name:'ultima_caratula',
					className:'align-middle',
					orderable:false,
					render:v=>{
						return v.medicacion_habitual
					}
				},
				{
					data:'ultima_caratula',
					name:'ultima_caratula',
					className:'align-middle',
					orderable:false,
					render:v=>{
						return v.antecedentes
					}
				},
				{
					data:'ultima_caratula',
					name:'ultima_caratula',
					className:'align-middle',
					orderable:false,
					render:v=>{
						return v.alergias
					}
				},



				{
					data:'ultima_caratula',
					name:'ultima_caratula',
					className:'align-middle',
					orderable:false,
					render:v=>{
						return v.created_at_formatted
					}
				},
				{
					data:null,
					name:'id',
					orderable:false,
					className:'align-middle text-right',
					render:(v,type,row,meta)=>{
						if(meta.settings.json.fichada_user!=1 && meta.settings.json.fichar_user) return ''
						return `
						<div class="acciones_tabla justify-content-end">
							<a title="Editar Carátula" href="nominas/caratulas/${v.id}/edit">
									<i class="fas fa-pencil"></i>
								</a>
								<a title="Ver Historial de Cambios" href="nominas/caratulas/${v.id}">
									<i class="fas fa-history"></i>
								</a>
						</div>`
					}
				},

			]
		}

	})


})
