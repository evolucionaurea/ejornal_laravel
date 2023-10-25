import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/clientes/ausentismos',
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
					className:'align-middle',
					render:v=>{
						const estado = v.trabajador_estado == '1' ? `<span class="badge badge-success">activo</span>` : `<span class="badge badge-danger">inactivo</span>`
						return `
							<div>${v.trabajador_nombre}</div>
							${estado}
						`
					}
				},
				{
					data:row=>row,
					name:'nominas.dni',
					className:'align-middle',
					render:v=>{
						return v.trabajador_dni==null ? '[no cargado]' : v.trabajador_dni
					}
				},
				{
					data:row=>row,
					name:'nominas.sector',
					className:'align-middle',
					render:v=>{
						return v.trabajador_sector==null ? '[no cargado]' : v.trabajador_sector
					}
				},
				{
					data:'ausentismo_tipo',
					name:'ausentismo_tipo.nombre',
					className:'align-middle',
				},
				{
					data:'fecha_inicio',
					name:'fecha_inicio',
					className:'align-middle',
				},
				{
					data:'fecha_final',
					name:'fecha_final',
					className:'align-middle',
					render:v=>{
						return v==null ? '[no cargada]' : v
					}
				},
				{
					data:'fecha_regreso_trabajar',
					name:'fecha_regreso_trabajar',
					className:'align-middle',
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
					className:'align-middle',
					render:v=>{
						if(v.fecha_final == null){
							if(v.incluir_indice===0) return '<span class="badge badge-warning">vigente</span>'
							return '<span class="badge badge-danger">ausente</span>'
						}else{
							let str = v.fecha_final;
							let [dia, mes, anio] = str.split('/');
							let regreso_trabajar = new Date(+anio, mes - 1, +dia);
							let hoy = new Date();
							if(regreso_trabajar > hoy && v.incluir_indice===0) return '<span class="badge badge-warning">vigente</span>'
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
