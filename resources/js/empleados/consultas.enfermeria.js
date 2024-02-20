import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/consultas/enfermeria',
		get_path:'/busqueda',
		table:$('.tabla_consultas_enfermeria'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),

		server_side:true,

		datatable_options:{
			order:[[ 1, "desc" ]],
			columns:[
				{
					name:'nombre',
					data:'nombre'
				},

				{
					name:'fecha',
					data:'fecha'
				},

				{
					name:'derivacion_consulta',
					data:'derivacion_consulta'
				},

				{
					name:'acciones',
					data:row=>row,
					render:(v,type,row,meta)=>{
						if(meta.settings.json.fichada_user!=1 && meta.settings.json.fichar_user!=0) return ''
						return `
						<div class="acciones_tabla">
							<a title="Ver" href="enfermeria/${v.id}">
								<i class="fas fa-eye"></i>
							</a>
						</div>`
					}
				}
			]
		},

	})

})