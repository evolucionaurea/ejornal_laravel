import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/grupos/nominas',
		get_path:'/historial_listado',
		table:$('[data-table="historial_nominas"]'),
		//modulo_busqueda:$('[data-toggle="busqueda-filtros"]'),
		server_side:true,

		datatable_options:{
			order:[[0,'desc']],
			dom:'<"table-spacer-top"l>t<"table-spacer-bottom"ip>',
			columns:[
				{
					data:'year',
					name:'year_month'
				},
				{
					data:'month',
					name:'year_month',
					sortable:false
				},
				{
					data:'cantidad',
					name:'cantidad'
				},
				{
					data:'dif_mes_anterior',
					name:'cantidad',
					sortable:false,
					render:v=>{
						if(v===0) return v
						if(v>0) return `<span class="text-success">+${v} <i class="fa fa-level-up fa-fw"></i></span>`
						if(v<0) return `<span class="text-danger">${v} <i class="fa fa-level-down fa-fw"></i></span>`
					}
				}

			]
		}

	})


})