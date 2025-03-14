import Tablas from '../classes/Tablas.js';

$(()=>{


	new Tablas({
		controller:'/empleados/consultas/todas',
		get_path:'/busqueda',
		table:$('[data-table="consultas"]'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		server_side:true,
		datatable_options:{
			dom:'<"table-spacer-top"il>t<"table-spacer-bottom"ip>',
			order:[[4,'desc']],
			columns:[
				{
					className:'align-middle',
					name:'id',
					data:'id'
				},
				{
					className:'align-middle',
					name:'tipo',
					data:'tipo'
				},
				{
					className:'align-middle',
					name:'nombre',
					data:'trabajador',
					render:v=>{
						console.log('ver', v);
						
						if(v==null) return '<span class="text-muted font-italic">[trabajador no encontrado]</span>'
						return `
							<div>${v.nombre}</div>
							<div class="small">DNI: ${v.dni}</div>
							`
					}
				},

				{
					className:'align-middle',
					name:'estado',
					data:'trabajador',
					orderable:false,
					render:v=>{
						return `<span class="badge badge-${v.estado==1?'success':'danger'}">${v.estado==1?'activo':'inactivo'}</span>`
					}
				},

				{
					className:'align-middle',
					name:'fecha',
					data:'fecha'
				},

				{
					className:'align-middle',
					name:'derivacion_consulta',
					data:row=>row,
					render:(v)=>{
						if(v.derivacion_consulta == null) return 'N/A';
						return v.derivacion_consulta
					}
				},

				{
					className:'align-middle',
					name:'user',
					// data:'user'
					data:row=>row,
					render:(v)=>{
						if(v.user == null) return '[No registrado]';
						return v.user
					}
				},

				{
					className:'align-middle',
					name:'acciones',
					data:row=>row,
					render:(v,type,row,meta)=>{
						console.log(v);
						
						if(meta.settings.json.fichada_user!=1 && meta.settings.json.fichar_user) return '';
						let tipoRuta = v.tipo === 'Médica' ? 'medicas' : (v.tipo === 'Nutricional' ? 'nutricionales' : 'enfermeria');
						return `
						<div class="acciones_tabla">
							<a title="Ver" href="${tipoRuta}/${v.id}">
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