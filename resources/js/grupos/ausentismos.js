import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/grupos/ausentismos',
		get_path:'/busqueda',
		table:$('.tabla_ausentismos'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		server_side:true,
		datatable_options:{
			//ordering:false,
			order:[[5,'desc']],

			dom:'<"table-spacer-top"l>t<"table-spacer-bottom"ip>',

			columns:[
				{
					data:row=>row,
					name:'nominas.nombre',
					render:v=>{
						return `
							<div><b>${v.trabajador_nombre}</b></div>
							<div class="badge badge-${v.trabajador_estado==1 ? 'success' : 'danger'}">${v.trabajador_estado==1 ? 'activo' : 'inactivo'}</div>
						`
					}
				},
				{
					data:row=>row,
					name:'nominas.dni',
					render:v=>{
						return v.trabajador_dni==null ? '[no cargado]' : v.trabajador_dni
					}
				},
				{
					data:row=>row,
					name:'nominas.sector',
					render:v=>{
						return v.trabajador_sector==null ? '[no cargado]' : v.trabajador_sector
					}
				},
				{
					data:'ausentismo_tipo',
					name:'ausentismo_tipo.nombre'
				},
				{
					data:'fecha_inicio',
					name:'fecha_inicio'
				},
				{
					data:'fecha_final',
					name:'fecha_final',
					render:v=>{
						return v==null ? '[no cargada]' : v
					}
				},
				{
					data:'fecha_regreso_trabajar',
					name:'fecha_regreso_trabajar',
					render:v=>{
						return v==null ? '[no cargada]' : v
					}
				},

				{
					data:'dias_mes_actual',
					orderable:false,
					className:'align-middle',
					name:'dias_mes_actual',
					render:v=>{
						return v>0 ? v : 0
					}
				},
				{
					data:'total_dias',
					orderable:false,
					className:'align-middle',
					render:v=>{
						return v
					}
				},


				{
					data:row=>row,
					orderable:false,
					render:v=>{
						if(v.fecha_final == null){
							return '<span class="badge badge-danger">ausente</span>'
						}else{
							let str = v.fecha_final;
							let [dia, mes, anio] = str.split('/');
							let regreso_trabajar = new Date(+anio, mes - 1, +dia);
							let hoy = new Date();
							return regreso_trabajar > hoy  ? '<span class="badge badge-danger">ausente</span>' : ''
						}
					}
				},
				{
					data:row=>row,
					orderable:false,
					render:v=>{
						return ''
					}
				}
			]
		}

	})

})
