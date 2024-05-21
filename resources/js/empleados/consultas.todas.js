import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/consultas/todas',
		get_path:'/busqueda',
		table:$('.tabla_consultas_todas'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),

		server_side:true,

		datatable_options:{
			order:[[3,'desc']],
			columns:[
				{
					name:'id',
					data:'id'
				},
				{
					name:'tipo',
					data:'tipo'
				},
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
						if(meta.settings.json.fichada_user!=1 && meta.settings.json.fichar_user) return ''
						return `
						<div class="acciones_tabla">
							<a title="Ver" href="${(v.tipo == 'medica') ? 'medicas' : 'enfermeria'}/${v.id}">
								<i class="fas fa-eye"></i>
							</a>
						</div>`
					}
				}
			]
		},

		/*render_row:consulta=>{
			return $(`
				<tr>
				<td>${consulta.nombre}</td>
				<td>${consulta.fecha}</td>
				<td>${consulta.derivacion_consulta}</td>

				<td class="acciones_tabla" scope="row">
					<a title="Ver" href="medicas/${consulta.id}">
						<i class="fas fa-eye"></i>
					</a>
				</td>
			</tr>`
			)
		}*/
	})

})