import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/medicamentos_movimientos',
		get_path:'/busqueda',
		table:$('[data-table="medicamentos-movimientos"]'),
		modulo_busqueda:$('[data-toggle="busqueda-filtros"]'),
		///datatable_options:{order:[[ 0, "desc" ]]},
		server_side:true,

		datatable_options:{
			order:[[7,'desc']],

			dom:'<"table-spacer-top"l>t<"table-spacer-bottom"ip>',

			columns:[

				{
					data:null,
					name:'medicamentos.nombre',
					className:'align-middle border-left',
					render:v=>{
						return v.stock_medicamento.medicamento.nombre
					}
				},
				{
					data:null,
					name:'tipo_consulta',
					className:'align-middle border-left',
					orderable:false,
					render:v=>{
						if(v.id_consulta_enfermeria==null && v.id_consulta_medica==null) return '<span class="text-muted font-italic">[ingreso / egreso]</span>'
						if(v.id_consulta_enfermeria!=null) return 'Enfermería'
						if(v.id_consulta_medica!=null) return 'Médica'
						return ''
					}
				},
				{
					data:null,
					name:'user',
					orderable:false,
					className:'align-middle border-left',
					render:v=>{
						if(v.id_consulta_enfermeria != null) return v.consulta_enfermeria.user
						if(v.id_consulta_medica != null) return v.consulta_medica.user
						if(v.user!=null) return v.user
						return v.stock_medicamento.user.nombre
					}
				},

				{
					data:null,
					name:'trabajador',
					className:'align-middle border-left',
					sortable:false,
					render:v=>{
						if(v.id_consulta_enfermeria != null) return v.consulta_enfermeria.trabajador.nombre
						if(v.id_consulta_medica != null) return v.consulta_medica.trabajador.nombre
						return '<span class="text-muted font-italic">[no aplica]</span>'

					}
				},

				{
					data:'suministrados',
					name:'suministrados',
					className:'align-middle border-left',
					render:v=>{
						return v==null || v==0 ? '-' : v
					}
				},
				{
					data:'egreso',
					name:'egreso',
					className:'align-middle border-left',
					render:v=>{
						return v==null || v==0 ? '-' : v
					}
				},
				{
					data:null,
					name:'motivo',
					className:'align-middle border-left',

					render:v=>{
						if(v.motivo==null){
							if(v.id_consulta_enfermeria!=null) return `<span class="text-muted font-italic">Suministrado en Enfermería</span>`
							if(v.id_consulta_medica!=null) return `<span class="text-muted font-italic">Suministrado en Consulta Médica</span>`
							return v.stock_medicamento.motivo
						}
						return v.motivo
					}
				},
				{
					data:'created_at',
					name:'created_at',
					className:'align-middle border-left',
					render:v=>{
						return `${v} hs.`
					}
				}
			]
		}

	})

	$('[data-toggle="busqueda-filtros"]').find('[name="from"], [name="to"]').datepicker()

})
